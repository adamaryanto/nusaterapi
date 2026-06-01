@extends('layouts.therapist')
@section('title', 'Rating & Review - Nusa Terapi Center')
@section('page_title', 'Rating & Review Pelanggan')

@section('content')
<div class="space-y-6">
    <!-- Summary Card -->
    <div class="bg-amber-50/40 border border-amber-200/60 rounded-2xl p-6 w-full max-w-xs shadow-sm">
        <span class="text-xs text-amber-800 font-semibold uppercase tracking-wider block mb-2">Rata-rata Rating Terapis</span>
        <div class="flex items-center gap-3">
            <span class="text-amber-500 text-3xl leading-none">★</span>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-extrabold text-slate-800 tracking-tight">{{ number_format($averageRating, 1, '.', '') }}</span>
                <span class="text-gray-400 font-bold text-base">/ 5.0</span>
            </div>
        </div>
        <p class="text-[11px] text-gray-400 mt-2 font-medium">Dari total {{ $totalReviews }} Ulasan</p>
    </div>

    <!-- Reviews Table Card -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-extrabold text-slate-800 text-base md:text-lg">Daftar Ulasan Pelanggan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-[11px] text-gray-500 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4 w-48">Customer</th>
                        <th class="px-6 py-4 w-36">Rating</th>
                        <th class="px-6 py-4">Review</th>
                        <th class="px-6 py-4 w-40 text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-slate-700">
                    @forelse($reviews as $index => $review)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="px-6 py-4 text-center text-xs font-semibold text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800 text-sm">{{ $review['customer_name'] }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-0.5 text-amber-400 text-sm leading-none">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review['rating'])
                                        ★
                                    @else
                                        <span class="text-gray-200">★</span>
                                    @endif
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm leading-relaxed italic">
                            "{{ $review['comment'] }}"
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-bold text-gray-400 whitespace-nowrap">
                            {{ $review['date'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center text-gray-400">
                            <p class="text-sm font-medium">Belum ada ulasan masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
