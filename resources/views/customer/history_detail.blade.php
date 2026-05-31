@extends('layouts.app')

@section('title', 'Detail Pesanan - Nusa Terapi Center')

@section('content')
    @php
        // Dynamic Badge Style
        $badgeClass = "";
        if ($booking->status === "Akan Datang") {
            $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
        } elseif ($booking->status === "Selesai") {
            $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
        } elseif ($booking->status === "Menunggu Pembayaran") {
            $badgeClass = "bg-amber-50 text-amber-700 border-amber-200";
        } else {
            $badgeClass = "bg-rose-50 text-rose-700 border-rose-200";
        }

        // Dynamic Payment Badge Style
        $payBadgeClass = "";
        if ($booking->pay_status === "Lunas") {
            $payBadgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
        } else {
            $payBadgeClass = "bg-rose-50 text-rose-700 border-rose-200";
        }
    @endphp

    <div class="max-w-4xl mx-auto py-12 px-6 space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="text-xs text-gray-400 font-medium">
            <a href="{{ route('customer.history') }}" class="hover:text-slate-700">Riwayat Pesanan</a>
            <span class="mx-1.5">/</span>
            <span class="text-slate-600 font-bold" id="breadcrumb-id">{{ $booking->id }}</span>
        </nav>

        <!-- Page Title -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900">Detail Pesanan</h2>

        <!-- Order ID & Date Info Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex flex-col sm:flex-row gap-6 md:gap-12">
                <div>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-1">Order ID</span>
                    <span class="text-slate-900 font-extrabold text-lg" id="detail-order-id">{{ $booking->id }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-1">Tanggal Pemesanan</span>
                    <span class="text-slate-700 font-semibold text-sm" id="detail-order-date">{{ $booking->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>
            <div>
                <span class="px-3.5 py-1 text-xs font-bold rounded-full border {{ $badgeClass }}" id="detail-status-badge">{{ $booking->status === 'Akan Datang' ? 'Dikonfirmasi' : $booking->status }}</span>
            </div>
        </div>

        <!-- Card: Informasi Layanan -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h3 class="font-extrabold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-4">Informasi Layanan</h3>
            <div class="space-y-4 text-xs md:text-sm font-semibold">
                
                <!-- Nama Layanan -->
                <div class="flex">
                    <span class="w-1/3 text-gray-400 font-medium">Nama Layanan</span>
                    <span class="w-2/3 text-slate-800" id="info-service-name">{{ $booking->service_name }}</span>
                </div>

                <!-- Terapis -->
                <div class="flex">
                    <span class="w-1/3 text-gray-400 font-medium">Terapis</span>
                    <span class="w-2/3 text-slate-800" id="info-therapist-name">{{ $booking->therapist ? $booking->therapist->name : 'Tidak Memilih Terapis' }}</span>
                </div>

                <!-- Jadwal Terapi -->
                <div class="flex">
                    <span class="w-1/3 text-gray-400 font-medium">Jadwal Terapi</span>
                    <span class="w-2/3 text-slate-800" id="info-schedule-date">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d F Y') }} - {{ $booking->schedule_time }} WIB</span>
                </div>

                <!-- Lokasi Terapi -->
                <div class="flex">
                    <span class="w-1/3 text-gray-400 font-medium">Lokasi Terapi</span>
                    <div class="w-2/3 space-y-1">
                        <span class="block text-slate-800 font-bold" id="info-location-type">{{ $booking->location_type === 'home' ? 'Home Service' : 'Datang ke Klinik' }}</span>
                        <p class="text-gray-500 font-medium leading-relaxed max-w-md whitespace-pre-line" id="info-location-address">{{ $booking->address }}</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Card: Rincian Pembayaran -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <!-- Header with Pay status -->
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-extrabold text-slate-800 text-sm">Rincian Pembayaran</h3>
                <span class="px-2.5 py-0.5 text-[9px] uppercase tracking-wider font-extrabold rounded border {{ $payBadgeClass }}" id="info-pay-status-badge">{{ $booking->pay_status }}</span>
            </div>
            
            <!-- Body -->
            <div class="p-6 space-y-4 text-xs md:text-sm font-semibold">
                <div class="flex justify-between text-gray-400 font-medium">
                    <span>Harga Layanan</span>
                    <span class="text-slate-800" id="info-service-price">Rp {{ number_format($booking->service_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-400 font-medium">
                    <span>Biaya Transportasi</span>
                    <span class="text-slate-800" id="info-transport-price">Rp {{ number_format($booking->transport_price, 0, ',', '.') }}</span>
                </div>
                
                <hr class="border-gray-100 my-2">
                
                <div class="flex justify-between items-center text-sm md:text-base">
                    <span class="text-slate-800 font-bold">Total Pembayaran</span>
                    <span class="text-slate-900 font-extrabold" id="info-total-payment">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Bottom Action Buttons -->
        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button onclick="contactTherapist()" class="px-6 py-3.5 bg-[#0bb583] hover:bg-[#0aa376] text-white rounded-xl text-xs md:text-sm font-bold transition shadow-sm hover:shadow focus:outline-none">
                Hubungi Terapis
            </button>
            @if($booking->status === 'Akan Datang' || $booking->status === 'Menunggu Pembayaran')
                <button onclick="cancelOrder()" id="cancel-order-btn" class="px-6 py-3.5 bg-white border border-rose-500 text-rose-500 hover:bg-rose-50 rounded-xl text-xs md:text-sm font-bold transition shadow-sm focus:outline-none">
                    Batalkan Pesanan
                </button>
            @endif
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        function contactTherapist() {
            alert("Menghubungi terapis {{ $booking->therapist ? $booking->therapist->name : 'Nusa Terapi' }} melalui WhatsApp...");
        }

        function cancelOrder() {
            if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) {
                fetch("{{ route('customer.history.cancel', $booking->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message || "Gagal membatalkan pesanan.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat membatalkan pesanan.");
                });
            }
        }
    </script>
@endsection
