@extends('layouts.admin')

@section('title', 'Detail Transaksi - Nusa Terapi Center')
@section('page_title', 'Data Transaksi > Detail')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- Header Card: ID and Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-slate-800 text-lg flex items-center">
                <span>Detail Transaksi</span>
                <span class="mx-2 text-gray-300">•</span>
                <span class="text-slate-600 font-semibold">ID : {{ $booking->id }}</span>
            </h3>
            
            <div class="flex space-x-2 text-xs font-semibold">
                <button onclick="window.print()" class="flex items-center bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50 transition shadow-sm">
                    <span class="mr-1.5">🖨️</span> Print
                </button>
            </div>
        </div>

        <!-- Main Card splitting Left and Right Columns with a vertical line -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">
                
                <!-- Left Column: Patient, Service & Date details -->
                <div class="p-8 space-y-6">
                    
                    <!-- Patient Info -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-blue-500">👤</span> Data Pasien
                        </h4>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Nama Pasien</span>
                                <span class="w-2/3 text-slate-800 font-bold">{{ $booking->user->name }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">No HP</span>
                                <span class="w-2/3 text-slate-600 font-medium">{{ $booking->user->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-100">

                    <!-- Service Info -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-emerald-500">💆</span> Detail Layanan
                        </h4>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Nama Layanan</span>
                                <span class="w-2/3 text-slate-800 font-bold">{{ $booking->service_name }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Terapis</span>
                                <span class="w-2/3 text-slate-600">{{ $booking->therapist ? $booking->therapist->name : 'Belum Ditentukan' }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Lokasi</span>
                                <span class="w-2/3 text-slate-600">{{ $booking->location_type === 'home' ? 'Home Service' : 'Klinik Terapi' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Payment Date Info -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-amber-500">📅</span> Tanggal Jadwal
                        </h4>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Tgl Jadwal</span>
                                <span class="w-2/3 text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d M Y') }}</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Jam Terapi</span>
                                <span class="w-2/3 text-slate-600 font-medium">{{ $booking->schedule_time }} WIB</span>
                            </div>
                            <div class="flex">
                                <span class="w-1/3 text-gray-400 font-medium">Tgl Pembuatan</span>
                                <span class="w-2/3 text-slate-600 font-medium">{{ $booking->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Money details, payment status & Notes -->
                <div class="p-8 space-y-6">

                    <!-- Payment Detail -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-yellow-600">💰</span> Detail Pembayaran
                        </h4>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400 font-medium">Harga Layanan</span>
                                <span class="text-slate-800 font-medium">Rp {{ number_format($booking->service_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400 font-medium">Biaya Transportasi</span>
                                <span class="text-slate-800 font-medium">
                                    @if($booking->transport_price == 0 && $booking->location_type === 'home')
                                        Gratis (Member)
                                    @else
                                        Rp {{ number_format($booking->transport_price, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @if($booking->discount_amount > 0)
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span>Potongan Member</span>
                                <span>-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-400 font-medium">Biaya Admin</span>
                                <span class="text-slate-800 font-medium">Rp {{ number_format($booking->admin_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400 font-medium">PPN</span>
                                <span class="text-slate-800 font-medium">Rp {{ number_format($booking->tax_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <hr class="border-gray-100 my-2">
                            
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-slate-800 font-bold">Total Pembayaran</span>
                                <span class="text-slate-900 font-extrabold text-lg">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Payment Method & Status -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-indigo-500">💳</span> Status Pembayaran & Booking
                        </h4>
                        <div class="space-y-3.5 text-sm">
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-400 font-medium">Status Bayar</span>
                                <div class="w-2/3">
                                    @if($booking->pay_status == 'Lunas')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">Lunas</span>
                                    @elseif($booking->pay_status == 'Belum Bayar')
                                        <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">Belum Bayar</span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">{{ $booking->pay_status }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="w-1/3 text-gray-400 font-medium">Status Booking</span>
                                <div class="w-2/3">
                                    @if($booking->status == 'Akan Datang')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">Dikonfirmasi</span>
                                    @elseif($booking->status == 'Dalam Perjalanan')
                                        <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-200">Dalam Perjalanan</span>
                                    @elseif($booking->status == 'Sampai Tujuan')
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-200">Sampai Tujuan</span>
                                    @elseif($booking->status == 'Selesai')
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">Selesai</span>
                                    @elseif($booking->status == 'Dibatalkan')
                                        <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">Dibatalkan</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-50 text-slate-700 text-xs font-bold rounded-full border border-slate-200">{{ $booking->status }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- Address -->
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2 mb-4">
                            <span class="text-violet-500">📍</span> Alamat Terapi
                        </h4>
                        <p class="text-slate-700 text-sm leading-relaxed font-medium">{{ $booking->address }}</p>
                    </div>

                </div>

            </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('admin.transactions') }}" class="px-5 py-2.5 border border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                ← Kembali ke Data Transaksi
            </a>
        </div>
    </div>
@endsection
