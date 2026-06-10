@extends('layouts.app')

@section('title', 'Nusa Terapi Center - Layanan Terapi & Pijat Solo')

@section('content')
    <!-- Hero Section -->
    <section id="beranda" class="bg-[#f0f7f4] flex flex-col-reverse md:flex-row items-center py-16 px-8 md:px-24">
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
            @foreach($services as $service)
            @php
                // Fallback icons
                $icon = "💆";
                if (str_contains(strtolower($service->name), 'refleksi')) $icon = "🦶";
                elseif (str_contains(strtolower($service->name), 'bekam')) $icon = "🍯";
                elseif (str_contains(strtolower($service->name), 'lulur') || str_contains(strtolower($service->name), 'scrub')) $icon = "🌸";
            @endphp
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div class="h-48 bg-slate-100 border-b border-gray-100 overflow-hidden relative">
                    @if($service->image_path)
                        <img src="{{ asset($service->image_path) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-4xl bg-slate-100">
                            {{ $icon }}
                        </div>
                    @endif
                </div>
                <div class="p-5 text-center flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-base mb-1 text-slate-800">{{ $service->name }}</h3>
                        <p class="text-xs text-gray-400 mb-0.5">Mulai dari</p>
                        <p class="text-sm font-bold text-slate-900 mb-4">Rp {{ number_format(min($service->price_clinic, $service->price_home), 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('services.detail') }}?type={{ $service->slug }}" class="block w-full py-2 bg-gray-100 text-gray-600 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Detail</a>
                </div>
            </div>
            @endforeach
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

        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = {
                'beranda': document.getElementById('nav-link-beranda'),
                'layanan': document.getElementById('nav-link-layanan'),
                'tentang-kami': document.getElementById('nav-link-tentang-kami'),
                'testimoni': document.getElementById('nav-link-testimoni'),
                'faq': document.getElementById('nav-link-faq')
            };

            const activeClasses = ['text-slate-900', 'font-bold', 'border-b-2', 'border-slate-900'];
            const inactiveClasses = ['text-gray-600'];

            function activateLink(id) {
                Object.entries(navLinks).forEach(([key, link]) => {
                    if (link) {
                        if (key === id) {
                            link.classList.remove(...inactiveClasses);
                            link.classList.add(...activeClasses);
                        } else {
                            link.classList.remove(...activeClasses);
                            link.classList.add(...inactiveClasses);
                        }
                    }
                });
            }

            const observerOptions = {
                root: null,
                rootMargin: '-30% 0px -30% 0px',
                threshold: 0
            };

            const observer = new IntersectionObserver((entries) => {
                // If scroll is near top, force beranda active
                if (window.scrollY < 100) {
                    activateLink('beranda');
                    return;
                }

                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        activateLink(id);
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                if (navLinks[section.id]) {
                    observer.observe(section);
                }
            });

            // Fallback for scrolling back to the very top
            window.addEventListener('scroll', function() {
                if (window.scrollY < 100) {
                    activateLink('beranda');
                }
            });

            // Initial check based on current hash or scroll position
            const currentHash = window.location.hash.substring(1);
            if (currentHash && navLinks[currentHash]) {
                activateLink(currentHash);
            } else if (window.scrollY < 100) {
                activateLink('beranda');
            }
        });
    </script>
@endsection
