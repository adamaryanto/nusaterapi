@extends('layouts.app')

@php
    $services = \App\Models\Service::active()->get();
@endphp

@section('title', 'Detail Layanan - Nusa Terapi Center')

@section('content')
    <div class="max-w-7xl mx-auto py-8 px-6">
        
        <!-- Breadcrumbs -->
        <nav class="text-xs text-gray-400 font-medium mb-6">
            <a href="{{ route('landing') }}" class="hover:text-slate-700">Beranda</a>
            <span class="mx-1.5">/</span>
            <span class="hover:text-slate-700">Layanan</span>
            <span class="mx-1.5">/</span>
            <span class="text-slate-600 font-bold" id="breadcrumb-service-name">Pijat Tradisional</span>
        </nav>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Side: Large Image, Service Details, Benefits, and Therapists (~2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Service Image Container -->
                <div id="service-image-container" class="w-full h-80 md:h-[450px] bg-slate-100 rounded-3xl overflow-hidden shadow-md border border-gray-100 flex items-center justify-center relative">
                    <!-- Dynamic image or fallback emoji will be injected here -->
                </div>

                <!-- Service Title & Description -->
                <div class="space-y-4">
                    <h1 class="text-3xl font-extrabold text-slate-900" id="service-title">Pijat Tradisional (Full Body)</h1>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed" id="service-description">
                        Pijat tradisional Nusantara yang fokus pada penekanan saraf dan otot kaku. Menggunakan minyak zaitun hangat dan teknik urut untuk melancarkan darah dan meredakan kelelahan.
                    </p>
                </div>

                <!-- Benefits Section -->
                <div class="bg-[#f8faf9] border border-gray-100 rounded-2xl p-6 md:p-8 space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Manfaat Terapi:</h3>
                    <ol class="space-y-2.5 text-slate-600 text-sm font-medium" id="service-benefits">
                        <!-- Dynamic list items injected here -->
                    </ol>
                </div>

                <!-- Expert Therapists Section -->
                <div class="space-y-4">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Terapis Ahli <span id="therapist-service-title">Pijat Tradisional</span></h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4" id="therapists-list">
                        <!-- Dynamic therapist cards injected here -->
                    </div>
                </div>

            </div>

            <!-- Right Side: Sidebar Price Estimator Card (~1/3 width) -->
            <div class="lg:col-span-1 lg:sticky lg:top-24">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8 space-y-6">
                    <h3 class="font-bold text-slate-800 text-base border-b border-gray-100 pb-3">Atur Durasi & Cek Harga</h3>
                    
                    <!-- Duration Selector buttons -->
                    <div class="space-y-3">
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Pilih Durasi:</span>
                        <div class="grid grid-cols-3 gap-2">
                            <button onclick="selectDuration('60m')" id="btn-60m" class="py-2.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">
                                60 Menit
                            </button>
                            <button onclick="selectDuration('90m')" id="btn-90m" class="py-2.5 rounded-lg border border-transparent text-xs font-bold text-white bg-[#0f172a] transition focus:outline-none">
                                90 Menit
                            </button>
                            <button onclick="selectDuration('120m')" id="btn-120m" class="py-2.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">
                                120 Menit
                            </button>
                        </div>
                    </div>

                    <!-- Price Output -->
                    <div class="space-y-1.5 border-t border-gray-100 pt-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Estimasi Biaya:</span>
                        <span class="block text-slate-900 font-extrabold text-2xl md:text-3xl" id="estimated-price">Rp 150.000</span>
                        <span class="block text-[10px] text-gray-400 font-medium italic">*Belum termasuk biaya transportasi untuk Home Service.</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 pt-2">
                        <button onclick="handleBooking()" class="w-full py-3.5 bg-[#0f172a] text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-md hover:shadow-lg focus:outline-none">
                            Booking Sekarang
                        </button>
                        <a href="https://wa.me/6281234567890?text=Halo%20Nusa%20Terapi%20Center,%20saya%20ingin%20tanya%20mengenai%20layanan" target="_blank" class="block w-full py-3.5 bg-[#e6faf2] text-[#00b074] border border-[#d2f7e8] rounded-xl text-sm font-bold hover:bg-[#dbf8ed] transition text-center focus:outline-none">
                            Tanya Admin (WhatsApp)
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Database of services matching mockup requirements
        const servicesDb = {
            @foreach($services as $service)
            @php
                // Benefits fallback mapping
                $manfaat = ["1. Menghilangkan pegal linu dan nyeri otot", "2. Membantu relaksasi pikiran", "3. Melancarkan sirkulasi oksigen", "4. Meningkatkan kualitas tidur"];
                if (str_contains(strtolower($service->name), 'refleksi')) {
                    $manfaat = ["1. Meredakan pegal kaki dan pegal tubuh", "2. Melancarkan peredaran darah", "3. Mengurangi ketegangan saraf", "4. Menyeimbangkan sistem energi tubuh"];
                } elseif (str_contains(strtolower($service->name), 'bekam')) {
                    $manfaat = ["1. Membuang racun dan darah kotor", "2. Meringankan migrain dan pegal pundak", "3. Meningkatkan imunitas tubuh", "4. Melancarkan regenerasi sel darah baru"];
                } elseif (str_contains(strtolower($service->name), 'lulur') || str_contains(strtolower($service->name), 'scrub')) {
                    $manfaat = ["1. Mengangkat sel kulit mati", "2. Mencerahkan dan menghaluskan kulit", "3. Memberikan efek relaksasi yang menyegarkan", "4. Menutrisi kulit tubuh secara mendalam"];
                }
                
                // Image path fallback
                $img = $service->image_path ? asset($service->image_path) : '';
                if (!$img) {
                    if (str_contains(strtolower($service->name), 'refleksi')) $img = "🦶";
                    elseif (str_contains(strtolower($service->name), 'bekam')) $img = "🍯";
                    elseif (str_contains(strtolower($service->name), 'lulur') || str_contains(strtolower($service->name), 'scrub')) $img = "🌸";
                }
            @endphp
            "{{ $service->slug }}": {
                title: "{{ $service->name }}",
                breadcrumb: "{{ $service->name }}",
                image: "{{ $img }}",
                description: "{{ addslashes($service->description ?? '') }}",
                manfaat: {!! json_encode($manfaat) !!},
                terapis: ["Siti Aminah", "Adam Aryanto", "Rizky Firmansyah", "Diana Putri"],
                prices: {
                    "60m": {{ $service->price_clinic }},
                    "90m": {{ $service->price_home }},
                    "120m": {{ (int)($service->price_home * 1.3) }}
                }
            },
            @endforeach
        };

        // Active state selectors
        let currentType = "{{ $services->first() ? $services->first()->slug : 'pijat-tradisional' }}";
        let currentDuration = "90m";
        let service = null;

        function formatPrice(number) {
            return "Rp " + number.toLocaleString('id-ID');
        }

        function loadServiceDetail() {
            const urlParams = new URLSearchParams(window.location.search);
            currentType = urlParams.get('type') || "pijat-tradisional";

            // Fallback if key not found
            service = servicesDb[currentType] || servicesDb["pijat-tradisional"];

            // Breadcrumb
            document.getElementById('breadcrumb-service-name').innerText = service.breadcrumb;

            // Image load
            const imgContainer = document.getElementById('service-image-container');
            imgContainer.innerHTML = "";
            if (service.image.startsWith("http") || service.image.startsWith("/") || service.image.includes(".png")) {
                imgContainer.innerHTML = `<img src="${service.image}" alt="${service.title}" class="w-full h-full object-cover rounded-3xl">`;
            } else {
                imgContainer.innerHTML = `
                    <div class="text-8xl w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-100 to-slate-200">
                        ${service.image}
                    </div>
                `;
            }

            // Text titles & details
            document.getElementById('service-title').innerText = service.title;
            document.getElementById('service-description').innerText = service.description;
            document.getElementById('therapist-service-title').innerText = service.breadcrumb;

            // Benefits list load
            const benefitsContainer = document.getElementById('service-benefits');
            benefitsContainer.innerHTML = "";
            service.manfaat.forEach(item => {
                benefitsContainer.insertAdjacentHTML('beforeend', `<li class="py-1">${item}</li>`);
            });

            // Therapists list grid load
            const therapistsContainer = document.getElementById('therapists-list');
            therapistsContainer.innerHTML = "";
            service.terapis.forEach(name => {
                therapistsContainer.insertAdjacentHTML('beforeend', `
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 text-center hover:shadow transition duration-300">
                        <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 mx-auto mb-2 text-xs">
                            ${name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()}
                        </div>
                        <span class="block text-xs font-extrabold text-slate-800 truncate">${name}</span>
                    </div>
                `);
            });

            // Update prices
            selectDuration(currentDuration);
        }

        function selectDuration(durationKey) {
            currentDuration = durationKey;

            // Reset buttons styling
            const keys = ["60m", "90m", "120m"];
            keys.forEach(k => {
                const el = document.getElementById("btn-" + k);
                if (el) {
                    el.className = "py-2.5 rounded-lg border border-gray-300 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none";
                }
            });

            // Activate current duration button
            const activeEl = document.getElementById("btn-" + durationKey);
            if (activeEl) {
                activeEl.className = "py-2.5 rounded-lg border border-transparent text-xs font-bold text-white bg-[#0f172a] transition focus:outline-none";
            }

            // Estimate price rendering
            const price = service.prices[durationKey];
            document.getElementById('estimated-price').innerText = formatPrice(price);
        }

        function handleBooking() {
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            if (!isLoggedIn) {
                alert("Silakan login terlebih dahulu untuk melakukan pemesanan!");
                window.location.href = "{{ route('login') }}";
                return;
            }
            
            // Redirect directly to checkout booking form page passing key parameters
            window.location.href = "{{ route('customer.booking') }}?type=" + currentType;
        }

        // Run load on setup
        loadServiceDetail();
    </script>
@endsection
