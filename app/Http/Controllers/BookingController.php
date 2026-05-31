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
        $therapists = Therapist::where('status', 'Active')->get();
        $services   = Service::active()->get();
        return view('customer.booking', compact('therapists', 'services'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_key' => 'required|string',
            'therapist_name' => 'required|string',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required|string',
            'location_type' => 'required|string|in:home,clinic',
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

        // Find therapist
        $therapist = Therapist::where('name', $request->therapist_name)->first();

        // Calculate transportation fee
        $transportFee = $request->location_type === 'home' ? 20000 : 0;
        $servicePrice = $request->location_type === 'home' ? $serviceModel->price_home : $serviceModel->price_clinic;
        $totalPayment = $servicePrice + $transportFee;

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
            'total_payment' => $totalPayment,
            'status'        => 'Pending',
            'pay_status'    => 'Lunas',
        ]);

        return redirect()->route('customer.history')->with('success', 'Pesanan Anda berhasil dibuat!');
    }

    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('schedule_date', 'desc')
            ->orderBy('schedule_time', 'desc')
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
}
