@extends('layouts.therapist')
@section('title', 'Jadwal Saya - Nusa Terapi Center')
@section('page_title', 'Jadwal Saya')

@section('content')
<div class="space-y-4">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Jadwal Mendatang</h3>
            <span class="text-xs text-gray-400 font-medium">{{ $upcoming->count() }} jadwal aktif</span>
        </div>

        @forelse($upcoming as $booking)
        <div class="px-6 py-4 border-b border-gray-50 hover:bg-gray-50 transition flex flex-col sm:flex-row sm:items-center gap-4">
            {{-- Date Badge --}}
            <div class="flex-shrink-0 w-16 h-16 bg-blue-50 border border-blue-100 rounded-xl flex flex-col items-center justify-center text-center">
                <span class="text-xs text-blue-400 font-medium">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('M') }}</span>
                <span class="text-2xl font-bold text-blue-600 leading-none">{{ \Carbon\Carbon::parse($booking->schedule_date)->format('d') }}</span>
                <span class="text-xs text-blue-400">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('D') }}</span>
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <p class="font-bold text-slate-800">{{ $booking->user->name }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $booking->service_name }}</p>
                <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        🕐 {{ $booking->schedule_time }} WIB
                    </span>
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        {{ $booking->location_type === 'home' ? '🏠 Home Service' : '🏥 Di Klinik' }}
                    </span>
                    <span class="text-xs font-semibold text-emerald-600">
                        Rp {{ number_format($booking->total_payment, 0, ',', '.') }}
                    </span>
                </div>
                @if($booking->location_type === 'home' && $booking->address)
                <p class="text-xs text-gray-400 mt-1 truncate max-w-sm">📍 {{ $booking->address }}</p>
                @endif
            </div>

            {{-- Action --}}
            <div class="flex-shrink-0">
                <form action="{{ route('therapist.complete', $booking->id) }}" method="POST"
                      onsubmit="return confirm('Tandai sesi ini selesai?')">
                    @csrf
                    <button type="submit"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                        ✓ Tandai Selesai
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="py-16 text-center text-gray-400">
            <div class="text-4xl mb-3">📅</div>
            <p class="font-medium">Tidak ada jadwal mendatang.</p>
            <p class="text-sm mt-1">Jadwal akan muncul saat ada booking baru.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
