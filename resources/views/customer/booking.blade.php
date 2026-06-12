@extends('layouts.app')

@section('title', 'Form Booking - Nusa Terapi Center')

@section('styles')
@endsection


@section('content')
    <form id="booking-form" action="{{ route('customer.booking') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_time" id="hidden-schedule-time" value="13:00">
        <input type="hidden" name="address" id="hidden-address" value="">
        <input type="hidden" name="payment_status" id="hidden-payment-status" value="paid">

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
                                    @foreach($services as $service)
                                        <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Durasi Terapi -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Pilih Durasi Terapi</label>
                            <div class="relative">
                                <select id="select-duration" name="duration" onchange="onDurationChange()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold appearance-none">
                                    <option value="60">60 Menit (1 Jam)</option>
                                    <option value="90">90 Menit (1.5 Jam)</option>
                                    <option value="120">120 Menit (2 Jam)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                        </div>
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
                                <div class="grid grid-cols-4 sm:grid-cols-5 gap-2.5">
                                    <button type="button" onclick="selectTime('09:00')" id="time-09:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">09:00</button>
                                    <button type="button" onclick="selectTime('10:00')" id="time-10:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">10:00</button>
                                    <button type="button" onclick="selectTime('11:00')" id="time-11:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">11:00</button>
                                    <button type="button" onclick="selectTime('12:00')" id="time-12:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">12:00</button>
                                    <button type="button" onclick="selectTime('13:00')" id="time-13:00" class="py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none">13:00</button>
                                </div>
                            </div>

                            <!-- Sesi Sore -->
                            <div class="space-y-2">
                                <span class="block text-xs font-semibold text-gray-400">Sesi Sore:</span>
                                <div class="grid grid-cols-4 sm:grid-cols-5 gap-2.5">
                                    <button type="button" onclick="selectTime('14:00')" id="time-14:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">14:00</button>
                                    <button type="button" onclick="selectTime('15:00')" id="time-15:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">15:00</button>
                                    <button type="button" onclick="selectTime('16:00')" id="time-16:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">16:00</button>
                                    <button type="button" onclick="selectTime('17:00')" id="time-17:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">17:00</button>
                                    <button type="button" onclick="selectTime('18:00')" id="time-18:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">18:00</button>
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
                            <div class="flex justify-between text-emerald-600 hidden" id="summary-discount-row">
                                <span>Potongan Member</span>
                                <span id="detail-discount-price">-Rp 0</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Biaya Admin</span>
                                <span class="text-slate-800" id="detail-admin-fee">Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span id="label-ppn">PPN ({{ $ppnPercentage }}%)</span>
                                <span class="text-slate-800" id="detail-ppn-amount">Rp 0</span>
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
    <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // Prices mapping for services
        const servicesPriceList = {
            @foreach($services as $service)
            "{{ $service->slug }}": {
                name: "{{ $service->name }}",
                default_duration_str: "{{ $service->default_duration }}",
                default_duration: {{ (int) filter_var($service->default_duration, FILTER_SANITIZE_NUMBER_INT) }},
                price_clinic: {{ $service->price_clinic }},
                price_home: {{ $service->price_home }}
            },
            @endforeach
        };

        // Active State variables
        let selectedServiceKey = "{{ $services->first() ? $services->first()->slug : 'pijat-tradisional' }}";
        let selectedTherapistName = "{{ $therapists->first() ? $therapists->first()->name : 'Adam Aryanto' }}";
        let selectedDate = "";
        let selectedTimeValue = "13:00";
        let selectedDuration = {{ $services->first() ? (int) filter_var($services->first()->default_duration, FILTER_SANITIZE_NUMBER_INT) : 90 }};
        let selectedLocationType = "home"; // home or clinic
        let selectedAddress = {!! json_encode(auth()->user()->address ?? "Jl. Slamet Riyadi No. 12, Kec. Banjarsari\nKota Solo, Jawa Tengah, 57123") !!};
        let transportFee = 20000;
        const isMember = {{ $isMember ? 'true' : 'false' }};
        const adminFee = {{ $adminFee }};
        const ppnPercentage = {{ $ppnPercentage }};
        const discountPercentageWd = {{ $discountPercentageWd ?? 0 }};
        const discountPercentageWe = {{ $discountPercentageWe ?? 0 }};
        const hasDiscountQuotaWd = {{ $hasDiscountQuotaWd ? 'true' : 'false' }};
        const hasDiscountQuotaWe = {{ $hasDiscountQuotaWe ? 'true' : 'false' }};

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
            selectedServiceKey = urlParams.get('type') || "{{ $services->first() ? $services->first()->slug : 'pijat-tradisional' }}";
            
            // Set service dropdown
            const serviceSelect = document.getElementById('select-service');
            if (serviceSelect) {
                serviceSelect.value = selectedServiceKey;
            }

            // Set default date and min date to today (local timezone)
            const dateInput = document.getElementById('input-date');
            if (dateInput) {
                const localToday = new Date();
                const year = localToday.getFullYear();
                const month = String(localToday.getMonth() + 1).padStart(2, '0');
                const day = String(localToday.getDate()).padStart(2, '0');
                const todayStr = `${year}-${month}-${day}`;
                
                dateInput.min = todayStr;
                selectedDate = todayStr;
                dateInput.value = selectedDate;
            }

            // Fill details card
            updateBookingSummary();
            updateTherapistAvailability();
        }

        // Update booking summary card
        function updateBookingSummary() {
            const serviceData = servicesPriceList[selectedServiceKey];
            
            // 1. Service Details
            document.getElementById('detail-service-name').innerText = `${serviceData.name} (${selectedDuration} Menit)`;
            document.getElementById('detail-therapist-name').innerText = selectedTherapistName;

            // 2. Schedule
            const formattedDate = formatIndoDate(selectedDate);
            document.getElementById('detail-schedule-date').innerText = `${formattedDate} • ${selectedTimeValue} WIB`;

            // 3. Location & Address
            const finalAddress = selectedLocationType === 'home' ? selectedAddress : "Klinik Utama Nusa Terapi, Solo";
            
            let baseServicePrice = 0;
            if (selectedLocationType === 'home') {
                document.getElementById('detail-location-type').innerText = "Home Service";
                document.getElementById('detail-location-address').innerText = selectedAddress;
                document.getElementById('address-input-group').classList.remove('hidden');
                transportFee = isMember ? 0 : 20000;
                baseServicePrice = serviceData.price_home;
            } else {
                document.getElementById('detail-location-type').innerText = "Datang ke Klinik";
                document.getElementById('detail-location-address').innerText = "Klinik Utama Nusa Terapi, Solo";
                document.getElementById('address-input-group').classList.add('hidden');
                transportFee = 0;
                baseServicePrice = serviceData.price_clinic;
            }
            const servicePrice = Math.round(baseServicePrice * (selectedDuration / serviceData.default_duration));
            document.getElementById('hidden-address').value = finalAddress;

            // 4. Prices Calculations
            document.getElementById('detail-service-price').innerText = formatPrice(servicePrice);
            
            if (selectedLocationType === 'home' && isMember) {
                document.getElementById('detail-transport-price').innerText = "Gratis (Member)";
            } else {
                document.getElementById('detail-transport-price').innerText = formatPrice(transportFee);
            }

            // Resolve weekday/weekend based on selected schedule date
            const dateInputVal = document.getElementById('input-date').value;
            let isWeekendSelected = false;
            if (dateInputVal) {
                const day = new Date(dateInputVal).getDay();
                isWeekendSelected = (day === 0 || day === 6); // 0 = Sunday, 6 = Saturday
            }

            const discountPercentage = isWeekendSelected ? discountPercentageWe : discountPercentageWd;
            const hasQuota = isWeekendSelected ? hasDiscountQuotaWe : hasDiscountQuotaWd;

            let appliedDiscount = 0;
            const discountRow = document.getElementById('summary-discount-row');
            if (isMember && hasQuota) {
                appliedDiscount = (servicePrice * discountPercentage) / 100;
                if (discountRow) {
                    discountRow.classList.remove('hidden');
                    document.getElementById('detail-discount-price').innerText = "-" + formatPrice(appliedDiscount);
                }
            } else {
                if (discountRow) {
                    discountRow.classList.add('hidden');
                }
            }
            
            const subtotal = servicePrice + transportFee - appliedDiscount;
            const ppnAmount = Math.round((subtotal * ppnPercentage) / 100);
            const totalPayment = subtotal + adminFee + ppnAmount;
            
            document.getElementById('detail-ppn-amount').innerText = formatPrice(ppnAmount);
            document.getElementById('detail-total-payment').innerText = formatPrice(totalPayment);
        }

        // Dropdown service event handler
        function onServiceChange() {
            selectedServiceKey = document.getElementById('select-service').value;
            const serviceData = servicesPriceList[selectedServiceKey];
            
            // Auto-select duration based on service choice
            const durationSelect = document.getElementById('select-duration');
            if (durationSelect && serviceData) {
                durationSelect.value = serviceData.default_duration;
                selectedDuration = serviceData.default_duration;
            }

            // Adjust default therapist based on service choice
            const therapistSelect = document.getElementById('select-therapist');
            if (therapistSelect) {
                let targetName = "";
                if (selectedServiceKey === 'refleksi-kaki') {
                    targetName = "Siti Aminah";
                } else if (selectedServiceKey === 'terapi-bekam') {
                    targetName = "Rizky Firmansyah";
                } else if (selectedServiceKey === 'lulur-scrub') {
                    targetName = "Diana Putri";
                } else {
                    targetName = "Adam Aryanto";
                }

                // Check if targetName option exists in the dropdown
                let optionExists = false;
                for (let i = 0; i < therapistSelect.options.length; i++) {
                    if (therapistSelect.options[i].value === targetName) {
                        optionExists = true;
                        break;
                    }
                }

                if (optionExists) {
                    therapistSelect.value = targetName;
                } else if (therapistSelect.options.length > 0) {
                    // If target doesn't exist, keep the current selected or fallback to first option
                    if (!therapistSelect.value && therapistSelect.selectedIndex < 0) {
                        therapistSelect.selectedIndex = 0;
                    }
                }
                selectedTherapistName = therapistSelect.value;
            }

            updateBookingSummary();
            updateTherapistAvailability();
        }

        // Duration selector event handler
        function onDurationChange() {
            selectedDuration = parseInt(document.getElementById('select-duration').value, 10);
            updateBookingSummary();
            updateTherapistAvailability();
        }

        // Dropdown therapist event handler
        function onTherapistChange() {
            selectedTherapistName = document.getElementById('select-therapist').value;
            updateBookingSummary();
            updateTherapistAvailability();
        }

        // Date selector event handler
        function onDateChange() {
            selectedDate = document.getElementById('input-date').value;
            updateBookingSummary();
            updateTherapistAvailability();
        }

        // Fetch booked times for selected therapist and date, disable corresponding time slots
        function updateTherapistAvailability() {
            if (!selectedTherapistName || !selectedDate) return;

            fetch(`/booking/check-availability?therapist_name=${encodeURIComponent(selectedTherapistName)}&schedule_date=${selectedDate}&duration=${selectedDuration}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const bookedTimes = data.booked_times || [];
                        const allTimes = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00"];

                        // Get current local time to disable past hours for today
                        const localToday = new Date();
                        const curHour = localToday.getHours();
                        const curMin = localToday.getMinutes();
                        
                        const year = localToday.getFullYear();
                        const month = String(localToday.getMonth() + 1).padStart(2, '0');
                        const day = String(localToday.getDate()).padStart(2, '0');
                        const todayStr = `${year}-${month}-${day}`;
                        const isToday = (selectedDate === todayStr);

                        let isCurrentlySelectedBooked = false;

                        allTimes.forEach(t => {
                            const btn = document.getElementById("time-" + t);
                            if (btn) {
                                let isPassedToday = false;
                                if (isToday) {
                                    const [slotHour, slotMin] = t.split(':').map(Number);
                                    if (slotHour < curHour || (slotHour === curHour && slotMin <= curMin)) {
                                        isPassedToday = true;
                                    }
                                }

                                if (bookedTimes.includes(t) || isPassedToday) {
                                    btn.disabled = true;
                                    btn.className = "py-2.5 rounded-lg border border-red-200 text-xs font-semibold text-red-300 bg-red-50 cursor-not-allowed transition focus:outline-none relative";
                                    
                                    if (isPassedToday) {
                                        btn.title = "Waktu sudah terlewat";
                                    } else {
                                        btn.title = "Terapis tidak tersedia (Sudah dipesan)";
                                    }

                                    if (selectedTimeValue === t) {
                                        isCurrentlySelectedBooked = true;
                                    }
                                } else {
                                    btn.disabled = false;
                                    if (selectedTimeValue === t) {
                                        btn.className = "py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none";
                                    } else {
                                        btn.className = "py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none";
                                    }
                                    btn.title = "";
                                }
                            }
                        });

                        if (isCurrentlySelectedBooked) {
                            const firstAvailable = allTimes.find(t => !bookedTimes.includes(t));
                            if (firstAvailable) {
                                selectTime(firstAvailable);
                            } else {
                                selectedTimeValue = "";
                                document.getElementById('hidden-schedule-time').value = "";
                                updateBookingSummary();
                                Swal.fire({
                                    title: 'Penuh',
                                    text: 'Terapis ini sudah penuh pesanan untuk tanggal yang Anda pilih. Silakan pilih terapis atau tanggal lain.',
                                    icon: 'info',
                                    confirmButtonColor: '#0f172a'
                                });
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error("Error checking availability:", error);
                });
        }

        // Time button select handler
        function selectTime(timeStr) {
            selectedTimeValue = timeStr;
            document.getElementById('hidden-schedule-time').value = timeStr;

            // Normalize classes for all buttons, but preserve disabled ones
            const activeTimes = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00"];
            activeTimes.forEach(t => {
                const el = document.getElementById("time-" + t);
                if (el && !el.disabled) {
                    el.className = "py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none";
                }
            });

            // Set active class for selected time button
            const activeEl = document.getElementById("time-" + timeStr);
            if (activeEl && !activeEl.disabled) {
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

        // Midtrans Integration
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.disabled = true;
            submitBtn.innerText = "Memproses...";

            // 1. Submit form data to create booking
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.booking_id) {
                    // 2. Fetch Snap Token for the new booking
                    fetch(`/riwayat-pesanan/detail/${data.booking_id}/snap-token`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(tokenRes => tokenRes.json())
                    .then(tokenData => {
                        if (tokenData.success && tokenData.snap_token) {
                            // 3. Open Midtrans Snap Payment Popup
                            window.snap.pay(tokenData.snap_token, {
                                onSuccess: function(result) {
                                    fetch(`/riwayat-pesanan/detail/${data.booking_id}/pay`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(payRes => {
                                        Swal.fire({
                                            title: 'Pembayaran Berhasil!',
                                            text: 'Terima kasih atas pesanan Anda.',
                                            icon: 'success',
                                            confirmButtonColor: '#0f172a'
                                        }).then(() => {
                                            window.location.href = "{{ route('customer.history') }}";
                                        });
                                    })
                                    .catch(err => {
                                        console.error("Error confirming payment:", err);
                                        window.location.href = "{{ route('customer.history') }}";
                                    });
                                },
                                onPending: function(result) {
                                    Swal.fire({
                                        title: 'Menunggu Pembayaran',
                                        text: 'Pesanan berhasil dibuat. Silakan selesaikan pembayaran Anda di halaman riwayat.',
                                        icon: 'info',
                                        confirmButtonColor: '#0f172a'
                                    }).then(() => {
                                        window.location.href = "{{ route('customer.history') }}";
                                    });
                                },
                                onError: function(result) {
                                    Swal.fire({
                                        title: 'Pembayaran Gagal',
                                        text: 'Anda dapat mencoba kembali di halaman riwayat pesanan.',
                                        icon: 'error',
                                        confirmButtonColor: '#0f172a'
                                    }).then(() => {
                                        window.location.href = "{{ route('customer.history') }}";
                                    });
                                },
                                onClose: function() {
                                    window.location.href = "{{ route('customer.history') }}";
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: tokenData.message || 'Gagal mendapatkan token pembayaran. Mengalihkan ke riwayat pesanan.',
                                icon: 'warning',
                                confirmButtonColor: '#0f172a'
                            }).then(() => {
                                window.location.href = "{{ route('customer.history') }}";
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal menghubungi server pembayaran. Mengalihkan ke riwayat pesanan.',
                            icon: 'error',
                            confirmButtonColor: '#0f172a'
                        }).then(() => {
                            window.location.href = "{{ route('customer.history') }}";
                        });
                    });
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalText;
                    
                    Swal.fire({
                        title: 'Validasi Gagal',
                        text: data.message || 'Gagal membuat pesanan. Silakan periksa kembali data Anda.',
                        icon: 'warning',
                        confirmButtonColor: '#0f172a'
                    });
                }
            })
            .catch(error => {
                console.error("Booking Error:", error);
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
                Swal.fire({
                    title: 'Kesalahan Koneksi',
                    text: 'Terjadi kesalahan koneksi saat memproses pesanan.',
                    icon: 'error',
                    confirmButtonColor: '#0f172a'
                });
            });
        });
    </script>
@endsection
