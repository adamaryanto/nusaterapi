<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Therapist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TherapistController extends Controller
{
    private function getTherapist()
    {
        return Therapist::where('user_id', Auth::id())->firstOrFail();
    }

    public function dashboard()
    {
        $therapist = $this->getTherapist();
        $today = Carbon::today()->toDateString();

        $bookingHariIni = Booking::where('therapist_id', $therapist->id)
            ->where('schedule_date', $today)->count();

        $bookingPending = Booking::where('therapist_id', $therapist->id)
            ->whereIn('status', ['Akan Datang', 'Dalam Perjalanan', 'Sampai Tujuan'])->count();

        $bookingSelesai = Booking::where('therapist_id', $therapist->id)
            ->where('status', 'Selesai')->count();

        $pendapatanHarian = Booking::where('therapist_id', $therapist->id)
            ->where('schedule_date', $today)
            ->where('status', 'Selesai')
            ->sum('total_payment');

        // Weekly chart data (last 7 days)
        $weeklyData = [];
        $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->toDateString();
            $weeklyData[] = Booking::where('therapist_id', $therapist->id)
                ->where('schedule_date', $date)
                ->where('status', 'Selesai')
                ->sum('total_payment');
        }

        $pendapatanBulanIni = Booking::where('therapist_id', $therapist->id)
            ->whereYear('schedule_date', Carbon::now()->year)
            ->whereMonth('schedule_date', Carbon::now()->month)
            ->where('status', 'Selesai')
            ->sum('total_payment');

        $pendapatanMingguIni = array_sum($weeklyData);

        return view('therapist.dashboard', compact(
            'therapist', 'bookingHariIni', 'bookingPending',
            'bookingSelesai', 'pendapatanHarian',
            'weeklyData', 'weeklyLabels', 'pendapatanBulanIni', 'pendapatanMingguIni'
        ));
    }

    public function bookings(Request $request)
    {
        $therapist = $this->getTherapist();
        
        $filter = $request->query('filter', 'all');
        $date = $request->query('date');

        if (!in_array($filter, ['all', 'today', 'week', 'month', 'date'])) {
            $filter = 'all';
        }

        $query = Booking::with('user')
            ->where('therapist_id', $therapist->id);

        if ($filter === 'today') {
            $query->where('schedule_date', Carbon::today()->toDateString());
        } elseif ($filter === 'week') {
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateString();
            $query->whereBetween('schedule_date', [$startOfWeek, $endOfWeek]);
        } elseif ($filter === 'month') {
            $query->whereYear('schedule_date', Carbon::now()->year)
                  ->whereMonth('schedule_date', Carbon::now()->month);
        } elseif ($filter === 'date' && !empty($date)) {
            $query->where('schedule_date', $date);
        }

        $bookings = $query->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time', 'desc')
            ->get();

        return view('therapist.bookings', compact('therapist', 'bookings', 'filter', 'date'));
    }

    public function income()
    {
        $therapist = $this->getTherapist();

        $pendapatanBulanIni = Booking::where('therapist_id', $therapist->id)
            ->whereYear('schedule_date', Carbon::now()->year)
            ->whereMonth('schedule_date', Carbon::now()->month)
            ->where('status', 'Selesai')
            ->sum('total_payment');

        $totalPendapatan = Booking::where('therapist_id', $therapist->id)
            ->where('status', 'Selesai')
            ->sum('total_payment');

        $recentBookings = Booking::with('user')
            ->where('therapist_id', $therapist->id)
            ->where('status', 'Selesai')
            ->orderBy('schedule_date', 'desc')
            ->take(10)
            ->get();

        // Monthly chart (last 6 months)
        $monthlyData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('M Y');
            $monthlyData[] = Booking::where('therapist_id', $therapist->id)
                ->whereYear('schedule_date', $month->year)
                ->whereMonth('schedule_date', $month->month)
                ->where('status', 'Selesai')
                ->sum('total_payment');
        }

        return view('therapist.income', compact(
            'therapist', 'pendapatanBulanIni', 'totalPendapatan',
            'recentBookings', 'monthlyData', 'monthlyLabels'
        ));
    }

    public function reviews()
    {
        $therapist = $this->getTherapist();

        // 1. Fetch real reviews submitted by customers from database
        $dbBookings = Booking::with('user')
            ->where('therapist_id', $therapist->id)
            ->whereNotNull('rating')
            ->orderBy('updated_at', 'desc')
            ->get();

        $realReviews = [];
        foreach ($dbBookings as $booking) {
            $realReviews[] = [
                'customer_name' => $booking->user ? $booking->user->name : 'Pelanggan',
                'rating' => $booking->rating,
                'comment' => $booking->review ?? '',
                'date' => \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y'),
            ];
        }

        // 2. Default Seeded Mockup Reviews (to keep the page populated)
        $dummyReviews = [
            [
                'customer_name' => 'Siti Aminah',
                'rating' => 5,
                'comment' => 'Pijatannya sangat enak, badan jadi segar dan ringan kembali. Mantap!',
                'date' => '14 Mei 2026',
            ],
            [
                'customer_name' => 'Budi Santoso',
                'rating' => 5,
                'comment' => 'Pelayanan sangat baik dan terapis datang tepat waktu ke lokasi.',
                'date' => '12 Mei 2026',
            ],
            [
                'customer_name' => 'Rina Marlina',
                'rating' => 5,
                'comment' => 'Sangat ramah dan profesional. Sangat direkomendasikan untuk home service.',
                'date' => '10 Mei 2026',
            ],
            [
                'customer_name' => 'Ahmad Faisal',
                'rating' => 4,
                'comment' => 'Pijatannya lumayan, tapi mungkin karena macet jadi agak telat 10 menit.',
                'date' => '08 Mei 2026',
            ],
            [
                'customer_name' => 'Diana Putri',
                'rating' => 5,
                'comment' => 'Lulurnya wangi dan bikin rileks banget. Besok pasti langganan lagi.',
                'date' => '05 Mei 2026',
            ],
        ];

        // Combine them (real reviews first, then dummy reviews)
        $reviews = array_merge($realReviews, $dummyReviews);

        // 3. Dynamically calculate average rating and count ulasan from DB + dummy
        $dbRatingCount = $dbBookings->count();
        $dbRatingSum = $dbBookings->sum('rating');

        $dummyCount = 142;
        $dummySum = 4.8 * $dummyCount;

        $totalReviews = $dummyCount + $dbRatingCount;
        $averageRating = ($dummySum + $dbRatingSum) / $totalReviews;
        $averageRating = round($averageRating, 1);

        return view('therapist.reviews', compact('therapist', 'reviews', 'averageRating', 'totalReviews'));
    }

    public function startJourney($id)
    {
        $therapist = $this->getTherapist();
        $booking = Booking::where('id', $id)
            ->where('therapist_id', $therapist->id)
            ->firstOrFail();

        $booking->update(['status' => 'Dalam Perjalanan']);
        return redirect()->route('therapist.bookings')->with('success', 'Perjalanan dimulai. Hati-hati di jalan!');
    }

    public function arrive($id)
    {
        $therapist = $this->getTherapist();
        $booking = Booking::where('id', $id)
            ->where('therapist_id', $therapist->id)
            ->firstOrFail();

        $booking->update(['status' => 'Sampai Tujuan']);
        return redirect()->route('therapist.bookings')->with('success', 'Status diperbarui: Anda telah sampai di tujuan.');
    }

    public function completeBooking($id)
    {
        $therapist = $this->getTherapist();
        $booking = Booking::where('id', $id)
            ->where('therapist_id', $therapist->id)
            ->firstOrFail();

        $booking->update(['status' => 'Selesai']);
        return redirect()->route('therapist.bookings')->with('success', 'Booking ditandai selesai.');
    }

    public function acceptBooking($id)
    {
        $therapist = $this->getTherapist();
        $booking = Booking::where('id', $id)
            ->where('therapist_id', $therapist->id)
            ->firstOrFail();

        $booking->update(['status' => 'Akan Datang']);
        return redirect()->route('therapist.bookings')->with('success', 'Booking berhasil diterima.');
    }

    public function rejectBooking($id)
    {
        $therapist = $this->getTherapist();
        $booking = Booking::where('id', $id)
            ->where('therapist_id', $therapist->id)
            ->firstOrFail();

        $booking->update(['status' => 'Dibatalkan']);
        return redirect()->route('therapist.bookings')->with('success', 'Booking berhasil ditolak.');
    }

    public function profile()
    {
        $therapist = Auth::user();
        return view('therapist.profile', compact('therapist'));
    }

    public function profileUpdate(Request $request)
    {
        $therapist = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $therapist->id,
            'phone' => 'required|string|max:20',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $avatarPath = $therapist->avatar_path;
        if ($request->hasFile('avatar')) {
            if ($avatarPath && file_exists(public_path($avatarPath))) {
                @unlink(public_path($avatarPath));
            }
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/avatars'), $filename);
            $avatarPath = 'uploads/avatars/' . $filename;
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'avatar_path' => $avatarPath,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $therapist->update($data);

        return redirect()->route('therapist.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
