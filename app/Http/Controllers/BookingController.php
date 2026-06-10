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
        $hasDiscountQuota = false;
        $discountAmount = 0;

        if ($isMember) {
            $weeklyLimit = (int)\App\Models\WebSetting::get('membership_weekly_limit', '3');
            $discountAmount = (int)\App\Models\WebSetting::get('membership_discount_amount', '15000');
            
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
            $endOfWeek = \Carbon\Carbon::now()->endOfWeek();
            
            $usedCount = Booking::where('user_id', $user->id)
                ->where('is_membership_discount_applied', true)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();
            
            $hasDiscountQuota = ($usedCount < $weeklyLimit);
        }

        return view('customer.booking', compact('therapists', 'services', 'isMember', 'hasDiscountQuota', 'discountAmount'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_key' => 'required|string',
            'therapist_name' => 'required|string',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required|string',
            'location_type' => 'required|string|in:home,clinic',
            'address' => 'nullable|string',
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

        $user = Auth::user();
        $isMember = (bool)$user->is_member;

        // Calculate transportation fee (free if member)
        $transportFee = 0;
        if ($request->location_type === 'home') {
            $transportFee = $isMember ? 0 : 20000;
        }

        $servicePrice = $request->location_type === 'home' ? $serviceModel->price_home : $serviceModel->price_clinic;

        // Calculate discount
        $discountAmount = 0;
        $isMembershipDiscountApplied = false;
        if ($isMember) {
            $weeklyLimit = (int)\App\Models\WebSetting::get('membership_weekly_limit', '3');
            $discountValue = (int)\App\Models\WebSetting::get('membership_discount_amount', '15000');
            
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
            $endOfWeek = \Carbon\Carbon::now()->endOfWeek();
            
            $usedCount = Booking::where('user_id', $user->id)
                ->where('is_membership_discount_applied', true)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();
            
            if ($usedCount < $weeklyLimit) {
                $discountAmount = $discountValue;
                $isMembershipDiscountApplied = true;
            }
        }

        $totalPayment = $servicePrice + $transportFee - $discountAmount;

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

        $paymentStatus = $request->input('payment_status', 'paid');
        $status = $paymentStatus === 'pending' ? 'Menunggu Pembayaran' : 'Akan Datang';
        $payStatus = $paymentStatus === 'pending' ? 'Belum Bayar' : 'Lunas';

        // Create booking in database
        Booking::create([
            'id'            => $orderId,
            'user_id'       => Auth::id(),
            'therapist_id'  => $therapist ? $therapist->id : null,
            'service_name'  => $serviceModel->name . ' (' . $serviceModel->default_duration . ')',
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'location_type' => $request->location_type,
            'address'       => $address,
            'service_price' => $servicePrice,
            'transport_price' => $transportFee,
            'discount_amount' => $discountAmount,
            'is_membership_discount_applied' => $isMembershipDiscountApplied,
            'total_payment' => $totalPayment,
            'status'        => $status,
            'pay_status'    => $payStatus,
        ]);

        $message = $paymentStatus === 'pending' 
            ? 'Pesanan Anda berhasil dibuat dan menunggu pembayaran!' 
            : 'Pesanan Anda berhasil dibuat!';

        return redirect()->route('customer.history')->with('success', $message);
    }

    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.history', compact('bookings'));
    }

    public function historyDetail($id)
    {
        $booking = Booking::with('therapist')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

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
}
