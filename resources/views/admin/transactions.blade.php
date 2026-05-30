@extends('layouts.admin')

@section('title', 'Data Transaksi - Nusa Terapi Center')
@section('page_title', 'Data Transaksi')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Riwayat Pembayaran & Transaksi</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="pb-3 px-4 font-medium w-16">No</th>
                        <th class="pb-3 px-4 font-medium">No. Invoice</th>
                        <th class="pb-3 px-4 font-medium">Pasien</th>
                        <th class="pb-3 px-4 font-medium">Layanan</th>
                        <th class="pb-3 px-4 font-medium">Total</th>
                        <th class="pb-3 px-4 font-medium">Status Bayar</th>
                        <th class="pb-3 px-4 font-medium w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($bookings as $index => $booking)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 font-mono font-medium text-slate-500">{{ $booking->id }}</td>
                            <td class="py-4 px-4 font-semibold text-slate-900">{{ $booking->user->name }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $booking->service_name }}</td>
                            <td class="py-4 px-4 font-medium text-slate-900">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</td>
                            <td class="py-4 px-4">
                                @if($booking->pay_status == 'Lunas')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Lunas</span>
                                @elseif($booking->pay_status == 'Belum Bayar')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">Belum Bayar</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-100 text-rose-700 text-xs font-semibold rounded-full border border-rose-200">{{ $booking->pay_status }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <a href="{{ route('admin.transactions.detail', $booking->id) }}" class="border border-gray-300 px-4 py-1 rounded-md text-gray-600 hover:bg-gray-50 text-xs font-medium transition shadow-sm inline-block">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-400">Tidak ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
