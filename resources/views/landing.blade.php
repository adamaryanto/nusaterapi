@extends('layouts.app')

@section('title', 'Nusa Terapi Center - Layanan Terapi & Pijat Solo')

@section('content')
    <!-- Hero Section -->
    <section class="bg-[#f0f7f4] flex flex-col-reverse md:flex-row items-center py-16 px-8 md:px-24">
        <div class="md:w-1/2 pr-0 md:pr-12 mt-8 md:mt-0">
            <h1 class="text-4xl md:text-5xl font-bold text-slate-900 leading-tight mb-6">
                {{ $settings->get('banner_headline', 'Kembalikan Kebugaran Tubuh Tanpa Perlu Keluar Rumah') }}
            </h1>
            <p class="text-gray-600 text-lg mb-8 leading-relaxed max-w-lg">
                {{ $settings->get('banner_subheadline', 'Layanan pijat dan terapi profesional di area Solo. Terapis bersertifikat siap datang ke rumah Anda (Home Service).') }}
            </p>
            <a href="{{ route('customer.booking') }}" class="bg-slate-900 text-white px-8 py-3.5 rounded-md font-semibold hover:bg-slate-800 transition shadow-lg inline-block">
                Booking Sekarang
            </a>
        </div>
        <div class="md:w-1/2 w-full">
            <div class="w-full h-80 md:h-[450px] bg-gray-200 rounded-3xl overflow-hidden shadow-2xl border-4 border-white flex items-center justify-center">
                @if($settings->get('banner_image'))
                    <img src="/{{ $settings->get('banner_image') }}" alt="Banner Nusa Terapi" class="w-full h-full object-cover">
                @else
                    <span class="text-gray-400 font-medium text-sm">📷 Upload foto banner di Manajemen Web</span>
                @endif
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang-kami" class="py-20 px-8 md:px-24 bg-white flex flex-col md:flex-row items-center gap-12 max-w-7xl mx-auto">
        <!-- Left Side: Image box -->
        <div class="w-full md:w-1/2">
            <div class="w-full h-80 md:h-[400px] bg-slate-100 rounded-3xl overflow-hidden shadow-md flex items-center justify-center border border-gray-100">
                @if($settings->get('about_image'))
                    <img src="/{{ $settings->get('about_image') }}" alt="Tentang Nusa Terapi Center" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('about_spa.png') }}" alt="Tentang Nusa Terapi Center" class="w-full h-full object-cover">
                @endif
            </div>
        </div>
        <!-- Right Side: Details -->
        <div class="w-full md:w-1/2 space-y-6">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">TENTANG KAMI</span>
            <h2 class="text-3xl font-extrabold text-slate-900 leading-tight">
                {{ $settings->get('about_title', 'Kesehatan & Relaksasi Anda Adalah Prioritas Kami') }}
            </h2>
            <p class="text-gray-600 text-sm md:text-base leading-relaxed">
                {{ $settings->get('about_description', 'Nusa Terapi Center hadir sebagai solusi perawatan tubuh terbaik dengan pijat tradisional. Dengan terapis bersertifikat dan berpengalaman, kami berkomitmen untuk melayani secara maksimal dan profesional untuk memulihkan kebugaran tubuh Anda secara optimal. Layanan Home Service memudahkan Anda menikmati relaksasi tanpa harus keluar rumah.') }}
            </p>
            <ul class="space-y-3 text-slate-700 text-sm font-semibold">
                <li class="flex items-center space-x-2.5">
                    <span class="text-slate-800 font-extrabold">✓</span>
                    <span class="text-slate-600">Terapis bersertifikat & berpengalaman</span>
                </li>
                <li class="flex items-center space-x-2.5">
                    <span class="text-slate-800 font-extrabold">✓</span>
                    <span class="text-slate-600">Layanan Home Service Seluruh Solo</span>
                </li>
                <li class="flex items-center space-x-2.5">
                    <span class="text-slate-800 font-extrabold">✓</span>
                    <span class="text-slate-600">Peralatan & Produk Higienis</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Services Section -->
    <section id="layanan" class="py-20 px-8 md:px-16 bg-white">
        <h2 class="text-3xl font-bold text-center text-slate-900 mb-12">Layanan Unggulan Kami</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-48 bg-gray-200">
                    <img src="{{ asset('traditional_massage.png') }}" alt="Pijat Tradisional" class="w-full h-full object-cover">
                </div>
                <div class="p-5 text-center">
                    <h3 class="font-bold text-base mb-1">Pijat Tradisional</h3>
                    <p class="text-xs text-gray-400">Mulai dari</p>
                    <p class="text-sm font-bold text-slate-900 mb-4">Rp 100.000</p>
                    <a href="{{ route('services.detail') }}?type=pijat-tradisional" class="block w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Detail</a>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-48 bg-slate-200 flex items-center justify-center text-3xl">🦶</div>
                <div class="p-5 text-center">
                    <h3 class="font-bold text-base mb-1">Refleksi Kaki</h3>
                    <p class="text-xs text-gray-400">Mulai dari</p>
                    <p class="text-sm font-bold text-slate-900 mb-4">Rp 80.000</p>
                    <a href="{{ route('services.detail') }}?type=refleksi-kaki" class="block w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Detail</a>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-48 bg-slate-200 flex items-center justify-center text-3xl">🍯</div>
                <div class="p-5 text-center">
                    <h3 class="font-bold text-base mb-1">Terapi Bekam</h3>
                    <p class="text-xs text-gray-400">Mulai dari</p>
                    <p class="text-sm font-bold text-slate-900 mb-4">Rp 120.000</p>
                    <a href="{{ route('services.detail') }}?type=terapi-bekam" class="block w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Detail</a>
                </div>
            </div>
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                <div class="h-48 bg-slate-200 flex items-center justify-center text-3xl">🌸</div>
                <div class="p-5 text-center">
                    <h3 class="font-bold text-base mb-1">Lulur & Scrub</h3>
                    <p class="text-xs text-gray-400">Mulai dari</p>
                    <p class="text-sm font-bold text-slate-900 mb-4">Rp 130.000</p>
                    <a href="{{ route('services.detail') }}?type=lulur-scrub" class="block w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Detail</a>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="py-20 px-8 bg-[#f0f7f4]">
        <h2 class="text-3xl font-bold text-center text-slate-900 mb-16">3 Langkah Mudah Booking</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-4xl mx-auto">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center mb-6 text-3xl border-2 border-slate-100">📋</div>
                <h3 class="font-bold text-lg">1. Pilih Layanan</h3>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center mb-6 text-3xl border-2 border-slate-100">📅</div>
                <h3 class="font-bold text-lg">2. Atur Jadwal</h3>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-white rounded-full shadow-lg flex items-center justify-center mb-6 text-3xl border-2 border-slate-100">💆</div>
                <h3 class="font-bold text-lg">3. Terapi Dimulai</h3>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimoni" class="py-20 px-8 bg-white">
        <h2 class="text-3xl font-bold text-center text-slate-900 mb-12">Apa Kata Pelanggan Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="bg-[#f9faf9] p-8 rounded-2xl border border-gray-50 shadow-sm">
                <div class="text-yellow-400 mb-4 text-xl">★★★★★</div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Pijatannya enak banget, badan langsung segar. Terapisnya ramah dan sopan."</p>
                <p class="font-bold text-slate-900 text-sm">- Siti Aminah, Solo</p>
            </div>
            <div class="bg-[#f9faf9] p-8 rounded-2xl border border-gray-50 shadow-sm">
                <div class="text-yellow-400 mb-4 text-xl">★★★★★</div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Sangat terbantu dengan home service ini. Tidak perlu macet-macetan ke tempat refleksi."</p>
                <p class="font-bold text-slate-900 text-sm">- Budi Santoso, Solo</p>
            </div>
            <div class="bg-[#f9faf9] p-8 rounded-2xl border border-gray-50 shadow-sm">
                <div class="text-yellow-400 mb-4 text-xl">★★★★★</div>
                <p class="text-gray-600 mb-6 italic leading-relaxed">"Terapi bekamnya profesional. Alat-alatnya bersih dan higienis. Top banget!"</p>
                <p class="font-bold text-slate-900 text-sm">- Ahmad Faisal, Solo</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 px-8 bg-white max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-slate-900 mb-12">Pertanyaan Umum (FAQ)</h2>
        <div class="space-y-4">
            <div class="bg-gray-100 rounded-xl overflow-hidden">
                <button onclick="toggleFaq('faq1')" class="w-full text-left px-6 py-5 font-semibold flex justify-between items-center text-slate-800">
                    <span>Apakah terapis membawa peralatan sendiri?</span>
                    <span id="icon-faq1" class="font-bold">[+]</span>
                </button>
                <div id="faq1" class="hidden px-6 pb-5 text-sm text-gray-600 faq-transition">
                    Ya, terapis kami membawa matras, minyak pijat, dan perlengkapan lainnya sesuai layanan yang dipilih.
                </div>
            </div>
            <div class="bg-gray-100 rounded-xl overflow-hidden">
                <button onclick="toggleFaq('faq2')" class="w-full text-left px-6 py-5 font-semibold flex justify-between items-center text-slate-800">
                    <span>Bagaimana cara melakukan pembayaran?</span>
                    <span id="icon-faq2" class="font-bold">[+]</span>
                </button>
                <div id="faq2" class="hidden px-6 pb-5 text-sm text-gray-600">
                    Pembayaran bisa dilakukan secara tunai langsung ke terapis atau melalui transfer bank/E-wallet setelah sesi selesai.
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function toggleFaq(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            content.classList.toggle('hidden');
            icon.innerText = content.classList.contains('hidden') ? '[+]' : '[-]';
        }
    </script>
@endsection
