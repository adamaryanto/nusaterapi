@extends('layouts.app')

@section('title', 'Membership Saya - Nusa Terapi Center')

@section('content')
    <div class="max-w-5xl mx-auto py-12 px-6">
        
        <!-- Page Title & Intro -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Program Membership</h2>
            <p class="text-sm text-gray-500 mt-2">Dapatkan layanan terapi dengan harga lebih hemat, bebas biaya panggilan, dan prioritas pemesanan.</p>
        </div>

        <!-- 1. Top Section: Status Keanggotaan -->
        <div class="mb-10">
            @if($isMember)
                <!-- Active Member Banner -->
                <div class="relative overflow-hidden bg-gradient-to-r from-emerald-800 to-teal-700 text-white rounded-3xl p-6 md:p-8 shadow-lg flex flex-col md:flex-row items-center justify-between">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-600/20 rounded-full blur-2xl"></div>
                    <div class="mb-4 md:mb-0 space-y-2 text-center md:text-left z-10">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/40 text-emerald-200 border border-emerald-500/30">
                            ★ VIP ACTIVE
                        </span>
                        <h3 class="text-xl md:text-2xl font-extrabold">Status Keanggotaan: Member VIP Aktif</h3>
                        <p class="text-xs md:text-sm text-emerald-100">Selamat! Akun Anda telah terdaftar sebagai Member VIP. Nikmati potongan harga dan bebas biaya transport panggilan secara otomatis.</p>
                    </div>
                    <div class="text-5xl md:text-6xl select-none z-10">👑</div>
                </div>
            @else
                <!-- Non Member Banner -->
                <div class="relative overflow-hidden bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-md flex flex-col md:flex-row items-center justify-between">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-600/20 rounded-full blur-2xl"></div>
                    <div class="mb-4 md:mb-0 space-y-2 text-center md:text-left z-10">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-900/40 text-slate-300 border border-slate-600/30">
                            🔒 NON-ACTIVE
                        </span>
                        <h3 class="text-xl md:text-2xl font-extrabold">Status Keanggotaan: Belum Menjadi Member</h3>
                        <p class="text-xs md:text-sm text-slate-300">Akun Anda saat ini berstatus Non-Member (Biasa). Silakan pilih paket membership di bawah untuk mengaktifkan keuntungan eksklusif Anda.</p>
                    </div>
                    <div class="text-5xl md:text-6xl select-none opacity-40 z-10">👑</div>
                </div>
            @endif
        </div>

        <!-- 2. Middle Section: Pilihan Paket Membership (Cards) -->
        <div class="mb-12">
            <h4 class="font-extrabold text-slate-800 text-lg text-center mb-6">Pilihan Paket Membership</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto items-stretch">
                
                <!-- Package 1: VIP Bulanan -->
                <div class="bg-white border-2 {{ $isMember ? 'border-gray-200 opacity-90' : 'border-gray-200' }} rounded-3xl p-8 flex flex-col justify-between shadow-sm hover:shadow-md transition relative overflow-hidden">
                    @if($isMember)
                        <div class="absolute top-0 right-0 bg-emerald-500 text-white px-4 py-1 text-xs font-bold rounded-bl-xl uppercase tracking-wider">
                            Paket Anda
                        </div>
                    @endif
                    
                    <div class="space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">VIP Bulanan</span>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-4xl font-extrabold text-slate-950">Rp 50.000</span>
                                <span class="text-sm text-gray-500 ml-1">/ bulan</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Pilihan populer untuk terapi rutin setiap bulan.</p>
                        </div>
                        
                        <hr class="border-gray-100">
                        
                        <ul class="space-y-3.5 text-xs text-slate-700">
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span><strong>Bebas Biaya Transportasi Panggilan</strong> (Hemat Rp 20.000)</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span><strong>Potongan Rp {{ number_format($discountAmount) }}</strong> per booking</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span>Kuota diskon hingga <strong>{{ $weeklyLimit }}x seminggu</strong></span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span>Prioritas konfirmasi terapis</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-8">
                        @if($isMember)
                            <button disabled class="w-full py-3.5 bg-emerald-100 text-emerald-700 font-bold text-xs rounded-xl cursor-default text-center">
                                ✓ Paket Sedang Aktif
                            </button>
                        @else
                            <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2c%20saya%20ingin%20aktivasi%20membership%20*VIP%20Bulanan*%20untuk%20akun%20saya%20({{ urlencode($user->email) }})" target="_blank" class="block w-full py-3.5 bg-[#00b074] hover:bg-[#009c67] text-white font-bold text-xs rounded-xl text-center shadow-sm transition">
                                Pilih Paket Bulanan
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Package 2: VIP Tahunan -->
                <div class="bg-white border-2 {{ $isMember ? 'border-gray-200' : 'border-yellow-400 shadow-sm' }} rounded-3xl p-8 flex flex-col justify-between shadow-sm hover:shadow-md transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-yellow-400 text-slate-900 px-4 py-1 text-xs font-bold rounded-bl-xl uppercase tracking-wider">
                        Hemat 25%
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">VIP Tahunan</span>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-4xl font-extrabold text-slate-950">Rp 450.000</span>
                                <span class="text-sm text-gray-500 ml-1">/ tahun</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Investasi terbaik untuk kesehatan kebugaran jangka panjang.</p>
                        </div>
                        
                        <hr class="border-gray-100">
                        
                        <ul class="space-y-3.5 text-xs text-slate-700">
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span><strong>Bebas Biaya Transportasi Panggilan</strong> (Hemat Rp 20.000)</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span><strong>Potongan Rp {{ number_format($discountAmount) }}</strong> per booking</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span>Kuota diskon hingga <strong>{{ $weeklyLimit }}x seminggu</strong></span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span>Prioritas konfirmasi terapis</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <span class="text-emerald-500 font-bold text-sm">✓</span>
                                <span class="text-yellow-600 font-semibold">Bonus 1x Voucher Pijat Tradisional gratis!</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-8">
                        @if($isMember)
                            <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2c%20saya%20tertarik%20untuk%20upgrade%20ke%20membership%20*VIP%20Tahunan*%20dari%20akun%20saya%20({{ urlencode($user->email) }})" target="_blank" class="block w-full py-3.5 bg-[#0f172a] hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center shadow-sm transition">
                                Upgrade Ke Tahunan
                            </a>
                        @else
                            <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2c%20saya%20ingin%20aktivasi%20membership%20*VIP%20Tahunan*%20untuk%20akun%20saya%20({{ urlencode($user->email) }})" target="_blank" class="block w-full py-3.5 bg-yellow-400 hover:bg-yellow-500 text-slate-950 font-bold text-xs rounded-xl text-center shadow-sm transition">
                                Pilih Paket Tahunan
                            </a>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>

        <!-- 3. Bottom Section: Detail Kuota (Hanya muncul jika Member aktif) -->
        @if($isMember)
            <div class="max-w-4xl mx-auto bg-slate-50 border border-slate-200 rounded-3xl p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-base">Penggunaan Kuota Diskon Mingguan Anda</h4>
                        <p class="text-xs text-gray-500 mt-1">Kuota diskon otomatis mereset setiap hari Senin pukul 00:00 WIB.</p>
                    </div>
                    <div class="mt-4 sm:mt-0 bg-white border border-slate-200 rounded-2xl px-4 py-2 text-center shadow-sm">
                        <span class="text-sm font-black text-slate-900">{{ $weeklyLimit - $usedCount }} / {{ $weeklyLimit }}</span>
                        <span class="block text-[9px] uppercase tracking-wider font-extrabold text-gray-400">Kuota Tersisa</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                @php
                    $percentage = $weeklyLimit > 0 ? (($weeklyLimit - $usedCount) / $weeklyLimit) * 100 : 0;
                @endphp
                <div class="space-y-2">
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-gray-500 font-medium">
                        <span>Sudah Terpakai: {{ $usedCount }} kali</span>
                        <span>Batas Maksimal: {{ $weeklyLimit }} kali / minggu</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="text-xs text-slate-600 flex items-center space-x-2">
                        <span class="text-yellow-500 text-lg">★</span>
                        <span>Nomor Member VIP Anda: <strong class="font-mono text-slate-800">MBR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</strong></span>
                    </div>
                    <a href="{{ route('customer.booking') }}" class="py-2.5 px-6 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Booking Sekarang
                    </a>
                </div>
            </div>
        @endif

    </div>
@endsection
