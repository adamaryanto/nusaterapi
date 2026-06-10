@extends('layouts.app')

@section('title', 'Membership Saya - Nusa Terapi Center')

@section('content')
    <div class="max-w-6xl mx-auto py-12 px-6">
        
        <!-- Page Title & Intro -->
        <div class="mb-10 text-center">
            <span class="text-emerald-600 font-semibold tracking-wider text-xs uppercase block mb-1">OPTIMALKAN KESEHATAN ANDA</span>
            <h2 class="text-3xl md:text-4xl font-black italic text-slate-900 tracking-tight uppercase mb-3">PROGRAM MEMBERSHIP</h2>
            <p class="text-sm text-gray-500 max-w-2xl mx-auto leading-relaxed">Dapatkan keuntungan eksklusif, diskon khusus, dan prioritas booking dengan menjadi member resmi kami.</p>
        </div>

        <!-- 1. Top Section: Status Keanggotaan Card -->
        <div class="mb-10">
            <div class="bg-slate-900 text-white rounded-3xl p-8 shadow-lg flex flex-col md:flex-row items-center justify-between border border-slate-800 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-800/50 rounded-full blur-2xl"></div>
                
                <div class="space-y-4 text-center md:text-left z-10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">STATUS KEANGGOTAAN</span>
                    
                    <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-3">
                        @if($isMember && $userTier)
                            <h3 class="text-2xl md:text-3xl font-black italic tracking-tight text-white uppercase">{{ $userTier->name }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase tracking-wider">
                                AKTIF
                            </span>
                        @else
                            <h3 class="text-2xl md:text-3xl font-black italic tracking-tight text-slate-400 uppercase">NON-MEMBER</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 uppercase tracking-wider">
                                TIDAK AKTIF
                            </span>
                        @endif
                    </div>
                    
                    @if($isMember && $userTier)
                        <p class="text-xs text-slate-400">Durasi: {{ $userTier->duration }} Hari (Reset Kuota: {{ $window }} Hari)</p>
                        <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0 pt-2 text-xs text-slate-300">
                            <div>
                                SISA DISKON WEEKDAY: <span class="text-emerald-400 font-bold">{{ $limitWd === null ? '∞' : ($limitWd - $usedWd) . '/' . $limitWd . 'x' }}</span>
                            </div>
                            <div>
                                SISA DISKON WEEKEND: <span class="text-emerald-400 font-bold">{{ $limitWe === null ? '∞' : ($limitWe - $usedWe) . '/' . $limitWe . 'x' }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-slate-400">Silakan pilih paket di bawah untuk mengaktifkan keanggotaan</p>
                        <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0 pt-2 text-xs text-slate-400">
                            <div>
                                SISA DISKON WEEKDAY: <span class="text-slate-500 font-bold">0x</span>
                            </div>
                            <div>
                                SISA DISKON WEEKEND: <span class="text-slate-500 font-bold">0x</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right mini card inside status card -->
                <div class="mt-6 md:mt-0 bg-slate-800/80 border border-slate-700 rounded-2xl px-8 py-5 text-center min-w-[180px] shadow-inner z-10">
                    @if($isMember && $userTier)
                        <span class="text-3xl font-black text-emerald-400 block">{{ $userTier->discount_wd }}% / {{ $userTier->discount_we }}%</span>
                        <span class="block text-[9px] uppercase tracking-widest font-bold text-slate-350 mt-1">DISKON BERLAKU</span>
                    @else
                        <span class="text-3xl font-black text-slate-500 block">0% / 0%</span>
                        <span class="block text-[9px] uppercase tracking-widest font-bold text-slate-500 mt-1">DISKON BERLAKU</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Bottom Section: Cards Grid (3 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            
            @foreach($tiers as $tier)
                <!-- Package Card -->
                <div class="bg-white border border-gray-250 rounded-3xl p-8 flex flex-col justify-between shadow-sm hover:shadow-md transition">
                    <div class="space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">{{ $tier->name }}</span>
                            <div class="mt-4 flex items-baseline">
                                <span class="text-4xl font-extrabold text-slate-900">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                                <span class="text-sm text-gray-500 ml-1">/ {{ $tier->duration }} hari</span>
                            </div>
                        </div>
                        
                        <hr class="border-gray-100">
                        
                        <ul class="space-y-4 text-xs text-slate-700">
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Bebas biaya transportasi panggilan</span>
                            </li>
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Diskon Weekday: <strong>{{ $tier->discount_wd }}%</strong></span>
                            </li>
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Diskon Weekend: <strong>{{ $tier->discount_we }}%</strong></span>
                            </li>
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Batas weekday: {{ $tier->limit_wd === null ? 'Tanpa batas (∞)' : $tier->limit_wd . 'x' }}</span>
                            </li>
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Batas weekend: {{ $tier->limit_we === null ? 'Tanpa batas (∞)' : $tier->limit_we . 'x' }}</span>
                            </li>
                            <li class="flex items-start space-x-2.5">
                                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Reset kuota diskon setiap {{ $tier->window }} hari</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-8">
                        @if($isMember && $userTier && $userTier->id === $tier->id)
                            <button disabled class="w-full py-3.5 bg-slate-100 text-slate-450 border border-slate-200 font-bold text-xs rounded-xl cursor-default text-center">
                                PAKET SAAT INI
                            </button>
                        @else
                            <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%2c%20saya%20ingin%20aktivasi%20membership%20*{{ urlencode($tier->name) }}*%20untuk%20akun%20saya%20({{ urlencode($user->email) }})" target="_blank" class="block w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl text-center shadow-sm transition">
                                UPGRADE SEKARANG
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
