@extends('layouts.app')

@section('title', 'Tulis Ulasan & Rating - Nusa Terapi Center')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-6">
    
    <!-- Breadcrumbs -->
    <div class="text-xs text-gray-400 font-semibold mb-2 flex items-center gap-1.5">
        <a href="{{ route('customer.history') }}" class="hover:text-slate-700 transition">Riwayat Pesanan</a>
        <span>/</span>
        <span class="text-gray-400">{{ $booking->id }}</span>
        <span>/</span>
        <span class="text-gray-400">Beri Ulasan</span>
    </div>

    <!-- Page Title -->
    <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-8">Tulis Ulasan & Rating</h2>

    <!-- Main Card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-8 space-y-6">
        
        <!-- Booking Info Box -->
        <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-5 flex items-center space-x-4">
            <div class="w-14 h-14 bg-slate-200 border border-slate-300 rounded-lg flex items-center justify-center text-2xl flex-shrink-0 text-slate-500 shadow-sm">
                @php
                    $icon = "💆";
                    if (str_contains(strtolower($booking->service_name), 'refleksi')) $icon = "🦶";
                    elseif (str_contains(strtolower($booking->service_name), 'bekam')) $icon = "🍯";
                    elseif (str_contains(strtolower($booking->service_name), 'lulur')) $icon = "🌸";
                @endphp
                {{ $icon }}
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm md:text-base leading-snug">{{ $booking->service_name }}</h4>
                <p class="text-xs text-gray-400 mt-1 font-medium">
                    Terapis: <span class="text-slate-600 font-semibold">{{ $booking->therapist ? $booking->therapist->name : 'Tidak Memilih Terapis' }}</span>
                    <span class="mx-1.5 text-gray-300">|</span>
                    Jadwal: <span class="text-slate-600 font-semibold">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}</span>
                </p>
            </div>
        </div>

        <form action="{{ route('customer.review.store', $booking->id) }}" method="POST" class="space-y-6" id="review-form">
            @csrf
            
            <!-- Rating Section -->
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-800">Bagaimana kualitas pelayanan terapis kami?</label>
                <div class="flex items-center space-x-2">
                    <input type="hidden" name="rating" id="rating-value" value="0">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})" onmouseover="hoverRating({{ $i }})" onmouseout="resetRating()" class="star-btn text-gray-300 hover:scale-110 transition duration-150 text-4xl focus:outline-none">
                            ★
                        </button>
                    @endfor
                </div>
                <p class="text-[11px] text-gray-400 font-medium">Klik bintang untuk memberikan nilai (1-5)</p>
                @error('rating')
                    <span class="text-xs text-red-500 block mt-1 font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Review Textarea Section -->
            <div class="space-y-2">
                <label for="review" class="block text-sm font-bold text-slate-800">Tulis Ulasan Anda *</label>
                <textarea name="review" id="review" rows="5" required placeholder="Tuliskan pengalaman Anda mengenai tekanan pijatan, keramahan terapis, atau ketepatan waktu di sini..."
                          class="w-full border border-gray-200 rounded-xl p-4 text-sm text-slate-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-slate-900 transition resize-none"></textarea>
                @error('review')
                    <span class="text-xs text-red-500 block mt-1 font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('customer.history') }}" class="px-5 py-2.5 text-sm font-bold border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold bg-[#0f172a] hover:bg-slate-800 text-white rounded-xl transition shadow-md">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentRating = 0;

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('rating-value').value = rating;
        highlightStars(rating);
    }

    function hoverRating(rating) {
        highlightStars(rating);
    }

    function resetRating() {
        highlightStars(currentRating);
    }

    function highlightStars(count) {
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            if (index < count) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-amber-400');
            } else {
                star.classList.remove('text-amber-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    // Basic form validation for rating
    document.getElementById('review-form').addEventListener('submit', function(e) {
        const ratingVal = document.getElementById('rating-value').value;
        if (parseInt(ratingVal) === 0) {
            e.preventDefault();
            alert('Silakan pilih rating bintang terlebih dahulu.');
        }
    });
</script>
@endsection
