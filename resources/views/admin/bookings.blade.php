@extends('layouts.admin')

@section('title', 'Manajemen Booking - Nusa Terapi Center')
@section('page_title', 'Manajemen Booking')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Data Booking</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="pb-3 px-4 font-medium w-16">No</th>
                        <th class="pb-3 px-4 font-medium">No. Invoice</th>
                        <th class="pb-3 px-4 font-medium">Pasien</th>
                        <th class="pb-3 px-4 font-medium">Terapis</th>
                        <th class="pb-3 px-4 font-medium">Jadwal</th>
                        <th class="pb-3 px-4 font-medium">Status</th>
                        <th class="pb-3 px-4 font-medium w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($bookings as $index => $booking)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 font-mono font-medium text-slate-500">{{ $booking->id }}</td>
                            <td class="py-4 px-4 font-semibold text-slate-900">{{ $booking->user->name }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $booking->therapist ? $booking->therapist->name : 'Belum Ditentukan' }}</td>
                            <td class="py-4 px-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}, {{ $booking->schedule_time }}
                            </td>
                            <td class="py-4 px-4">
                                @if($booking->status == 'Akan Datang')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Dikonfirmasi</span>
                                @elseif($booking->status == 'Selesai')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Selesai</span>
                                @elseif($booking->status == 'Dibatalkan')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full border border-red-200">Dibatalkan</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full border border-slate-200">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <a href="{{ route('admin.bookings.detail', $booking->id) }}" class="border border-gray-300 px-4 py-1 rounded-md text-gray-600 hover:bg-gray-50 text-xs font-medium transition shadow-sm inline-block">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
