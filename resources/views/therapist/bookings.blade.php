@extends('layouts.therapist')
@section('title', 'List Booking - Nusa Terapi Center')
@section('page_title', 'List Booking Masuk')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-slate-800 text-base">Semua Booking Saya</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                    <th class="px-5 py-3">No. Booking</th>
                    <th class="px-5 py-3">Pasien</th>
                    <th class="px-5 py-3">Layanan</th>
                    <th class="px-5 py-3">Jadwal</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $booking->id }}</td>
                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $booking->user->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $booking->service_name }}</td>
                    <td class="px-5 py-3 text-gray-700">
                        <span class="font-medium">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}</span>
                        <span class="text-gray-400 text-xs block">{{ $booking->schedule_time }} WIB</span>
                    </td>
                    <td class="px-5 py-3 text-gray-600">
                        {{ $booking->location_type === 'home' ? '🏠 Home Service' : '🏥 Klinik' }}
                    </td>
                    <td class="px-5 py-3">
                        @if($booking->status === 'Akan Datang')
                            <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">Akan Datang</span>
                        @elseif($booking->status === 'Selesai')
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Selesai</span>
                        @elseif($booking->status === 'Dibatalkan')
                            <span class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-semibold rounded-full border border-rose-200">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($booking->status === 'Akan Datang')
                        <form action="{{ route('therapist.complete', $booking->id) }}" method="POST"
                              onsubmit="return confirm('Tandai booking ini selesai?')">
                            @csrf
                            <button type="submit"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                ✓ Selesai
                            </button>
                        </form>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-400">
                        <div class="text-3xl mb-2">📋</div>
                        Belum ada booking masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
