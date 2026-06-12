<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Therapist;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function bookingForm(Request $request)
    {
        $userGender = Auth::user()->gender;
        $query = Therapist::where('status', 'Active');
        if ($userGender) {
            $query->where('gender', $userGender);
        }
        $therapists = $query->get();
        $services   = Service::active()->get();

        $user = Auth::user();
        $isMember = (bool)$user->is_member;
        
        $discountPercentageWd = 0;
        $discountPercentageWe = 0;
        $hasDiscountQuotaWd = false;
        $hasDiscountQuotaWe = false;

        if ($isMember) {
            $tier = $user->membershipTier;
            if (!$tier) {
                $tier = \App\Models\MembershipTier::active()->first();
            }
            
            if ($tier) {
                $discountPercentageWd = (int)$tier->discount_wd;
                $discountPercentageWe = (int)$tier->discount_we;
                
                $startDate = \Carbon\Carbon::now()->subDays($tier->window);
                
                // Weekday bookings count
                if ($tier->limit_wd === null) {
                    $hasDiscountQuotaWd = true;
                } else {
                    $usedCountWd = Booking::where('user_id', $user->id)
                        ->where('is_membership_discount_applied', true)
                        ->where('created_at', '>=', $startDate)
                        ->get()
                        ->filter(function($booking) {
                            return !\Carbon\Carbon::parse($booking->schedule_date)->isWeekend();
                        })
                        ->count();
                    $hasDiscountQuotaWd = ($usedCountWd < $tier->limit_wd);
                }
                
                // Weekend bookings count
                if ($tier->limit_we === null) {
                    $hasDiscountQuotaWe = true;
                } else {
                    $usedCountWe = Booking::where('user_id', $user->id)
                        ->where('is_membership_discount_applied', true)
                        ->where('created_at', '>=', $startDate)
                        ->get()
                        ->filter(function($booking) {
                            return \Carbon\Carbon::parse($booking->schedule_date)->isWeekend();
                        })
                        ->count();
                    $hasDiscountQuotaWe = ($usedCountWe < $tier->limit_we);
                }
            }
        }

        $adminFee = (int) \App\Models\WebSetting::get('admin_fee', '5000');
        $ppnPercentage = (float) \App\Models\WebSetting::get('ppn_percentage', '11');

        return view('customer.booking', compact(
            'therapists', 'services', 'isMember', 
            'discountPercentageWd', 'discountPercentageWe', 
            'hasDiscountQuotaWd', 'hasDiscountQuotaWe',
            'adminFee', 'ppnPercentage'
        ));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_key' => 'required|string',
            'therapist_name' => 'required|string',
            'schedule_date' => 'required|date|after_or_equal:today',
            'schedule_time' => 'required|string',
            'location_type' => 'required|string|in:home,clinic',
            'address' => 'nullable|string',
            'duration' => 'required|integer|in:60,90,120',
        ]);

        // Resolve Service Info from Database
        $serviceKey = $request->service_key;
        $serviceModel = Service::where('slug', $serviceKey)->where('status', 'Active')->first();
        if (!$serviceModel) {
            $serviceModel = Service::active()->first();
        }
        $service = [
            'name'  => $serviceModel->name . ' (' . $serviceModel->default_duration . ')',
            'price' => $request->location_type === 'home' ? $serviceModel->price_home : $serviceModel->price_clinic,
        ];

        // Find therapist and ensure matching gender
        $userGender = Auth::user()->gender;
        $therapistQuery = Therapist::where('name', $request->therapist_name)->where('status', 'Active');
        if ($userGender) {
            $therapistQuery->where('gender', $userGender);
        }
        $therapist = $therapistQuery->first();

        if (!$therapist) {
            return back()->withErrors(['therapist_name' => 'Terapis yang dipilih tidak tersedia atau tidak sesuai dengan jenis kelamin Anda.'])->withInput();
        }

        // If booking date is today, check if selected time has already passed
        if (\Carbon\Carbon::parse($request->schedule_date)->isToday()) {
            $currentTime = \Carbon\Carbon::now();
            $bookingTime = \Carbon\Carbon::parse($request->schedule_date . ' ' . $request->schedule_time);
            
            if ($bookingTime->isPast()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Waktu terapi yang dipilih sudah terlewat untuk hari ini. Silakan pilih jam lain.'
                    ], 400);
                }
                return back()->withErrors(['schedule_time' => 'Waktu terapi yang dipilih sudah terlewat untuk hari ini.'])->withInput();
            }
        }

        // Prevent overlapping bookings for the therapist
        $existingBookings = Booking::where('therapist_id', $therapist->id)
            ->where('schedule_date', $request->schedule_date)
            ->where('status', '!=', 'Dibatalkan')
            ->get();

        $duration = (int) $request->duration;
        $isOverlap = false;
        foreach ($existingBookings as $eb) {
            $ebDuration = (int) ($eb->duration ?? 90);
            if (self::areIntervalsOverlapping($request->schedule_time, $duration, $eb->schedule_time, $ebDuration)) {
                $isOverlap = true;
                break;
            }
        }

        if ($isOverlap) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terapis yang dipilih tidak tersedia (jadwal bentrok dengan pesanan lain pada jam tersebut). Silakan pilih terapis atau waktu lain.'
                ], 400);
            }
            return back()->withErrors(['schedule_time' => 'Terapis yang dipilih tidak tersedia (jadwal bentrok dengan pesanan lain pada jam tersebut).'])->withInput();
        }

        $user = Auth::user();
        $isMember = (bool)$user->is_member;

        // Calculate transportation fee (free if member)
        $transportFee = 0;
        if ($request->location_type === 'home') {
            $transportFee = $isMember ? 0 : 20000;
        }

        // Proportional price calculation based on selected duration vs default duration
        $defaultDuration = (int) filter_var($serviceModel->default_duration, FILTER_SANITIZE_NUMBER_INT);
        if ($defaultDuration <= 0) {
            $defaultDuration = 90; // fallback
        }
        $basePrice = $request->location_type === 'home' ? $serviceModel->price_home : $serviceModel->price_clinic;
        $servicePrice = (int) round($basePrice * ($request->duration / $defaultDuration));

        // Calculate discount
        $discountAmount = 0;
        $isMembershipDiscountApplied = false;
        if ($isMember) {
            $tier = $user->membershipTier;
            if (!$tier) {
                $tier = \App\Models\MembershipTier::active()->first();
            }
            
            if ($tier) {
                $bookingDate = \Carbon\Carbon::parse($request->schedule_date);
                $isWeekend = $bookingDate->isWeekend();
                
                $discountPercentage = $isWeekend ? $tier->discount_we : $tier->discount_wd;
                $limit = $isWeekend ? $tier->limit_we : $tier->limit_wd;
                $startDate = \Carbon\Carbon::now()->subDays($tier->window);
                
                $hasQuota = false;
                if ($limit === null) {
                    $hasQuota = true;
                } else {
                    $usedCount = Booking::where('user_id', $user->id)
                        ->where('is_membership_discount_applied', true)
                        ->where('created_at', '>=', $startDate)
                        ->get()
                        ->filter(function($booking) use ($isWeekend) {
                            return \Carbon\Carbon::parse($booking->schedule_date)->isWeekend() === $isWeekend;
                        })
                        ->count();
                    $hasQuota = ($usedCount < $limit);
                }
                
                if ($hasQuota) {
                    $discountAmount = ($servicePrice * $discountPercentage) / 100;
                    $isMembershipDiscountApplied = true;
                }
            }
        }

        $adminFeeSetting = (int) \App\Models\WebSetting::get('admin_fee', '5000');
        $ppnPercentage = (float) \App\Models\WebSetting::get('ppn_percentage', '11');

        $subtotal = $servicePrice + $transportFee - $discountAmount;
        $taxAmount = (int) round(($subtotal * $ppnPercentage) / 100);
        $totalPayment = $subtotal + $adminFeeSetting + $taxAmount;

        // Generate Order ID format: TRX-2605-XXX
        // Using current year/month for formatting e.g. TRX-2605-123
        $datePrefix = date('ym');
        $randomNum = rand(100, 999);
        $orderId = "TRX-{$datePrefix}-{$randomNum}";

        // Ensure unique
        while (Booking::where('id', $orderId)->exists()) {
            $randomNum = rand(100, 999);
            $orderId = "TRX-{$datePrefix}-{$randomNum}";
        }

        // Get user profile address fallback if Home Service
        $address = $request->location_type === 'home' 
            ? ($request->address ?: Auth::user()->address ?: 'Solo, Jawa Tengah') 
            : 'Klinik Utama Nusa Terapi, Solo';

        // Update user profile address in database
        if ($request->location_type === 'home' && $request->filled('address')) {
            $user = Auth::user();
            if ($user) {
                \App\Models\User::where('id', $user->id)->update(['address' => $request->address]);
            }
        }

        $status = 'Menunggu Pembayaran';
        $payStatus = 'Belum Bayar';

        // Create booking in database
        Booking::create([
            'id'            => $orderId,
            'user_id'       => Auth::id(),
            'therapist_id'  => $therapist ? $therapist->id : null,
            'service_name'  => $serviceModel->name . ' (' . $request->duration . ' Menit)',
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'duration'      => $request->duration,
            'location_type' => $request->location_type,
            'address'       => $address,
            'service_price' => $servicePrice,
            'transport_price' => $transportFee,
            'discount_amount' => $discountAmount,
            'is_membership_discount_applied' => $isMembershipDiscountApplied,
            'admin_fee'     => $adminFeeSetting,
            'tax_amount'    => $taxAmount,
            'total_payment' => $totalPayment,
            'status'        => $status,
            'pay_status'    => $payStatus,
        ]);

        $message = 'Pesanan Anda berhasil dibuat dan menunggu pembayaran!';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'booking_id' => $orderId,
                'message' => $message
            ]);
        }

        return redirect()->route('customer.history')->with('success', $message);
    }

    public function history()
    {
        // Auto-expire bookings older than 10 minutes
        Booking::where('user_id', Auth::id())
            ->where('status', 'Menunggu Pembayaran')
            ->where('created_at', '<', now()->subMinutes(10))
            ->update([
                'status' => 'Dibatalkan',
                'pay_status' => 'Kadaluarsa'
            ]);

        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.history', compact('bookings'));
    }

    public function historyDetail($id)
    {
        // Auto-expire this booking if it's older than 10 minutes
        $booking = Booking::with('therapist')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($booking->status === 'Menunggu Pembayaran' && $booking->created_at->addMinutes(10)->isPast()) {
            $booking->update([
                'status' => 'Dibatalkan',
                'pay_status' => 'Kadaluarsa'
            ]);
        }

        return view('customer.history_detail', compact('booking'));
    }

    public function cancelBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($booking->status === 'Akan Datang' || $booking->status === 'Menunggu Pembayaran') {
            $booking->update([
                'status' => 'Dibatalkan',
                'pay_status' => 'Batal'
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan ini tidak dapat dibatalkan.'
        ], 400);
    }

    public function reviewForm($id)
    {
        $booking = Booking::with('therapist')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'Selesai')
            ->firstOrFail();

        return view('customer.review', compact('booking'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'Selesai')
            ->firstOrFail();

        $booking->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Dynamically update the therapist's average rating in the database
        if ($booking->therapist_id) {
            $therapist = $booking->therapist;
            $avg = Booking::where('therapist_id', $therapist->id)
                ->whereNotNull('rating')
                ->avg('rating');
            $therapist->update([
                'rating' => round($avg, 1)
            ]);
        }

        return redirect()->route('customer.history')->with('success', 'Terima kasih atas ulasan Anda!');
    }

    public function payBooking($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($booking->status === 'Menunggu Pembayaran') {
            $booking->update([
                'status' => 'Akan Datang',
                'pay_status' => 'Lunas'
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan ini tidak dapat dibayar.'
        ], 400);
    }

    /**
     * Show the reschedule form for a booking.
     */
    public function rescheduleForm($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($booking->status, ['Akan Datang', 'Menunggu Pembayaran'])) {
            return redirect()->route('customer.history.detail', $id)
                ->with('error', 'Pesanan ini tidak dapat dijadwal ulang.');
        }

        $user = Auth::user();
        $isMember = $user->is_member && $user->membershipTier;

        return view('customer.reschedule', compact('booking', 'user', 'isMember'));
    }

    /**
     * AJAX: Check the reschedule fee for a given new date/time.
     */
    public function checkRescheduleFee($id, \Illuminate\Http\Request $request)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $newDate = $request->input('new_date');
        $newTime = $request->input('new_time');

        $fee = $this->calculateRescheduleFee($booking, $newDate, $newTime);

        return response()->json($fee);
    }

    /**
     * Process the reschedule: update booking date/time and charge fee if applicable.
     */
    public function processReschedule($id, \Illuminate\Http\Request $request)
    {
        $request->validate([
            'new_date' => 'required|date|after_or_equal:today',
            'new_time' => 'required|string',
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if (!in_array($booking->status, ['Akan Datang', 'Menunggu Pembayaran'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini tidak dapat dijadwal ulang.'
            ], 400);
        }

        $newDate = $request->input('new_date');
        $newTime = $request->input('new_time');

        // If rescheduling date is today, check if selected time has already passed
        if (\Carbon\Carbon::parse($newDate)->isToday()) {
            $bookingTime = \Carbon\Carbon::parse($newDate . ' ' . $newTime);
            if ($bookingTime->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waktu reschedule yang dipilih sudah terlewat untuk hari ini. Silakan pilih jam lain.'
                ], 400);
            }
        }

        // Prevent overlapping bookings for the therapist when rescheduling
        if ($booking->therapist_id) {
            $existingBookings = Booking::where('therapist_id', $booking->therapist_id)
                ->where('schedule_date', $newDate)
                ->where('id', '!=', $booking->id) // Exclude this booking itself
                ->where('status', '!=', 'Dibatalkan')
                ->get();

            $duration = (int) ($booking->duration ?? 90);
            $isOverlap = false;
            foreach ($existingBookings as $eb) {
                $ebDuration = (int) ($eb->duration ?? 90);
                if (self::areIntervalsOverlapping($newTime, $duration, $eb->schedule_time, $ebDuration)) {
                    $isOverlap = true;
                    break;
                }
            }

            if ($isOverlap) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terapis tidak tersedia pada jadwal baru tersebut karena bentrok dengan pesanan lain.'
                ], 400);
            }
        }

        $feeInfo = $this->calculateRescheduleFee($booking, $newDate, $newTime);
        $fee = $feeInfo['fee'];

        // Update booking
        $booking->schedule_date = $newDate;
        $booking->schedule_time = $newTime;
        $booking->reschedule_count = ($booking->reschedule_count ?? 0) + 1;
        $booking->reschedule_fee_charged = ($booking->reschedule_fee_charged ?? 0) + $fee;
        $booking->total_payment = $booking->total_payment + $fee;

        if ($fee > 0) {
            $booking->pay_status = 'Belum Lunas';
        }

        $booking->save();

        return response()->json([
            'success' => true,
            'message' => $fee > 0
                ? 'Jadwal berhasil diubah. Biaya reschedule Rp ' . number_format($fee, 0, ',', '.') . ' ditambahkan.'
                : 'Jadwal berhasil diubah tanpa biaya tambahan.',
            'fee_charged' => $fee
        ]);
    }

    /**
     * Helper: Calculate reschedule fee based on time threshold and membership status.
     */
    private function calculateRescheduleFee(Booking $booking, $newDate, $newTime)
    {
        $user = Auth::user();
        $isMember = $user->is_member && $user->membershipTier;

        // Calculate hours until original schedule
        $scheduleDateTime = \Carbon\Carbon::parse($booking->schedule_date . ' ' . $booking->schedule_time);
        $now = \Carbon\Carbon::now();
        $hoursUntilSchedule = $now->diffInHours($scheduleDateTime, false);

        // Default non-member reschedule fee
        $defaultNonMemberFee = 20000;

        // If reschedule is > 12 hours before schedule: FREE for everyone
        if ($hoursUntilSchedule > 12) {
            return [
                'fee' => 0,
                'is_free' => true,
                'reason' => 'Reschedule dilakukan lebih dari 12 jam sebelum jadwal. Gratis untuk semua.',
                'hours_until' => $hoursUntilSchedule,
            ];
        }

        // If reschedule is <= 12 hours (mendadak):
        if ($isMember) {
            $tier = $user->membershipTier;
            $freeLimit = $tier->free_reschedule_limit ?? 3;

            // Count total reschedules this month across ALL bookings
            $monthlyCount = Booking::where('user_id', $user->id)
                ->where('reschedule_count', '>', 0)
                ->whereYear('updated_at', $now->year)
                ->whereMonth('updated_at', $now->month)
                ->sum('reschedule_count');

            if ($monthlyCount < $freeLimit) {
                $remaining = $freeLimit - $monthlyCount - 1;
                return [
                    'fee' => 0,
                    'is_free' => true,
                    'reason' => 'Gratis (Member). Sisa kuota reschedule mendadak bulan ini: ' . max(0, $remaining) . '/' . $freeLimit,
                    'hours_until' => $hoursUntilSchedule,
                    'remaining_free' => max(0, $remaining),
                ];
            } else {
                $fee = $tier->reschedule_fee ?? $defaultNonMemberFee;
                return [
                    'fee' => $fee,
                    'is_free' => false,
                    'reason' => 'Kuota reschedule gratis bulan ini habis (' . $monthlyCount . '/' . $freeLimit . '). Biaya: Rp ' . number_format($fee, 0, ',', '.'),
                    'hours_until' => $hoursUntilSchedule,
                ];
            }
        } else {
            // Non-member: always charged for last-minute reschedule
            return [
                'fee' => $defaultNonMemberFee,
                'is_free' => false,
                'reason' => 'Reschedule mendadak (kurang dari 12 jam). Biaya: Rp ' . number_format($defaultNonMemberFee, 0, ',', '.'),
                'hours_until' => $hoursUntilSchedule,
            ];
        }
    }

    /**
     * Initialize Midtrans configurations.
     */
    private function initMidtrans()
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Generate Midtrans Snap Token for a booking.
     */
    public function getSnapToken($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // Check if expired
        if ($booking->status === 'Menunggu Pembayaran' && $booking->created_at->addMinutes(10)->isPast()) {
            $booking->update([
                'status' => 'Dibatalkan',
                'pay_status' => 'Kadaluarsa'
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Batas waktu pembayaran (10 menit) telah habis. Pesanan ini telah dibatalkan.'
            ], 400);
        }

        $this->initMidtrans();

        // Unique Midtrans Order ID incorporating time() to avoid duplicate errors in Midtrans
        $midtransOrderId = $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $booking->total_payment,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $booking->id,
                    'price' => (int) $booking->total_payment,
                    'quantity' => 1,
                    'name' => substr($booking->service_name, 0, 50),
                ]
            ],
            'expiry' => [
                'duration' => 10,
                'unit' => 'minute'
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Midtrans Notification Webhook Callback.
     */
    public function midtransCallback(\Illuminate\Http\Request $request)
    {
        $this->initMidtrans();

        try {
            $notif = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Parse root booking ID (e.g. TRX-2605-123-1 -> TRX-2605-123)
        $parts = explode('-', $orderId);
        if (count($parts) >= 3) {
            $bookingId = $parts[0] . '-' . $parts[1] . '-' . $parts[2];
        } else {
            $bookingId = $orderId;
        }

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $booking->update(['pay_status' => 'Challenge']);
                } else {
                    $booking->update([
                        'pay_status' => 'Lunas',
                        'status' => 'Akan Datang'
                    ]);
                }
            }
        } else if ($transaction == 'settlement') {
            $booking->update([
                'pay_status' => 'Lunas',
                'status' => 'Akan Datang'
            ]);
        } else if ($transaction == 'pending') {
            $booking->update(['pay_status' => 'Belum Lunas']);
        } else if ($transaction == 'deny') {
            $booking->update(['pay_status' => 'Gagal']);
        } else if ($transaction == 'expire') {
            $booking->update([
                'pay_status' => 'Kadaluarsa',
                'status' => 'Dibatalkan'
            ]);
        } else if ($transaction == 'cancel') {
            $booking->update([
                'pay_status' => 'Batal',
                'status' => 'Dibatalkan'
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Helper: Check if two booking intervals overlap on the same day.
     */
    public static function areIntervalsOverlapping($start1, $duration1, $start2, $duration2)
    {
        $timeToMinutes = function($timeStr) {
            $parts = explode(':', $timeStr);
            $hours = (int) $parts[0];
            $minutes = isset($parts[1]) ? (int) $parts[1] : 0;
            return $hours * 60 + $minutes;
        };

        $s1 = $timeToMinutes($start1);
        $e1 = $s1 + (int) $duration1;

        $s2 = $timeToMinutes($start2);
        $e2 = $s2 + (int) $duration2;

        return $s1 < $e2 && $s2 < $e1;
    }

    /**
     * AJAX: Check booking availability for a therapist on a specific date.
     */
    public function checkAvailability(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'therapist_name' => 'required|string',
            'schedule_date' => 'required|date',
            'duration' => 'nullable|integer|in:60,90,120',
        ]);

        $therapist = Therapist::where('name', $request->therapist_name)->where('status', 'Active')->first();
        if (!$therapist) {
            return response()->json([
                'success' => true,
                'booked_times' => []
            ]);
        }

        $duration = (int) $request->input('duration', 90);

        // Get all active bookings for this therapist on the schedule_date
        $bookings = Booking::where('therapist_id', $therapist->id)
            ->where('schedule_date', $request->schedule_date)
            ->where('status', '!=', 'Dibatalkan')
            ->get();

        $allTimes = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00"];
        $bookedTimes = [];

        foreach ($allTimes as $time) {
            foreach ($bookings as $b) {
                $existingDuration = (int) ($b->duration ?? 90);
                if (self::areIntervalsOverlapping($time, $duration, $b->schedule_time, $existingDuration)) {
                    $bookedTimes[] = $time;
                    break; // Overlapped, no need to check other bookings for this slot
                }
            }
        }

        return response()->json([
            'success' => true,
            'booked_times' => $bookedTimes
        ]);
    }
}
