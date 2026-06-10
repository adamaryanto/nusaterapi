@extends('layouts.app')

@section('title', 'Membership Saya - Nusa Terapi Center')

@section('content')
    <div class="max-w-6xl mx-auto py-12 px-6">
        
        <!-- Page Title & Intro -->
        <div class="mb-8">
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
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-900/40 text-emerald-200 border border-emerald-500/30">
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
                        <p class="text-xs md:text-sm text-slate-300">Akun Anda saat ini berstatus Non-Member (Biasa). Aktifkan keanggotaan VIP untuk menikmati diskon eksklusif dan bebas biaya panggilan.</p>
                    </div>
                    <div class="text-5xl md:text-6xl select-none opacity-40 z-10">👑</div>
                </div>
            @endif
        </div>

        <!-- 2. Bottom Section: Cards Grid (3 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            
            <!-- Card 1: Kartu Keanggotaan / Quota -->
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-5">Kartu Keanggotaan Saya</h4>
                    
                    @if($isMember)
                        <!-- Glossy Gold VIP Card -->
                        <div class="relative overflow-hidden bg-gradient-to-tr from-[#111e35] via-[#1e293b] to-[#0f172a] border border-slate-800 rounded-2xl p-6 text-white shadow-md flex flex-col justify-between h-44 group hover:shadow-lg transition duration-300">
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-slate-700/10 rounded-full blur-xl"></div>
                            <div class="absolute right-4 top-4 text-yellow-400 font-extrabold tracking-widest text-[9px] border border-yellow-400/40 px-2 py-0.5 rounded-full bg-yellow-400/10 select-none">
                                ★ VIP MEMBER
                            </div>
                            <div>
                                <p class="text-[8px] uppercase tracking-widest font-extrabold text-gray-400 mb-0.5">Nusa Terapi Center</p>
                                <h3 class="text-base font-bold tracking-tight text-white select-all">{{ $user->name }}</h3>
                                <p class="text-[10px] font-mono text-gray-400 select-all">ID: MBR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="text-[8px] uppercase tracking-wider font-extrabold text-gray-400">Keanggotaan</p>
                                    <span class="inline-block text-[10px] font-bold text-emerald-400">Aktif Selamanya</span>
                                </div>
                                <span class="text-2xl">💆</span>
                            </div>
                        </div>

                        <!-- Quota Statistics -->
                        <div class="mt-6 space-y-3">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-slate-700">Kuota Diskon Mingguan:</span>
                                <span class="font-bold text-slate-900">{{ $weeklyLimit - $usedCount }} / {{ $weeklyLimit }} tersisa</span>
                            </div>
                            
                            @php
                                $percentage = $weeklyLimit > 0 ? (($weeklyLimit - $usedCount) / $weeklyLimit) * 100 : 0;
                            @endphp
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-[10px] text-gray-400 leading-relaxed">Diskon Rp {{ number_format($discountAmount, 0, ',', '.') }} per booking. Kuota otomatis diperbarui setiap Senin pukul 00:00 WIB.</p>
                        </div>
                    @else
                        <!-- Inactive Grey Card Preview -->
                        <div class="relative overflow-hidden bg-slate-100 border border-dashed border-slate-300 rounded-2xl p-6 text-slate-400 flex flex-col justify-between h-44 select-none">
                            <div class="absolute inset-0 flex items-center justify-center bg-slate-200/30 backdrop-blur-[0.5px]">
                                <span class="text-xs font-bold bg-slate-300 text-slate-600 px-3 py-1 rounded-full border border-slate-400/20 shadow-sm uppercase tracking-wider">🔒 Belum Aktif</span>
                            </div>
                            <div class="opacity-40">
                                <p class="text-[8px] uppercase tracking-widest font-extrabold text-gray-400 mb-0.5">Nusa Terapi Center</p>
                                <h3 class="text-base font-bold tracking-tight text-slate-600">{{ $user->name }}</h3>
                                <p class="text-[10px] font-mono text-slate-500">ID: MBR-XXXXX</p>
                            </div>
                            <div class="flex justify-between items-end opacity-40">
                                <div>
                                    <p class="text-[8px] uppercase tracking-wider font-extrabold text-gray-400">Keanggotaan</p>
                                    <span class="inline-block text-[10px] font-bold text-slate-500">Non-Member</span>
                                </div>
                                <span class="text-2xl">💆</span>
                            </div>
                        </div>
                        <div class="mt-6 bg-slate-50 border border-slate-100 rounded-2xl p-4">
                            <p class="text-xs text-slate-500 leading-relaxed text-center">Status Anda saat ini adalah <strong>Non-Member</strong>. Daftarkan akun Anda agar dapat menikmati potongan harga langsung dan kuota diskon mingguan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 2: Keuntungan VIP Member -->
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-5">Keuntungan VIP Member</h4>
                    <div class="space-y-5">
                        <!-- Benefit 1 -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">🏠</div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Bebas Biaya Transportasi</h5>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Gratis biaya transportasi untuk pemesanan layanan Home Service (Hemat Rp 20.000 setiap booking).</p>
                            </div>
                        </div>

                        <!-- Benefit 2 -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">💸</div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Potongan Harga Langsung</h5>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Potongan sebesar Rp {{ number_format($discountAmount, 0, ',', '.') }} per booking sesuai batas kuota mingguan Anda.</p>
                            </div>
                        </div>

                        <!-- Benefit 3 -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">🔄</div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Kuota Diskon Mingguan</h5>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Dapatkan kuota diskon hingga {{ $weeklyLimit }} kali penggunaan setiap minggu, yang direset otomatis.</p>
                            </div>
                        </div>

                        <!-- Benefit 4 -->
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">💎</div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Prioritas Konfirmasi Terapis</h5>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Pemesanan oleh Member VIP diprioritaskan oleh sistem untuk dicarikan terapis terbaik lebih cepat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Biaya & Aktivasi -->
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-5">Biaya & Cara Aktivasi</h4>
                    
                    @if($isMember)
                        <div class="space-y-4">
                            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-center space-y-2">
                                <span class="text-3xl block select-none">🎉</span>
                                <h5 class="text-xs font-bold text-emerald-800">Membership Anda Aktif</h5>
                                <p class="text-[10px] text-emerald-600 leading-relaxed">Keanggotaan Anda dikelola secara langsung oleh admin klinik Nusa Terapi Center.</p>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed mt-2 text-center">Jika Anda memiliki pertanyaan mengenai status membership atau ingin memperpanjang periode membership Anda, silakan hubungi tim kami.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div class="text-center py-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                <span class="text-[10px] uppercase tracking-wider font-extrabold text-gray-400 block">Biaya Aktivasi VIP</span>
                                <span class="text-2xl font-black text-slate-900 mt-1 block">Rp 50.000 <span class="text-xs font-normal text-gray-500">/ bulan</span></span>
                            </div>
                            
                            <div class="bg-amber-50/70 border border-amber-200 text-amber-800 rounded-2xl px-4 py-3 text-[11px] font-medium leading-relaxed">
                                <strong>Cara Bergabung:</strong> Hubungi admin kami via WhatsApp atau silakan lakukan pembayaran langsung di kasir klinik untuk langsung diaktifkan sebagai Member VIP.
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pt-6 border-t border-gray-100 mt-6">
                    @if($isMember)
                        <a href="{{ route('customer.booking') }}" class="block w-full py-3 bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold rounded-xl text-center shadow-md transition">
                            Pesan Terapi Sekarang
                        </a>
                    @else
                        <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2c%20saya%20ingin%20aktivasi%20membership%20VIP%20untuk%20akun%20saya%20({{ urlencode($user->email) }})" target="_blank" class="block w-full py-3 bg-[#00b074] hover:bg-[#009c67] text-white text-xs font-bold rounded-xl text-center shadow-md transition">
                            💬 Hubungi Admin via WhatsApp
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection
