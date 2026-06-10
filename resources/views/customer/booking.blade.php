@extends('layouts.app')

@section('title', 'Form Booking - Nusa Terapi Center')

@section('styles')
@endsection


@section('content')
    <form id="booking-form" action="{{ route('customer.booking') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_time" id="hidden-schedule-time" value="13:00">
        <input type="hidden" name="address" id="hidden-address" value="">
        <input type="hidden" name="payment_status" id="hidden-payment-status" value="paid">

        <div class="max-w-7xl mx-auto py-12 px-6">
            
            <!-- Main Form & Detail Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left Side: Lengkapi Data Pesanan Anda Card (~2/3 width) -->
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 md:p-8 space-y-6">
                    
                    <!-- Card Header -->
                    <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                        <button type="button" onclick="history.back()" class="text-slate-800 hover:text-slate-600 font-bold transition text-lg">
                            ←
                        </button>
                        <h3 class="font-extrabold text-slate-800 text-lg md:text-xl">Lengkapi Data Pesanan Anda</h3>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-5">
                        
                        <!-- Pilih Layanan -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Layanan</label>
                            <div class="relative">
                                <select id="select-service" name="service_key" onchange="onServiceChange()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold appearance-none">
                                    @foreach($services as $service)
                                        <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Terapis -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Terapis (Opsional)</label>
                            <div class="relative">
                                <select id="select-therapist" name="therapist_name" onchange="onTherapistChange()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold appearance-none">
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->name }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Jadwal Tanggal -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Jadwal Tanggal</label>
                            <input type="date" id="input-date" name="schedule_date" onchange="onDateChange()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold">
                        </div>

                        <!-- Pilih Waktu (Jam Terapi) -->
                        <div class="space-y-4">
                            <span class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Waktu (Jam Terapi)</span>
                            
                            <!-- Sesi Pagi - Siang -->
                            <div class="space-y-2">
                                <span class="block text-xs font-semibold text-gray-400">Sesi Pagi - Siang:</span>
                                <div class="grid grid-cols-4 gap-2.5">
                                    <button type="button" onclick="selectTime('09:00')" id="time-09:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">09:00</button>
                                    <button type="button" onclick="selectTime('11:00')" id="time-11:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">11:00</button>
                                    <button type="button" onclick="selectTime('13:00')" id="time-13:00" class="py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none">13:00</button>
                                    <button type="button" onclick="selectTime('15:00')" id="time-15:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">15:00</button>
                                </div>
                            </div>

                            <!-- Sesi Malam -->
                            <div class="space-y-2">
                                <span class="block text-xs font-semibold text-gray-400">Sesi Malam:</span>
                                <div class="grid grid-cols-4 gap-2.5">
                                    <button type="button" onclick="selectTime('19:00')" id="time-19:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">19:00</button>
                                    <button type="button" onclick="selectTime('20:00')" id="time-20:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">20:00</button>
                                    <button type="button" onclick="selectTime('21:00')" id="time-21:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">21:00</button>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Lokasi Terapi -->
                        <div class="space-y-3 pt-2">
                            <span class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Lokasi Terapi</span>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-semibold text-slate-700">
                                    <input type="radio" name="location_type" value="home" checked onchange="onLocationChange()"
                                        class="w-4 h-4 text-[#0f172a] focus:ring-slate-900 border-gray-300">
                                    <span>Datang ke Rumah (Home Service)</span>
                                </label>
                                <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-semibold text-slate-700">
                                    <input type="radio" name="location_type" value="clinic" onchange="onLocationChange()"
                                        class="w-4 h-4 text-[#0f172a] focus:ring-slate-900 border-gray-300">
                                    <span>Datang ke Klinik</span>
                                </label>
                            </div>
                        </div>

                        <!-- Input Alamat Rumah (Hanya untuk Home Service) -->
                        <div id="address-input-group" class="space-y-1.5 pt-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Alamat Lengkap Rumah</label>
                            <textarea id="input-address" rows="3" oninput="onAddressInput()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold"
                                placeholder="Masukkan alamat lengkap rumah Anda...">{{ auth()->user()->address ?? '' }}</textarea>
                        </div>

                    </div>

                </div>

                <!-- Right Side: Detail Pemesanan Card (~1/3 width) -->
                <div class="lg:col-span-1 lg:sticky lg:top-24">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8 space-y-6">
                        <h3 class="font-bold text-slate-800 text-base border-b border-gray-100 pb-3">Detail Pemesanan</h3>
                        
                        <!-- Booking details listing -->
                        <div class="space-y-4 text-xs font-semibold text-gray-500">
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Layanan:</span>
                                <span class="block text-slate-800 text-sm" id="detail-service-name">Pijat Tradisional (90 Menit)</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Terapis:</span>
                                <span class="block text-slate-800 text-sm" id="detail-therapist-name">Adam Aryanto</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Jadwal:</span>
                                <span class="block text-slate-800 text-sm" id="detail-schedule-date">Sabtu, 16 Mei 2026 • 13:00 WIB</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Lokasi:</span>
                                <span class="block text-slate-800 text-sm" id="detail-location-type">Home Service</span>
                                <span class="block text-gray-500 font-medium mt-0.5" id="detail-location-address">Jl. Slamet Riyadi No. 12, Solo</span>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Prices lists details -->
                        <div class="space-y-3.5 text-xs font-semibold">
                            <div class="flex justify-between text-gray-500">
                                <span>Harga Layanan</span>
                                <span class="text-slate-800" id="detail-service-price">Rp 150.000</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Biaya Transportasi</span>
                                <span class="text-slate-800" id="detail-transport-price">Rp 20.000</span>
                            </div>
                            
                            <hr class="border-gray-100">
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-800 font-bold">Total Pembayaran</span>
                                <span class="text-slate-900 font-extrabold text-base md:text-lg" id="detail-total-payment">Rp 170.000</span>
                            </div>
                        </div>

                        <!-- Confirm button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-[#0f172a] text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-md hover:shadow-lg focus:outline-none">
                                Konfirmasi & Pesan
                            </button>
                        </div>

                    </div>
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
    </form>
@endsection

@section('scripts')
    <script>
        // Prices mapping for services
        const servicesPriceList = {
            @foreach($services as $service)
            "{{ $service->slug }}": {
                name: "{{ $service->name }} ({{ $service->default_duration }})",
                price_clinic: {{ $service->price_clinic }},
                price_home: {{ $service->price_home }}
            },
            @endforeach
        };

        // Active State variables
        let selectedServiceKey = "{{ $services->first() ? $services->first()->slug : 'pijat-tradisional' }}";
        let selectedTherapistName = "{{ $therapists->first() ? $therapists->first()->name : 'Adam Aryanto' }}";
        let selectedDate = "2026-05-16";
        let selectedTimeValue = "13:00";
        let selectedLocationType = "home"; // home or clinic
        let selectedAddress = {!! json_encode(auth()->user()->address ?? "Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123") !!};
        let transportFee = 20000;

        function onAddressInput() {
            selectedAddress = document.getElementById('input-address').value;
            updateBookingSummary();
        }

        function formatPrice(number) {
            return "Rp " + number.toLocaleString('id-ID');
        }

        // Format Indonesian Date formatting
        function formatIndoDate(dateStr) {
            if (!dateStr) return "-";
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;

            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            const dayName = days[date.getDay()];
            const day = date.getDate();
            const monthName = months[date.getMonth()];
            const year = date.getFullYear();

            return `${dayName}, ${day} ${monthName} ${year}`;
        }

        // Setup Initial Form State
        function initializeBookingForm() {
            // Check query strings
            const urlParams = new URLSearchParams(window.location.search);
            selectedServiceKey = urlParams.get('type') || "{{ $services->first() ? $services->first()->slug : 'pijat-tradisional' }}";
            
            // Set service dropdown
            const serviceSelect = document.getElementById('select-service');
            if (serviceSelect) {
                serviceSelect.value = selectedServiceKey;
            }

            // Set default date
            const dateInput = document.getElementById('input-date');
            if (dateInput) {
                selectedDate = "2026-05-16";
                dateInput.value = selectedDate;
            }

            // Fill details card
            updateBookingSummary();
        }

        // Update booking summary card
        function updateBookingSummary() {
            const serviceData = servicesPriceList[selectedServiceKey];
            
            // 1. Service Details
            document.getElementById('detail-service-name').innerText = serviceData.name;
            document.getElementById('detail-therapist-name').innerText = selectedTherapistName;

            // 2. Schedule
            const formattedDate = formatIndoDate(selectedDate);
            document.getElementById('detail-schedule-date').innerText = `${formattedDate} • ${selectedTimeValue} WIB`;

            // 3. Location & Address
            const finalAddress = selectedLocationType === 'home' ? selectedAddress : "Klinik Utama Nusa Terapi, Solo";
            
            let servicePrice = 0;
            if (selectedLocationType === 'home') {
                document.getElementById('detail-location-type').innerText = "Home Service";
                document.getElementById('detail-location-address').innerText = selectedAddress;
                document.getElementById('address-input-group').classList.remove('hidden');
                transportFee = 20000;
                servicePrice = serviceData.price_home;
            } else {
                document.getElementById('detail-location-type').innerText = "Datang ke Klinik";
                document.getElementById('detail-location-address').innerText = "Klinik Utama Nusa Terapi, Solo";
                document.getElementById('address-input-group').classList.add('hidden');
                transportFee = 0;
                servicePrice = serviceData.price_clinic;
            }
            document.getElementById('hidden-address').value = finalAddress;

            // 4. Prices Calculations
            document.getElementById('detail-service-price').innerText = formatPrice(servicePrice);
            document.getElementById('detail-transport-price').innerText = formatPrice(transportFee);
            
            const totalPayment = servicePrice + transportFee;
            document.getElementById('detail-total-payment').innerText = formatPrice(totalPayment);
        }

        // Dropdown service event handler
        function onServiceChange() {
            selectedServiceKey = document.getElementById('select-service').value;
            
            // Adjust default therapist based on service choice
            const therapistSelect = document.getElementById('select-therapist');
            if (therapistSelect) {
                let targetName = "";
                if (selectedServiceKey === 'refleksi-kaki') {
                    targetName = "Siti Aminah";
                } else if (selectedServiceKey === 'terapi-bekam') {
                    targetName = "Rizky Firmansyah";
                } else if (selectedServiceKey === 'lulur-scrub') {
                    targetName = "Diana Putri";
                } else {
                    targetName = "Adam Aryanto";
                }

                // Check if targetName option exists in the dropdown
                let optionExists = false;
                for (let i = 0; i < therapistSelect.options.length; i++) {
                    if (therapistSelect.options[i].value === targetName) {
                        optionExists = true;
                        break;
                    }
                }

                if (optionExists) {
                    therapistSelect.value = targetName;
                } else if (therapistSelect.options.length > 0) {
                    // If target doesn't exist, keep the current selected or fallback to first option
                    if (!therapistSelect.value && therapistSelect.selectedIndex < 0) {
                        therapistSelect.selectedIndex = 0;
                    }
                }
                selectedTherapistName = therapistSelect.value;
            }

            updateBookingSummary();
        }

        // Dropdown therapist event handler
        function onTherapistChange() {
            selectedTherapistName = document.getElementById('select-therapist').value;
            updateBookingSummary();
        }

        // Date selector event handler
        function onDateChange() {
            selectedDate = document.getElementById('input-date').value;
            updateBookingSummary();
        }

        // Time button select handler
        function selectTime(timeStr) {
            selectedTimeValue = timeStr;
            document.getElementById('hidden-schedule-time').value = timeStr;

            // Normalize classes for all buttons
            const activeTimes = ["09:00", "11:00", "13:00", "15:00", "19:00", "20:00", "21:00"];
            activeTimes.forEach(t => {
                const el = document.getElementById("time-" + t);
                if (el) {
                    el.className = "py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none";
                }
            });

            // Set active class for selected time button
            const activeEl = document.getElementById("time-" + timeStr);
            if (activeEl) {
                activeEl.className = "py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none";
            }

            updateBookingSummary();
        }

        // Location radio button event handler
        function onLocationChange() {
            const checkedRadio = document.querySelector('input[name="location_type"]:checked');
            if (checkedRadio) {
                selectedLocationType = checkedRadio.value;
            }
            updateBookingSummary();
        }

        // Run setup
        initializeBookingForm();

        // Midtrans Simulation Variables
        let timerInterval = null;

        function startPaymentFlow() {
            // Generate mock Order ID
            const datePrefix = "2605";
            const rand = Math.floor(Math.random() * 900) + 100;
            const mockOrderId = `TRX-${datePrefix}-${rand}`;
            
            // Set values in modal
            const totalPaymentText = document.getElementById('detail-total-payment').innerText;
            document.getElementById('midtrans-order-id').innerText = mockOrderId;
            document.getElementById('midtrans-total-amount').innerText = totalPaymentText;
            
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
            const bcaStep = document.getElementById('midtrans-step-bca');
            const gopayStep = document.getElementById('midtrans-step-gopay');
            const isStep2Active = (!bcaStep.classList.contains('hidden') || !gopayStep.classList.contains('hidden'));

            if (isStep2Active) {
                document.getElementById('hidden-payment-status').value = "pending";
                paymentCompleted = true;
                document.getElementById('booking-form').submit();
            } else {
                closeMidtransModal();
            }
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
            document.getElementById('midtrans-step-select').classList.add('hidden');

            if (method === 'gopay') {
                // Show GoPay QR step
                document.getElementById('midtrans-step-gopay').classList.remove('hidden');
            } else {
                // Show Virtual Account step for bank transfer methods
                const vaStep = document.getElementById('midtrans-step-bca');
                vaStep.classList.remove('hidden');

                // Set VA title and number based on method
                const vaTitle = document.getElementById('midtrans-va-title');
                const vaNumber = document.getElementById('midtrans-va-number');
                const vaNumbers = {
                    bca: { title: 'Nomor BCA Virtual Account', number: '88012081234567890' },
                    mandiri: { title: 'Nomor Mandiri Virtual Account', number: '89012098765432100' },
                    bni: { title: 'Nomor BNI Virtual Account', number: '88089055667788900' },
                };
                const info = vaNumbers[method] || vaNumbers.bca;
                vaTitle.innerText = info.title;
                vaNumber.innerText = info.number;
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

        let paymentCompleted = false;

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
                        paymentCompleted = true;
                        document.getElementById('booking-form').submit();
                    }, 1500);
                    
                }, 1200);
            }, 1000);
        }

        // Intercept Form Submit to show Payment Modal
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            if (!paymentCompleted) {
                e.preventDefault();
                startPaymentFlow();
            }
            // If paymentCompleted is true, let the form submit normally
        });
    </script>
@endsection
