@extends('layouts.app')

@section('title', 'Riwayat Pesanan Anda - Nusa Terapi Center')

@section('content')
    @php
        $allServices = \App\Models\Service::all();
        $serviceSlugMap = [];
        foreach ($allServices as $srv) {
            $key = $srv->name . ' (' . $srv->default_duration . ')';
            $serviceSlugMap[$key] = $srv->slug;
        }
    @endphp
    <div class="max-w-4xl mx-auto py-12 px-6">
        
        <!-- Page Title -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-6">Riwayat Pesanan Anda</h2>
        
        <!-- Navigation Tabs -->
        <div class="flex border-b border-gray-100 text-sm font-semibold text-gray-400 overflow-x-auto space-x-8 mb-8 scrollbar-none">
            <button onclick="changeTab('Semua')" id="tab-Semua" class="py-3 border-b-2 border-slate-900 text-slate-900 focus:outline-none whitespace-nowrap transition-all font-bold">Semua</button>
            <button onclick="changeTab('Menunggu Pembayaran')" id="tab-Menunggu_Pembayaran" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Menunggu Pembayaran</button>
            <button onclick="changeTab('Akan Datang')" id="tab-Akan_Datang" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Dikonfirmasi</button>
            <button onclick="changeTab('Selesai')" id="tab-Selesai" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Selesai</button>
            <button onclick="changeTab('Dibatalkan')" id="tab-Dibatalkan" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Dibatalkan</button>
        </div>

        <!-- Orders List Container -->
        <div id="orders-list" class="space-y-6">
            @forelse($bookings as $booking)
                @php
                    // Dynamic Badge Style & Display Text
                    $badgeClass = "";
                    $statusText = $booking->status;
                    if ($booking->status === "Akan Datang") {
                        $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
                        $statusText = "Dikonfirmasi";
                    } elseif ($booking->status === "Dalam Perjalanan") {
                        $badgeClass = "bg-blue-50 text-blue-700 border-blue-200";
                        $statusText = "Terapis Dalam Perjalanan";
                    } elseif ($booking->status === "Sampai Tujuan") {
                        $badgeClass = "bg-indigo-50 text-indigo-700 border-indigo-200";
                        $statusText = "Terapis Tiba";
                    } elseif ($booking->status === "Selesai") {
                        $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
                    } elseif ($booking->status === "Menunggu Pembayaran") {
                        $badgeClass = "bg-amber-50 text-amber-700 border-amber-200";
                    } else {
                        $badgeClass = "bg-rose-50 text-rose-700 border-rose-200";
                    }

                    // Dynamic Icons
                    $icon = "💆";
                    if (str_contains(strtolower($booking->service_name), 'refleksi')) $icon = "🦶";
                    elseif (str_contains(strtolower($booking->service_name), 'bekam')) $icon = "🍯";
                    elseif (str_contains(strtolower($booking->service_name), 'lulur')) $icon = "🌸";

                    // Dynamic Action Buttons
                    $actionText = "Lihat Detail";
                    $actionClass = "bg-[#0f172a] text-white hover:bg-slate-800";
                    if ($booking->status === 'Selesai') {
                        $actionText = "Beri Ulasan";
                        $actionClass = "bg-white border border-gray-300 text-gray-700 hover:bg-gray-50";
                    } elseif ($booking->status === 'Dibatalkan') {
                        $actionText = "Pesan Lagi";
                        $actionClass = "bg-white border border-gray-300 text-gray-700 hover:bg-gray-50";
                    }
                @endphp

                <div class="booking-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 hover:shadow-md transition duration-300" data-status="{{ $booking->status }}">
                    <!-- Top Header Section of Card -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-5">
                        <div class="flex items-center space-x-3 text-xs font-semibold">
                            <span class="px-2.5 py-1 rounded-md border text-[10px] uppercase tracking-wider font-bold {{ $badgeClass }}">{{ $statusText }}</span>
                            <span class="text-gray-400">{{ $booking->id }}</span>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            @if($booking->status === 'Selesai')
                                @if(is_null($booking->rating))
                                    <a href="{{ route('customer.review', $booking->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 inline-block">
                                        Beri Ulasan
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 font-bold px-2 py-2 block">Sudah Diulas</span>
                                @endif
                                
                                @php
                                    $bookingSlug = $serviceSlugMap[$booking->service_name] ?? null;
                                @endphp
                                <a href="{{ route('customer.booking', $bookingSlug ? ['type' => $bookingSlug] : []) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-[#0f172a] text-white hover:bg-slate-800 inline-block">
                                    Pesan Lagi
                                </a>
                            @elseif($booking->status === 'Dibatalkan')
                                @php
                                    $bookingSlug = $serviceSlugMap[$booking->service_name] ?? null;
                                @endphp
                                <a href="{{ route('customer.booking', $bookingSlug ? ['type' => $bookingSlug] : []) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 inline-block">
                                    Pesan Lagi
                                </a>
                                <a href="{{ route('customer.history.detail', $booking->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-[#0f172a] text-white hover:bg-slate-800 inline-block">
                                    Lihat Detail
                                </a>
                            @elseif($booking->status === 'Menunggu Pembayaran')
                                <button onclick="openPaymentModalForBooking('{{ $booking->id }}', 'Rp {{ number_format($booking->total_payment, 0, ',', '.') }}')" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-[#0f172a] text-white hover:bg-slate-800 inline-block focus:outline-none">
                                    Bayar Sekarang
                                </button>
                                <a href="{{ route('customer.history.detail', $booking->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 inline-block">
                                    Lihat Detail
                                </a>
                            @elseif($actionText === "Lihat Detail")
                                <a href="{{ route('customer.history.detail', $booking->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm {{ $actionClass }} inline-block">
                                    {{ $actionText }}
                                </a>
                            @else
                                <button onclick="handleAction('{{ $booking->id }}', '{{ $actionText }}')" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm {{ $actionClass }}">
                                    {{ $actionText }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body Content -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <!-- Square Placeholder Image -->
                            <div class="w-16 h-16 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center text-3xl flex-shrink-0 shadow-inner">
                                {{ $icon }}
                            </div>
                            <div class="space-y-1">
                                <h4 class="font-bold text-slate-800 text-sm md:text-base">
                                    <a href="{{ route('customer.history.detail', $booking->id) }}" class="hover:underline hover:text-slate-600 transition">{{ $booking->service_name }}</a>
                                </h4>
                                <p class="text-xs text-gray-500 font-medium">
                                    Terapis: <span class="text-slate-700 font-semibold">{{ $booking->therapist ? $booking->therapist->name : 'Tidak Memilih Terapis' }}</span> 
                                    <span class="mx-1 text-gray-300">|</span> 
                                    Jadwal: <span class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }} • {{ $booking->schedule_time }} WIB</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-left sm:text-right w-full sm:w-auto">
                            <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">Total Belanja</span>
                            <span class="text-slate-900 font-extrabold text-base md:text-lg">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-12 text-center text-gray-500">
                    <span class="text-4xl block mb-3">📦</span>
                    <p class="font-medium text-slate-600">Belum ada pesanan</p>
                    <p class="text-xs text-gray-400 mt-1">Anda tidak memiliki riwayat pesanan.</p>
                </div>
            @endforelse

            <!-- Client Filter Empty State -->
            <div id="empty-state" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-12 text-center text-gray-500 hidden">
                <span class="text-4xl block mb-3">📦</span>
                <p class="font-medium text-slate-600">Belum ada pesanan</p>
                <p class="text-xs text-gray-400 mt-1">Anda tidak memiliki riwayat pesanan untuk status ini.</p>
            </div>
        </div>

    </div>

    <!-- Midtrans Mockup Modal -->
    <div id="midtrans-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] overflow-hidden border border-slate-100 flex flex-col max-h-[92vh]">
            
            <!-- Midtrans Header -->
            <div class="px-6 py-4.5 border-b border-slate-150 bg-white flex justify-between items-center relative">
                <div class="flex items-center space-x-2">
                    <span class="text-slate-400 text-xs">🔒</span>
                    <span class="text-xs font-extrabold text-slate-700 tracking-tight">Pembayaran Secure • Nusa Terapi</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center space-x-1 bg-slate-100 px-2.5 py-1 rounded-full text-slate-700 text-[10px] font-mono font-bold">
                        <span id="midtrans-timer">14:59</span>
                        <span class="text-[9px]">⏱️</span>
                    </div>
                    <button type="button" onclick="handleMidtransClose()" class="text-slate-400 hover:text-slate-600 transition text-lg font-bold focus:outline-none select-none">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Transaction Info -->
            <div class="p-6 bg-[#111e35] text-white border-b border-slate-800">
                <div class="flex justify-between items-center">
                    <div class="space-y-1">
                        <p class="text-[9px] uppercase font-bold tracking-wider text-slate-400">Order ID</p>
                        <p class="text-xs font-mono font-bold tracking-wider text-slate-200 bg-slate-800/40 px-2.5 py-0.5 rounded inline-block" id="midtrans-order-id">TRX-2605-001</p>
                    </div>
                    <div class="text-right space-y-0.5">
                        <p class="text-[9px] uppercase font-bold tracking-wider text-slate-400">Total Pembayaran</p>
                        <p class="text-xl font-black text-emerald-400 tracking-tight" id="midtrans-total-amount">Rp 170.000</p>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-6 bg-slate-50/50" id="midtrans-body">
                
                <!-- STEP 1: Select Payment Method -->
                <div id="midtrans-step-select" class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Cara Pembayaran</h4>
                    
                    <!-- Bank Transfer Option Group -->
                    <div class="space-y-2.5">
                        <button type="button" onclick="selectMidtransMethod('bca')" class="w-full flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50/10 hover:shadow-sm transition duration-205 text-left group">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-8 bg-[#005a9c] rounded-lg flex items-center justify-center text-[10px] font-black text-white tracking-widest shadow-sm">BCA</div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900 transition">BCA Virtual Account</p>
                                    <p class="text-[10px] text-slate-500 leading-tight">Bayar via m-BCA, KlikBCA, atau ATM BCA</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-350 group-hover:text-blue-500 group-hover:translate-x-0.5 transition duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>

                        <button type="button" onclick="selectMidtransMethod('mandiri')" class="w-full flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50/10 hover:shadow-sm transition duration-205 text-left group">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-8 bg-[#1a3f68] rounded-lg flex items-center justify-center text-[9px] font-black text-white tracking-tight shadow-sm relative overflow-hidden">
                                    MANDIRI
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-[#ffb81c]"></div>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900 transition">Mandiri Virtual Account</p>
                                    <p class="text-[10px] text-slate-500 leading-tight">Bayar via Livin' by Mandiri atau ATM Mandiri</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-355 group-hover:text-blue-500 group-hover:translate-x-0.5 transition duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                        
                        <button type="button" onclick="selectMidtransMethod('bni')" class="w-full flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50/10 hover:shadow-sm transition duration-205 text-left group">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-8 bg-[#00667e] rounded-lg flex items-center justify-center text-[10px] font-black text-white tracking-tighter shadow-sm relative overflow-hidden">
                                    BNI
                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-[#f15a24] rounded-tl-full"></div>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900 transition">BNI Virtual Account</p>
                                    <p class="text-[10px] text-slate-500 leading-tight">Bayar via BNI Mobile Banking atau ATM BNI</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-355 group-hover:text-blue-500 group-hover:translate-x-0.5 transition duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                    <!-- E-Wallet Option Group -->
                    <div class="space-y-2.5 pt-2">
                        <button type="button" onclick="selectMidtransMethod('gopay')" class="w-full flex items-center justify-between p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:bg-blue-50/10 hover:shadow-sm transition duration-205 text-left group">
                            <div class="flex items-center space-x-3.5">
                                <div class="w-12 h-8 bg-[#00a2e9] rounded-lg flex items-center justify-center text-[10px] font-black text-white tracking-tight shadow-sm">
                                    GoPay
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900 transition">GoPay / QRIS</p>
                                    <p class="text-[10px] text-slate-500 leading-tight">Bayar instan dengan QR Code atau Aplikasi GoPay</p>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-slate-355 group-hover:text-blue-500 group-hover:translate-x-0.5 transition duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Virtual Account Details -->
                <div id="midtrans-step-bca" class="hidden space-y-4">
                    <button type="button" onclick="backToMethods()" class="text-xs font-bold text-blue-600 hover:text-blue-805 flex items-center space-x-1 transition">
                        <span>←</span> <span>Kembali ke Metode Pembayaran</span>
                    </button>
                    
                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-5 text-center space-y-3 shadow-inner">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider" id="midtrans-va-title">Nomor Virtual Account</p>
                        <div class="flex items-center justify-center space-x-3 bg-white border border-slate-200 rounded-lg py-2.5 px-4 max-w-xs mx-auto">
                            <span class="text-lg font-mono font-extrabold text-slate-900 tracking-wider select-all" id="midtrans-va-number">88012081234567890</span>
                            <button type="button" onclick="copyVANumber()" class="text-[10px] font-bold text-blue-600 border border-blue-200 px-2.5 py-1 rounded bg-white hover:bg-blue-50 transition active:scale-95">Salin</button>
                        </div>
                        <p class="text-[10px] text-slate-400">Gunakan nomor di atas untuk melakukan transfer Virtual Account.</p>
                    </div>

                    <div class="space-y-3 text-xs text-slate-600 leading-relaxed bg-white border border-slate-100 p-4 rounded-xl">
                        <p class="font-extrabold text-slate-800">Petunjuk Transfer:</p>
                        <ol class="list-decimal pl-4 space-y-1.5 text-slate-650">
                            <li>Buka aplikasi Mobile Banking Anda atau kunjungi ATM terdekat.</li>
                            <li>Pilih menu <strong>Transfer</strong> &gt; <strong>Virtual Account</strong>.</li>
                            <li>Masukkan Nomor Virtual Account di atas.</li>
                            <li>Konfirmasi jumlah pembayaran dan selesaikan transaksi.</li>
                        </ol>
                    </div>
                    
                    <div class="pt-2">
                        <button type="button" onclick="simulatePaymentSuccess()" class="w-full py-3 bg-blue-600 text-white rounded-xl text-xs font-extrabold hover:bg-blue-700 transition shadow-md shadow-blue-200">
                            Simulasikan Pembayaran Berhasil
                        </button>
                    </div>
                </div>

                <!-- STEP 2b: GoPay Details -->
                <div id="midtrans-step-gopay" class="hidden space-y-4 text-center">
                    <button type="button" onclick="backToMethods()" class="text-xs font-bold text-blue-600 hover:text-blue-805 flex items-center space-x-1 justify-start text-left w-full transition">
                        <span>←</span> <span>Kembali ke Metode Pembayaran</span>
                    </button>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Scan QR Code dengan GoPay / e-Wallet Lain</p>
                    
                    <!-- QRIS simulated code box -->
                    <div class="mx-auto w-48 h-48 bg-white border border-slate-200 rounded-2xl flex flex-col items-center justify-center p-4 shadow-sm relative">
                        <div class="w-full flex justify-between items-center text-[7px] font-black text-slate-700 tracking-widest border-b border-slate-100 pb-1 mb-2">
                            <span>QRIS</span>
                            <span class="text-blue-600">GOPAY</span>
                        </div>
                        <!-- Mock QR Code Design using neat Grid and SVG elements -->
                        <div class="w-28 h-28 bg-[#1e293b] rounded flex flex-wrap p-1 gap-1 justify-center items-center relative shadow-inner">
                            <div class="w-8 h-8 bg-white rounded flex items-center justify-center"><div class="w-4 h-4 bg-[#1e293b] rounded-sm"></div></div>
                            <div class="w-8 h-8 bg-white rounded flex items-center justify-center"><div class="w-4 h-4 bg-[#1e293b] rounded-sm"></div></div>
                            <div class="w-8 h-8 bg-white rounded flex items-center justify-center"><div class="w-4 h-4 bg-[#1e293b] rounded-sm"></div></div>
                            <div class="w-8 h-8 bg-white rounded"></div>
                            <div class="w-8 h-8 bg-white rounded flex items-center justify-center"><div class="w-3.5 h-3.5 bg-[#1e293b] rounded-sm"></div></div>
                            <div class="w-8 h-8 bg-white rounded"></div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 px-4">Arahkan kamera e-Wallet Anda ke kode QRIS di atas untuk melakukan pembayaran instan.</p>
                    
                    <div class="pt-2">
                        <button type="button" onclick="simulatePaymentSuccess()" class="w-full py-3 bg-[#00a2e9] text-white rounded-xl text-xs font-extrabold hover:bg-[#008ccb] transition shadow-md shadow-cyan-100">
                            Simulasikan Bayar via Aplikasi
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Loading/Processing -->
                <div id="midtrans-step-loading" class="hidden py-10 text-center space-y-5">
                    <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    <div>
                        <p class="text-sm font-extrabold text-slate-800" id="loading-title">Memverifikasi Pembayaran...</p>
                        <p class="text-xs text-slate-400 mt-1" id="loading-subtitle">Menghubungkan ke bank partner...</p>
                    </div>
                </div>

                <!-- STEP 4: Success Message -->
                <div id="midtrans-step-success" class="hidden py-10 text-center space-y-5">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto shadow-md">
                        ✓
                    </div>
                    <div>
                        <p class="text-base font-extrabold text-emerald-600">Pembayaran Berhasil!</p>
                        <p class="text-xs text-slate-500 mt-1">Transaksi Anda terverifikasi di Nusa Terapi Center.</p>
                    </div>
                </div>
            </div>

            <!-- Footer Secure Message -->
            <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 text-center flex justify-center items-center space-x-1.5 text-slate-400 text-[9px] font-bold">
                <span>🔒 Pembayaran aman & terenkripsi</span>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let currentActiveTab = "Semua";

        function changeTab(tabName) {
            currentActiveTab = tabName;
            
            // Normalize tab element IDs for element lookup
            const targetId = "tab-" + tabName.replace(" ", "_");
            
            // Reset all tab styling
            const tabs = ["Semua", "Menunggu_Pembayaran", "Akan_Datang", "Selesai", "Dibatalkan"];
            tabs.forEach(t => {
                const el = document.getElementById("tab-" + t);
                if (el) {
                    el.className = "py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all";
                }
            });

            // Activate current tab styling
            const activeEl = document.getElementById(targetId);
            if (activeEl) {
                activeEl.className = "py-3 border-b-2 border-slate-900 text-slate-900 font-bold focus:outline-none whitespace-nowrap transition-all";
            }

            // Filter booking cards client side
            const cards = document.querySelectorAll('.booking-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                let match = false;
                if (tabName === "Semua") {
                    match = true;
                } else if (tabName === "Akan Datang") {
                    match = (cardStatus === "Akan Datang" || cardStatus === "Dalam Perjalanan" || cardStatus === "Sampai Tujuan");
                } else {
                    match = (cardStatus.toLowerCase() === tabName.toLowerCase());
                }

                if (match) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0 && cards.length > 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function handleAction(orderId, actionType) {
            if (actionType === "Beri Ulasan") {
                const ulasan = prompt(`Beri penilaian untuk pesanan ${orderId} (1-5):`);
                if (ulasan) {
                    alert(`Terima kasih atas ulasan bintang ${ulasan} Anda!`);
                }
            } else if (actionType === "Pesan Lagi") {
                window.location.href = "{{ route('customer.booking') }}";
            }
        }

        // Midtrans Simulation on History Page
        let activePayBookingId = null;
        let timerInterval = null;

        function openPaymentModalForBooking(orderId, totalAmount) {
            activePayBookingId = orderId;
            
            // Set values in modal
            document.getElementById('midtrans-order-id').innerText = orderId;
            document.getElementById('midtrans-total-amount').innerText = totalAmount;
            
            // Show modal
            document.getElementById('midtrans-modal').classList.remove('hidden');
            
            // Reset modal steps
            document.getElementById('midtrans-step-select').classList.remove('hidden');
            document.getElementById('midtrans-step-bca').classList.add('hidden');
            document.getElementById('midtrans-step-gopay').classList.add('hidden');
            document.getElementById('midtrans-step-loading').classList.add('hidden');
            document.getElementById('midtrans-step-success').classList.add('hidden');
            
            // Start timer
            startMidtransTimer();
        }

        function closeMidtransModal() {
            document.getElementById('midtrans-modal').classList.add('hidden');
            clearInterval(timerInterval);
        }

        function handleMidtransClose() {
            closeMidtransModal();
        }

        function startMidtransTimer() {
            clearInterval(timerInterval);
            let duration = 15 * 60 - 1; // 14 mins 59 secs
            const timerEl = document.getElementById('midtrans-timer');
            timerInterval = setInterval(() => {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;
                timerEl.innerText = `${minutes}:${seconds}`;
                if (--duration < 0) {
                    clearInterval(timerInterval);
                    closeMidtransModal();
                }
            }, 1000);
        }

        function selectMidtransMethod(method) {
            const bcaStep = document.getElementById('midtrans-step-bca');
            const gopayStep = document.getElementById('midtrans-step-gopay');
            
            document.getElementById('midtrans-step-select').classList.add('hidden');
            
            if (method === 'gopay') {
                gopayStep.classList.remove('hidden');
            } else {
                const vaTitles = {
                    bca: { title: 'Nomor BCA Virtual Account', number: '88012081234567890' },
                    mandiri: { title: 'Nomor Mandiri Virtual Account', number: '89012098765432100' },
                    bni: { title: 'Nomor BNI Virtual Account', number: '88089055667788900' }
                };
                
                const data = vaTitles[method];
                document.getElementById('midtrans-va-title').innerText = data.title;
                document.getElementById('midtrans-va-number').innerText = data.number;
                bcaStep.classList.remove('hidden');
            }
        }

        function backToMethods() {
            document.getElementById('midtrans-step-bca').classList.add('hidden');
            document.getElementById('midtrans-step-gopay').classList.add('hidden');
            document.getElementById('midtrans-step-select').classList.remove('hidden');
        }

        function copyVANumber() {
            const vaNum = document.getElementById('midtrans-va-number').innerText;
            navigator.clipboard.writeText(vaNum).then(() => {
                const copyBtn = document.querySelector('#midtrans-step-bca button[onclick*="copyVANumber"]');
                if (copyBtn) {
                    const originalText = copyBtn.innerText;
                    copyBtn.innerText = "Tersalin!";
                    copyBtn.disabled = true;
                    setTimeout(() => {
                        copyBtn.innerText = originalText;
                        copyBtn.disabled = false;
                    }, 1500);
                }
            });
        }

        function simulatePaymentSuccess() {
            document.getElementById('midtrans-step-bca').classList.add('hidden');
            document.getElementById('midtrans-step-gopay').classList.add('hidden');
            
            const stepLoading = document.getElementById('midtrans-step-loading');
            stepLoading.classList.remove('hidden');
            
            const loadTitle = document.getElementById('loading-title');
            const loadSubtitle = document.getElementById('loading-subtitle');
            
            loadTitle.innerText = "Memverifikasi Pembayaran...";
            loadSubtitle.innerText = "Menghubungkan ke bank partner...";
            
            setTimeout(() => {
                loadTitle.innerText = "Memproses Transaksi...";
                loadSubtitle.innerText = "Menyelesaikan pesanan Anda di Nusa Terapi Center...";
                
                setTimeout(() => {
                    stepLoading.classList.add('hidden');
                    
                    const stepSuccess = document.getElementById('midtrans-step-success');
                    stepSuccess.classList.remove('hidden');
                    
                    setTimeout(() => {
                        // Call payBooking AJAX POST
                        fetch(`/riwayat-pesanan/detail/${activePayBookingId}/pay`, {
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
                                window.location.reload();
                            } else {
                                alert(data.message || "Gagal memproses pembayaran.");
                                closeMidtransModal();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Terjadi kesalahan koneksi.");
                            closeMidtransModal();
                        });
                    }, 1500);
                    
                }, 1200);
            }, 1000);
        }
    </script>
@endsection
