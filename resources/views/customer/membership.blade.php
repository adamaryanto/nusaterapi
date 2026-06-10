@extends('layouts.app')

@section('title', 'Membership Saya - Nusa Terapi Center')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        
        <!-- Page Title -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-6">Membership Saya</h2>

        @if($isMember)
            <!-- ACTIVE MEMBER DASHBOARD -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                
                <!-- VIP Card Col (Left 2/3 on desktop) -->
                <div class="md:col-span-2 space-y-6 flex flex-col justify-between">
                    
                    <!-- Glossy VIP Gold Card -->
                    <div class="relative overflow-hidden bg-gradient-to-tr from-[#111e35] via-[#1e293b] to-[#0f172a] border border-slate-800 rounded-3xl p-8 text-white shadow-xl flex flex-col justify-between h-56 group hover:shadow-2xl transition duration-300">
                        <!-- Card background decoration -->
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-700/10 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-slate-700/10 rounded-full blur-xl"></div>
                        <div class="absolute right-6 top-6 text-yellow-400 font-extrabold tracking-widest text-xs border border-yellow-400/40 px-3 py-1 rounded-full bg-yellow-400/10 select-none">
                            ★ VIP MEMBER
                        </div>

                        <div>
                            <p class="text-[9px] uppercase tracking-widest font-extrabold text-gray-400 mb-1">Nusa Terapi Center</p>
                            <h3 class="text-xl font-bold tracking-tight text-white select-all">{{ $user->name }}</h3>
                            <p class="text-xs font-mono text-gray-400 select-all">ID: MBR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[9px] uppercase tracking-wider font-extrabold text-gray-400">Status Keanggotaan</p>
                                <span class="inline-block text-xs font-bold text-emerald-400">Aktif Selamanya</span>
                            </div>
                            <span class="text-3xl">💆</span>
                        </div>
                    </div>

                    <!-- Quota Statistics Card -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">Kuota Diskon Mingguan</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Mereset otomatis setiap Senin pukul 00:00 WIB</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-extrabold text-slate-900">{{ $weeklyLimit - $usedCount }} / {{ $weeklyLimit }}</span>
                                <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">Tersisa</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @php
                            $percentage = $weeklyLimit > 0 ? (($weeklyLimit - $usedCount) / $weeklyLimit) * 100 : 0;
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-emerald-500 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="flex justify-between text-xs text-gray-500 font-medium">
                            <span>Digunakan: {{ $usedCount }} kali</span>
                            <span>Maksimal: {{ $weeklyLimit }} kali/minggu</span>
                        </div>
                    </div>

                </div>

                <!-- Membership Benefits Summary (Right 1/3) -->
                <div class="md:col-span-1 bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-4">Benefit Anda</h4>
                        <div class="space-y-4">
                            
                            <!-- Benefit 1 -->
                            <div class="flex items-start space-x-3.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">🏠</div>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">Bebas Biaya Panggilan</h5>
                                    <p class="text-[10px] text-gray-500 mt-0.5 leading-relaxed">Gratis biaya transportasi untuk pemesanan layanan Home Service (Hemat Rp 20.000).</p>
                                </div>
                            </div>

                            <!-- Benefit 2 -->
                            <div class="flex items-start space-x-3.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">💸</div>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">Potongan Harga Langsung</h5>
                                    <p class="text-[10px] text-gray-500 mt-0.5 leading-relaxed">Potongan sebesar Rp {{ number_format($discountAmount, 0, ',', '.') }} per booking sesuai batas limit kuota.</p>
                                </div>
                            </div>

                            <!-- Benefit 3 -->
                            <div class="flex items-start space-x-3.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base flex-shrink-0">💎</div>
                                <div>
                                    <h5 class="text-xs font-bold text-slate-800">Prioritas Booking</h5>
                                    <p class="text-[10px] text-gray-500 mt-0.5 leading-relaxed">Pemesanan Anda diprioritaskan untuk konfirmasi terapis tercepat.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 mt-6">
                        <a href="{{ route('customer.booking') }}" class="block w-full py-3 bg-[#0f172a] hover:bg-slate-800 text-white text-xs font-bold rounded-xl text-center shadow-md transition">
                            Pesan Terapi Sekarang
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- NOT MEMBER INVITE PAGE -->
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden p-8 md:p-12 text-center max-w-2xl mx-auto space-y-6">
                <span class="text-6xl block select-none">👑</span>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-slate-900">Gabung Membership Nusa Terapi</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">Nikmati berbagai penawaran dan benefit premium eksklusif untuk kebugaran tubuh Anda tanpa batas.</p>
                </div>

                <!-- Benefits List grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left max-w-lg mx-auto pt-4">
                    <div class="bg-slate-50 p-4 border border-slate-100 rounded-2xl flex items-center space-x-3.5">
                        <span class="text-2xl flex-shrink-0">🏠</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Bebas Biaya Panggil</h4>
                            <p class="text-[10px] text-gray-400 mt-0.5">Gratis biaya transport ke rumah.</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-4 border border-slate-100 rounded-2xl flex items-center space-x-3.5">
                        <span class="text-2xl flex-shrink-0">💸</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Potongan Booking</h4>
                            <p class="text-[10px] text-gray-400 mt-0.5">Hemat Rp 15.000 per pemesanan.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 max-w-md mx-auto space-y-3.5">
                    <div class="bg-amber-50/70 border border-amber-200 text-amber-800 rounded-2xl px-5 py-4 text-xs font-medium leading-relaxed">
                        ⚠️ <strong>Cara Bergabung:</strong> Hubungi admin klinik kami atau tanyakan kepada terapis/admin kami saat terapi berlangsung untuk langsung diaktifkan menjadi Member VIP.
                    </div>

                    <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2520saya%2520ingin%2520aktivasi%2520membership%2520VIP" target="_blank" class="inline-flex items-center space-x-2 py-3.5 px-8 bg-[#00b074] hover:bg-[#009c67] text-white text-xs font-bold rounded-xl shadow-md transition">
                        <span>💬</span>
                        <span>Hubungi Admin via WhatsApp</span>
                    </a>
                </div>
            </div>
        @endif

    </div>
@endsection
