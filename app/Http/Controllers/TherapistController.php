<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('therapist.reviews', compact('therapist'));
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
}
