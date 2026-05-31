@extends('layouts.app')

@section('title', 'Form Booking - Nusa Terapi Center')

@section('styles')
@endsection


@section('content')
    <form action="{{ route('customer.booking') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_time" id="hidden-schedule-time" value="13:00">
        <input type="hidden" name="address" id="hidden-address" value="">

        <div class="max-w-7xl mx-auto py-12 px-6">
            
            <!-- Main Form & Detail Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left Side: Lengkapi Data Pesanan Anda Card (~2/3 width) -->
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 md:p-8 space-y-6">
                    
                    <!-- Card Header -->
                    <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                        <button type="button" onclick="history.back()" class="text-slate-800 hover:text-slate-600 font-bold transition text-lg">
                            ←
                        </button>
                        <h3 class="font-extrabold text-slate-800 text-lg md:text-xl">Lengkapi Data Pesanan Anda</h3>
                    </div>

                    <!-- Form Fields -->
                    <div class="space-y-5">
                        
                        <!-- Pilih Layanan -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Layanan</label>
                            <div class="relative">
                                <select id="select-service" name="service_key" onchange="onServiceChange()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold appearance-none">
                                    <option value="pijat-tradisional">Pijat Tradisional</option>
                                    <option value="refleksi-kaki">Refleksi Kaki</option>
                                    <option value="terapi-bekam">Terapi Bekam</option>
                                    <option value="lulur-scrub">Lulur & Scrub</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Terapis -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Terapis (Opsional)</label>
                            <div class="relative">
                                <select id="select-therapist" name="therapist_name" onchange="onTherapistChange()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold appearance-none">
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->name }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Jadwal Tanggal -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Jadwal Tanggal</label>
                            <input type="date" id="input-date" name="schedule_date" onchange="onDateChange()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold">
                        </div>

                        <!-- Pilih Waktu (Jam Terapi) -->
                        <div class="space-y-4">
                            <span class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Waktu (Jam Terapi)</span>
                            
                            <!-- Sesi Pagi - Siang -->
                            <div class="space-y-2">
                                <span class="block text-xs font-semibold text-gray-400">Sesi Pagi - Siang:</span>
                                <div class="grid grid-cols-4 gap-2.5">
                                    <button type="button" onclick="selectTime('09:00')" id="time-09:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">09:00</button>
                                    <button type="button" onclick="selectTime('11:00')" id="time-11:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">11:00</button>
                                    <button type="button" onclick="selectTime('13:00')" id="time-13:00" class="py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none">13:00</button>
                                    <button type="button" onclick="selectTime('15:00')" id="time-15:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">15:00</button>
                                </div>
                            </div>

                            <!-- Sesi Malam -->
                            <div class="space-y-2">
                                <span class="block text-xs font-semibold text-gray-400">Sesi Malam:</span>
                                <div class="grid grid-cols-4 gap-2.5">
                                    <button type="button" onclick="selectTime('19:00')" id="time-19:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">19:00</button>
                                    <button type="button" onclick="selectTime('20:00')" id="time-20:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">20:00</button>
                                    <button type="button" onclick="selectTime('21:00')" id="time-21:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">21:00</button>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Lokasi Terapi -->
                        <div class="space-y-3 pt-2">
                            <span class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Lokasi Terapi</span>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-semibold text-slate-700">
                                    <input type="radio" name="location_type" value="home" checked onchange="onLocationChange()"
                                        class="w-4 h-4 text-[#0f172a] focus:ring-slate-900 border-gray-300">
                                    <span>Datang ke Rumah (Home Service)</span>
                                </label>
                                <label class="flex items-center space-x-2.5 cursor-pointer text-sm font-semibold text-slate-700">
                                    <input type="radio" name="location_type" value="clinic" onchange="onLocationChange()"
                                        class="w-4 h-4 text-[#0f172a] focus:ring-slate-900 border-gray-300">
                                    <span>Datang ke Klinik</span>
                                </label>
                            </div>
                        </div>

                        <!-- Input Alamat Rumah (Hanya untuk Home Service) -->
                        <div id="address-input-group" class="space-y-1.5 pt-2">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Alamat Lengkap Rumah</label>
                            <textarea id="input-address" rows="3" oninput="onAddressInput()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold"
                                placeholder="Masukkan alamat lengkap rumah Anda...">{{ auth()->user()->address ?? '' }}</textarea>
                        </div>

                    </div>

                </div>

                <!-- Right Side: Detail Pemesanan Card (~1/3 width) -->
                <div class="lg:col-span-1 lg:sticky lg:top-24">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 md:p-8 space-y-6">
                        <h3 class="font-bold text-slate-800 text-base border-b border-gray-100 pb-3">Detail Pemesanan</h3>
                        
                        <!-- Booking details listing -->
                        <div class="space-y-4 text-xs font-semibold text-gray-500">
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Layanan:</span>
                                <span class="block text-slate-800 text-sm" id="detail-service-name">Pijat Tradisional (90 Menit)</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Terapis:</span>
                                <span class="block text-slate-800 text-sm" id="detail-therapist-name">Adam Aryanto</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Jadwal:</span>
                                <span class="block text-slate-800 text-sm" id="detail-schedule-date">Sabtu, 16 Mei 2026 • 13:00 WIB</span>
                            </div>
                            <div>
                                <span class="block text-gray-400 font-medium mb-0.5">Lokasi:</span>
                                <span class="block text-slate-800 text-sm" id="detail-location-type">Home Service</span>
                                <span class="block text-gray-500 font-medium mt-0.5" id="detail-location-address">Jl. Slamet Riyadi No. 12, Solo</span>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Prices lists details -->
                        <div class="space-y-3.5 text-xs font-semibold">
                            <div class="flex justify-between text-gray-500">
                                <span>Harga Layanan</span>
                                <span class="text-slate-800" id="detail-service-price">Rp 150.000</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Biaya Transportasi</span>
                                <span class="text-slate-800" id="detail-transport-price">Rp 20.000</span>
                            </div>
                            
                            <hr class="border-gray-100">
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-800 font-bold">Total Pembayaran</span>
                                <span class="text-slate-900 font-extrabold text-base md:text-lg" id="detail-total-payment">Rp 170.000</span>
                            </div>
                        </div>

                        <!-- Confirm button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 bg-[#0f172a] text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition shadow-md hover:shadow-lg focus:outline-none">
                                Konfirmasi & Pesan
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Prices mapping for services
        const servicesPriceList = {
            "pijat-tradisional": { name: "Pijat Tradisional (90 Menit)", price: 150000 },
            "refleksi-kaki": { name: "Refleksi Kaki (60 Menit)", price: 120000 },
            "terapi-bekam": { name: "Terapi Bekam (60 Menit)", price: 120000 },
            "lulur-scrub": { name: "Lulur & Scrub (90 Menit)", price: 180000 }
        };

        // Active State variables
        let selectedServiceKey = "pijat-tradisional";
        let selectedTherapistName = "{{ $therapists->first() ? $therapists->first()->name : 'Adam Aryanto' }}";
        let selectedDate = "2026-05-16";
        let selectedTimeValue = "13:00";
        let selectedLocationType = "home"; // home or clinic
        let selectedAddress = "{{ auth()->user()->address ?? 'Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123' }}";
        let transportFee = 20000;

        function onAddressInput() {
            selectedAddress = document.getElementById('input-address').value;
            updateBookingSummary();
        }

        function formatPrice(number) {
            return "Rp " + number.toLocaleString('id-ID');
        }

        // Format Indonesian Date formatting
        function formatIndoDate(dateStr) {
            if (!dateStr) return "-";
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;

            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            const dayName = days[date.getDay()];
            const day = date.getDate();
            const monthName = months[date.getMonth()];
            const year = date.getFullYear();

            return `${dayName}, ${day} ${monthName} ${year}`;
        }

        // Setup Initial Form State
        function initializeBookingForm() {
            // Check query strings
            const urlParams = new URLSearchParams(window.location.search);
            selectedServiceKey = urlParams.get('type') || "pijat-tradisional";
            
            // Set service dropdown
            const serviceSelect = document.getElementById('select-service');
            if (serviceSelect) {
                serviceSelect.value = selectedServiceKey;
            }

            // Set default date
            const dateInput = document.getElementById('input-date');
            if (dateInput) {
                selectedDate = "2026-05-16";
                dateInput.value = selectedDate;
            }

            // Fill details card
            updateBookingSummary();
        }

        // Update booking summary card
        function updateBookingSummary() {
            const serviceData = servicesPriceList[selectedServiceKey];
            
            // 1. Service Details
            document.getElementById('detail-service-name').innerText = serviceData.name;
            document.getElementById('detail-therapist-name').innerText = selectedTherapistName;

            // 2. Schedule
            const formattedDate = formatIndoDate(selectedDate);
            document.getElementById('detail-schedule-date').innerText = `${formattedDate} • ${selectedTimeValue} WIB`;

            // 3. Location & Address
            const finalAddress = selectedLocationType === 'home' ? selectedAddress : "Klinik Utama Nusa Terapi, Solo";
            
            if (selectedLocationType === 'home') {
                document.getElementById('detail-location-type').innerText = "Home Service";
                document.getElementById('detail-location-address').innerText = selectedAddress;
                document.getElementById('address-input-group').classList.remove('hidden');
                transportFee = 20000;
            } else {
                document.getElementById('detail-location-type').innerText = "Datang ke Klinik";
                document.getElementById('detail-location-address').innerText = "Klinik Utama Nusa Terapi, Solo";
                document.getElementById('address-input-group').classList.add('hidden');
                transportFee = 0;
            }
            document.getElementById('hidden-address').value = finalAddress;

            // 4. Prices Calculations
            document.getElementById('detail-service-price').innerText = formatPrice(serviceData.price);
            document.getElementById('detail-transport-price').innerText = formatPrice(transportFee);
            
            const totalPayment = serviceData.price + transportFee;
            document.getElementById('detail-total-payment').innerText = formatPrice(totalPayment);
        }

        // Dropdown service event handler
        function onServiceChange() {
            selectedServiceKey = document.getElementById('select-service').value;
            
            // Adjust default therapist based on service choice
            const therapistSelect = document.getElementById('select-therapist');
            if (selectedServiceKey === 'refleksi-kaki') {
                therapistSelect.value = "Siti Aminah";
            } else if (selectedServiceKey === 'terapi-bekam') {
                therapistSelect.value = "Rizky Firmansyah";
            } else if (selectedServiceKey === 'lulur-scrub') {
                therapistSelect.value = "Diana Putri";
            } else {
                therapistSelect.value = "Adam Aryanto";
            }
            selectedTherapistName = therapistSelect.value;

            updateBookingSummary();
        }

        // Dropdown therapist event handler
        function onTherapistChange() {
            selectedTherapistName = document.getElementById('select-therapist').value;
            updateBookingSummary();
        }

        // Date selector event handler
        function onDateChange() {
            selectedDate = document.getElementById('input-date').value;
            updateBookingSummary();
        }

        // Time button select handler
        function selectTime(timeStr) {
            selectedTimeValue = timeStr;
            document.getElementById('hidden-schedule-time').value = timeStr;

            // Normalize classes for all buttons
            const activeTimes = ["09:00", "11:00", "13:00", "15:00", "19:00", "20:00", "21:00"];
            activeTimes.forEach(t => {
                const el = document.getElementById("time-" + t);
                if (el) {
                    el.className = "py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none";
                }
            });

            // Set active class for selected time button
            const activeEl = document.getElementById("time-" + timeStr);
            if (activeEl) {
                activeEl.className = "py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none";
            }

            updateBookingSummary();
        }

        // Location radio button event handler
        function onLocationChange() {
            const checkedRadio = document.querySelector('input[name="location_type"]:checked');
            if (checkedRadio) {
                selectedLocationType = checkedRadio.value;
            }
            updateBookingSummary();
        }

        // Run setup
        initializeBookingForm();
    </script>
@endsection
