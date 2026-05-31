@extends('layouts.therapist')
@section('title', 'List Booking - Nusa Terapi Center')
@section('page_title', 'List Booking Masuk')

@section('content')
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-800 text-base md:text-lg">Daftar Booking Pelanggan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 font-semibold uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-4 w-16 text-center">No</th>
                    <th class="px-6 py-4">Nama Customer</th>
                    <th class="px-6 py-4">Layanan</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4">Jadwal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-slate-700">
                @forelse($bookings as $index => $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $booking->user->name }}</td>
                    <td class="px-6 py-4 text-gray-600 font-medium">{{ $booking->service_name }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        @if($booking->location_type === 'home')
                            <span class="font-semibold text-slate-700 block">{{ $booking->address }}</span>
                            <span class="text-xs text-gray-400 font-medium">(Home Service)</span>
                        @else
                            <span class="font-semibold text-slate-700 block">Klinik</span>
                            <span class="text-xs text-gray-400 font-medium">(Tempat Pijat)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}, {{ $booking->schedule_time }}
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->status === 'Pending')
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full border border-amber-200 shadow-sm">Pending</span>
                        @elseif($booking->status === 'Akan Datang')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 shadow-sm">Diterima</span>
                        @elseif($booking->status === 'Dibatalkan')
                            <span class="px-3 py-1 bg-rose-100 text-rose-800 text-xs font-semibold rounded-full border border-rose-200 shadow-sm">Ditolak</span>
                        @elseif($booking->status === 'Selesai')
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded-full border border-emerald-200 shadow-sm">Selesai</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full border border-gray-200 shadow-sm">{{ $booking->status }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($booking->status === 'Pending')
                        <div class="flex justify-center items-center gap-2">
                            <form action="{{ route('therapist.accept', $booking->id) }}" method="POST"
                                  onsubmit="return confirm('Terima booking ini?')">
                                @csrf
                                <button type="submit"
                                        class="bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold px-3.5 py-1.5 rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                    ✓ Terima
                                </button>
                            </form>
                            <form action="{{ route('therapist.reject', $booking->id) }}" method="POST"
                                  onsubmit="return confirm('Tolak booking ini?')">
                                @csrf
                                <button type="submit"
                                        class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-xs font-semibold px-3.5 py-1.5 rounded-lg transition inline-flex items-center gap-1 shadow-sm">
                                    ✕ Tolak
                                </button>
                            </form>
                        </div>
                        @else
                            <span class="text-gray-400 text-sm font-medium">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center text-gray-400">
                        <div class="text-4xl mb-3">📋</div>
                        <p class="font-medium">Belum ada booking masuk.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
