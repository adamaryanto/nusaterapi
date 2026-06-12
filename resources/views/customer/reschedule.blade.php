@extends('layouts.app')

@section('title', 'Jadwal Ulang - Nusa Terapi Center')

@section('content')
    <div class="max-w-2xl mx-auto py-12 px-6 space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="text-xs text-gray-400 font-medium">
            <a href="{{ route('customer.history') }}" class="hover:text-slate-700">Riwayat Pesanan</a>
            <span class="mx-1.5">/</span>
            <a href="{{ route('customer.history.detail', $booking->id) }}" class="hover:text-slate-700">{{ $booking->id }}</a>
            <span class="mx-1.5">/</span>
            <span class="text-slate-600 font-bold">Jadwal Ulang</span>
        </nav>

        <!-- Page Title -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('customer.history.detail', $booking->id) }}" class="text-slate-800 hover:text-slate-600 font-bold transition text-lg">
                &larr;
            </a>
            <h2 class="text-xl md:text-2xl font-extrabold text-slate-900">Jadwal Ulang Pesanan</h2>
        </div>

        <!-- Current Schedule Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
            <h3 class="font-extrabold text-slate-800 text-sm border-b border-gray-100 pb-3 mb-4">Jadwal Saat Ini</h3>
            <div class="flex flex-col sm:flex-row gap-6">
                <div>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-1">Order ID</span>
                    <span class="text-slate-900 font-extrabold text-sm">{{ $booking->id }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-1">Layanan</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ $booking->service_name }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-1">Jadwal Terapi</span>
                    <span class="text-slate-800 font-semibold text-sm">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d F Y') }} - {{ $booking->schedule_time }} WIB</span>
                </div>
            </div>
            @if($booking->reschedule_count > 0)
                <div class="mt-3 text-xs text-gray-500">
                    Sudah direschedule {{ $booking->reschedule_count }}x sebelumnya.
                </div>
            @endif
        </div>

        <!-- New Schedule Form Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 space-y-6">
            <h3 class="font-extrabold text-slate-800 text-sm border-b border-gray-100 pb-3">Pilih Jadwal Baru</h3>

            <!-- Date Picker -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Tanggal Baru</label>
                <input type="date" id="new-date" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-semibold">
            </div>

            <!-- Time Picker -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide">Waktu Baru</label>
                
                <span class="block text-xs font-semibold text-gray-400">Sesi Pagi - Siang:</span>
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-2.5">
                    <button type="button" onclick="selectTime('09:00')" id="rtime-09:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">09:00</button>
                    <button type="button" onclick="selectTime('10:00')" id="rtime-10:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">10:00</button>
                    <button type="button" onclick="selectTime('11:00')" id="rtime-11:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">11:00</button>
                    <button type="button" onclick="selectTime('12:00')" id="rtime-12:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">12:00</button>
                    <button type="button" onclick="selectTime('13:00')" id="rtime-13:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">13:00</button>
                </div>
                
                <span class="block text-xs font-semibold text-gray-400 pt-2">Sesi Sore:</span>
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-2.5">
                    <button type="button" onclick="selectTime('14:00')" id="rtime-14:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">14:00</button>
                    <button type="button" onclick="selectTime('15:00')" id="rtime-15:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">15:00</button>
                    <button type="button" onclick="selectTime('16:00')" id="rtime-16:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">16:00</button>
                    <button type="button" onclick="selectTime('17:00')" id="rtime-17:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">17:00</button>
                    <button type="button" onclick="selectTime('18:00')" id="rtime-18:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">18:00</button>
                </div>
                
                <span class="block text-xs font-semibold text-gray-400 pt-2">Sesi Malam:</span>
                <div class="grid grid-cols-4 gap-2.5">
                    <button type="button" onclick="selectTime('19:00')" id="rtime-19:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">19:00</button>
                    <button type="button" onclick="selectTime('20:00')" id="rtime-20:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">20:00</button>
                    <button type="button" onclick="selectTime('21:00')" id="rtime-21:00" class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none">21:00</button>
                </div>
            </div>

            <!-- Fee Estimate Banner -->
            <div id="fee-banner" class="hidden rounded-xl px-5 py-4 text-sm font-medium border">
                <p id="fee-reason" class="text-sm font-semibold"></p>
                <p id="fee-amount" class="text-xs mt-1"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('customer.history.detail', $booking->id) }}" 
                   class="px-6 py-3 border border-gray-300 text-sm font-bold rounded-xl hover:bg-gray-50 transition text-slate-700">
                    Batal
                </a>
                <button type="button" id="btn-reschedule" onclick="submitReschedule()" disabled
                        class="px-6 py-3 bg-[#0f172a] hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                    Konfirmasi Jadwal Ulang
                </button>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        let selectedTime = null;
        const allTimes = ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];

        // Set minimum date to today (local timezone)
        const localToday = new Date();
        const year = localToday.getFullYear();
        const month = String(localToday.getMonth() + 1).padStart(2, '0');
        const day = String(localToday.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;
        document.getElementById('new-date').min = todayStr;

        function selectTime(time) {
            selectedTime = time;
            allTimes.forEach(t => {
                const btn = document.getElementById('rtime-' + t);
                if (btn && !btn.disabled) {
                    if (t === time) {
                        btn.className = 'py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none';
                    } else {
                        btn.className = 'py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none';
                    }
                }
            });
            checkFee();
        }

        function updateRescheduleTimes() {
            const selectedDate = document.getElementById('new-date').value;
            const localToday = new Date();
            const curHour = localToday.getHours();
            const curMin = localToday.getMinutes();
            const isToday = (selectedDate === todayStr);

            allTimes.forEach(t => {
                const btn = document.getElementById('rtime-' + t);
                if (btn) {
                    let isPassedToday = false;
                    if (isToday) {
                        const [slotHour, slotMin] = t.split(':').map(Number);
                        if (slotHour < curHour || (slotHour === curHour && slotMin <= curMin)) {
                            isPassedToday = true;
                        }
                    }

                    if (isPassedToday) {
                        btn.disabled = true;
                        btn.className = 'py-2.5 rounded-lg border border-red-200 text-xs font-semibold text-red-300 bg-red-50 cursor-not-allowed transition focus:outline-none';
                        btn.title = 'Waktu sudah terlewat';
                        if (selectedTime === t) {
                            selectedTime = null;
                            document.getElementById('btn-reschedule').disabled = true;
                        }
                    } else {
                        btn.disabled = false;
                        if (selectedTime === t) {
                            btn.className = 'py-2.5 rounded-lg border border-transparent text-xs font-semibold text-white bg-[#0f172a] transition focus:outline-none';
                        } else {
                            btn.className = 'py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white hover:bg-gray-50 transition focus:outline-none';
                        }
                        btn.title = '';
                    }
                }
            });
        }

        document.getElementById('new-date').addEventListener('change', function() {
            updateRescheduleTimes();
            checkFee();
        });

        function checkFee() {
            const newDate = document.getElementById('new-date').value;
            if (!newDate || !selectedTime) {
                document.getElementById('fee-banner').classList.add('hidden');
                document.getElementById('btn-reschedule').disabled = true;
                return;
            }

            fetch("{{ route('customer.booking.reschedule.check', $booking->id) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ new_date: newDate, new_time: selectedTime })
            })
            .then(r => r.json())
            .then(data => {
                const banner = document.getElementById('fee-banner');
                const reason = document.getElementById('fee-reason');
                const amount = document.getElementById('fee-amount');

                banner.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-200', 'text-emerald-800', 'bg-amber-50', 'border-amber-200', 'text-amber-800');

                if (data.is_free) {
                    banner.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
                    reason.textContent = data.reason;
                    amount.textContent = 'Biaya reschedule: Rp 0 (GRATIS)';
                } else {
                    banner.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
                    reason.textContent = data.reason;
                    amount.textContent = 'Biaya reschedule akan ditambahkan ke total pembayaran Anda.';
                }

                document.getElementById('btn-reschedule').disabled = false;
            })
            .catch(err => {
                console.error('Fee check error:', err);
            });
        }

        function submitReschedule() {
            const newDate = document.getElementById('new-date').value;
            if (!newDate || !selectedTime) {
                Swal.fire({
                    title: 'Pilih Jadwal',
                    text: 'Silakan pilih tanggal dan waktu baru.',
                    icon: 'warning',
                    confirmButtonColor: '#0f172a'
                });
                return;
            }

            Swal.fire({
                title: 'Jadwal Ulang Pesanan',
                text: 'Apakah Anda yakin ingin mengubah jadwal pesanan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah jadwal!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('btn-reschedule');
                    btn.disabled = true;
                    btn.textContent = 'Memproses...';

                    fetch("{{ route('customer.booking.reschedule.process', $booking->id) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ new_date: newDate, new_time: selectedTime })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#0f172a'
                            }).then(() => {
                                window.location.href = "{{ route('customer.history.detail', $booking->id) }}";
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message || 'Gagal mengubah jadwal.',
                                icon: 'error',
                                confirmButtonColor: '#0f172a'
                            });
                            btn.disabled = false;
                            btn.textContent = 'Konfirmasi Jadwal Ulang';
                        }
                    })
                    .catch(err => {
                        console.error('Reschedule error:', err);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonColor: '#0f172a'
                        });
                        btn.disabled = false;
                        btn.textContent = 'Konfirmasi Jadwal Ulang';
                    });
                }
            });
        }
    </script>
@endsection
