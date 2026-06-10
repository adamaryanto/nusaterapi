@extends('layouts.admin')

@section('title', 'Detail Booking - Nusa Terapi Center')
@section('page_title', 'Manajemen Booking > Detail')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Header Card: ID and Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h3 class="font-bold text-slate-800 text-lg flex items-center">
                <span>Detail Booking</span>
                <span class="mx-2 text-gray-300">•</span>
                <span class="text-slate-600 font-semibold" id="booking-id-title">ID : {{ $booking->id }}</span>
            </h3>
            
            <div class="flex space-x-2 text-xs font-semibold">
                @if($booking->status === 'Akan Datang' || $booking->status === 'Menunggu Pembayaran' || $booking->status === 'Dalam Perjalanan' || $booking->status === 'Sampai Tujuan')
                    <button onclick="cancelBooking()" class="flex items-center bg-rose-50 border border-rose-200 text-rose-700 px-3 py-2 rounded-lg hover:bg-rose-100 transition shadow-sm">
                        <span class="mr-1.5">❌</span> Batalkan Booking
                    </button>
                    <form id="cancel-booking-form" action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @endif
                <button onclick="window.print()" class="flex items-center bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-50 transition shadow-sm">
                    <span class="mr-1.5">🖨️</span> Print
                </button>
            </div>
        </div>

        <!-- Main Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Column: Patient, Therapist & Schedule -->
            <div class="space-y-6">
                <!-- Patient Info -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                        <h4 class="font-bold text-slate-800 text-sm">Informasi Pasien</h4>
                    </div>
                    <div class="p-6 space-y-3.5 text-sm">
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Nama Pasien</span>
                            <span class="w-2/3 text-slate-800 font-bold">{{ $booking->user->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">No HP</span>
                            <span class="w-2/3 text-slate-600 font-medium">{{ $booking->user->phone ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Alamat Terapi</span>
                            <span class="w-2/3 text-slate-600 leading-relaxed">{{ $booking->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- Therapist Info -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                        <h4 class="font-bold text-slate-800 text-sm">Informasi Terapis</h4>
                    </div>
                    <div class="p-6 space-y-3.5 text-sm">
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Nama Terapis</span>
                            <span class="w-2/3 text-slate-800 font-bold">{{ $booking->therapist ? $booking->therapist->name : 'Belum Ditentukan' }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Spesialisasi</span>
                            <span class="w-2/3 text-slate-600">{{ $booking->therapist ? $booking->therapist->specialty : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Schedule Info -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                        <h4 class="font-bold text-slate-800 text-sm">Informasi Jadwal</h4>
                    </div>
                    <div class="p-6 space-y-3.5 text-sm">
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Tanggal</span>
                            <span class="w-2/3 text-slate-800 font-semibold">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d M Y') }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Jam Mulai</span>
                            <span class="w-2/3 text-slate-600">{{ $booking->schedule_time }} WIB</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Lokasi Terapi</span>
                            <span class="w-2/3 text-slate-600">{{ $booking->location_type === 'home' ? 'Home Service (Datang ke Rumah)' : 'Klinik Terapi' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Services, Status, Payment & Notes -->
            <div class="space-y-6">
                <!-- Service Info -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                        <h4 class="font-bold text-slate-800 text-sm">Informasi Layanan</h4>
                    </div>
                    <div class="p-6 space-y-3.5 text-sm">
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Layanan Terapi</span>
                            <span class="w-2/3 text-slate-800 font-bold">{{ $booking->service_name }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Harga Layanan</span>
                            <span class="w-2/3 text-slate-800 font-semibold">Rp {{ number_format($booking->service_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex">
                            <span class="w-1/3 text-gray-400 font-medium">Biaya Transport</span>
                            <span class="w-2/3 text-slate-800 font-semibold">
                                @if($booking->transport_price == 0 && $booking->location_type === 'home')
                                    Gratis (Member)
                                @else
                                    Rp {{ number_format($booking->transport_price, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if($booking->discount_amount > 0)
                        <div class="flex text-emerald-600 font-medium">
                            <span class="w-1/3">Potongan Member</span>
                            <span class="w-2/3">-Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Status & Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
                        <h4 class="font-bold text-slate-800 text-sm">Status & Pembayaran</h4>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <span class="text-gray-400 font-medium">Total Tagihan</span>
                            <span class="text-slate-900 font-extrabold text-base">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 font-medium">Status Booking</span>
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
                        <div class="flex items-center justify-between pt-1">
                            <span class="text-gray-400 font-medium">Status Bayar</span>
                            <div>
                                @if($booking->pay_status == 'Lunas')
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">Lunas</span>
                                @elseif($booking->pay_status == 'Belum Bayar')
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-full border border-amber-200">Belum Bayar</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-full border border-rose-200">{{ $booking->pay_status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Back Button -->
        <div class="flex justify-start">
            <a href="{{ route('admin.bookings') }}" class="px-5 py-2.5 border border-gray-300 bg-white rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                ← Kembali ke Manajemen Booking
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function cancelBooking() {
            if (confirm("Apakah Anda yakin ingin membatalkan booking ini?")) {
                document.getElementById('cancel-booking-form').submit();
            }
        }
    </script>
@endsection
