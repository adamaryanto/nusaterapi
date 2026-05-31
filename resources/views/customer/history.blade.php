@extends('layouts.app')

@section('title', 'Riwayat Pesanan Anda - Nusa Terapi Center')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        
        <!-- Page Title -->
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-6">Riwayat Pesanan Anda</h2>
        
        <!-- Navigation Tabs -->
        <div class="flex border-b border-gray-100 text-sm font-semibold text-gray-400 overflow-x-auto space-x-8 mb-8 scrollbar-none">
            <button onclick="changeTab('Semua')" id="tab-Semua" class="py-3 border-b-2 border-slate-900 text-slate-900 focus:outline-none whitespace-nowrap transition-all font-bold">Semua</button>
            <button onclick="changeTab('Menunggu Pembayaran')" id="tab-Menunggu_Pembayaran" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Menunggu Pembayaran</button>
            <button onclick="changeTab('Akan Datang')" id="tab-Akan_Datang" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Dikonfirmasi</button>
            <button onclick="changeTab('Selesai')" id="tab-Selesai" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Selesai</button>
            <button onclick="changeTab('Dibatalkan')" id="tab-Dibatalkan" class="py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all">Dibatalkan</button>
        </div>

        <!-- Orders List Container -->
        <div id="orders-list" class="space-y-6">
            @forelse($bookings as $booking)
                @php
                    // Dynamic Badge Style
                    $badgeClass = "";
                    if ($booking->status === "Akan Datang") {
                        $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
                    } elseif ($booking->status === "Selesai") {
                        $badgeClass = "bg-emerald-50 text-emerald-700 border-emerald-200";
                    } elseif ($booking->status === "Menunggu Pembayaran") {
                        $badgeClass = "bg-amber-50 text-amber-700 border-amber-200";
                    } else {
                        $badgeClass = "bg-rose-50 text-rose-700 border-rose-200";
                    }

                    // Dynamic Icons
                    $icon = "💆";
                    if (str_contains(strtolower($booking->service_name), 'refleksi')) $icon = "🦶";
                    elseif (str_contains(strtolower($booking->service_name), 'bekam')) $icon = "🍯";
                    elseif (str_contains(strtolower($booking->service_name), 'lulur')) $icon = "🌸";

                    // Dynamic Action Buttons
                    $actionText = "Lihat Detail";
                    $actionClass = "bg-[#0f172a] text-white hover:bg-slate-800";
                    if ($booking->status === 'Selesai') {
                        $actionText = "Beri Ulasan";
                        $actionClass = "bg-white border border-gray-300 text-gray-700 hover:bg-gray-50";
                    } elseif ($booking->status === 'Dibatalkan') {
                        $actionText = "Pesan Lagi";
                        $actionClass = "bg-white border border-gray-300 text-gray-700 hover:bg-gray-50";
                    }
                @endphp

                <div class="booking-card bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 hover:shadow-md transition duration-300" data-status="{{ $booking->status }}">
                    <!-- Top Header Section of Card -->
                    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-5">
                        <div class="flex items-center space-x-3 text-xs font-semibold">
                            <span class="px-2.5 py-1 rounded-md border text-[10px] uppercase tracking-wider font-bold {{ $badgeClass }}">{{ $booking->status === 'Akan Datang' ? 'Dikonfirmasi' : $booking->status }}</span>
                            <span class="text-gray-400">{{ $booking->id }}</span>
                        </div>
                        
                        <div>
                            @if($actionText === "Lihat Detail" || $booking->status === 'Dibatalkan')
                                <a href="{{ route('customer.history.detail', $booking->id) }}" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm {{ $actionClass }} inline-block">
                                    {{ $actionText }}
                                </a>
                            @else
                                <button onclick="handleAction('{{ $booking->id }}', '{{ $actionText }}')" class="px-4 py-2 text-xs font-bold rounded-lg transition shadow-sm {{ $actionClass }}">
                                    {{ $actionText }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body Content -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center space-x-4">
                            <!-- Square Placeholder Image -->
                            <div class="w-16 h-16 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center text-3xl flex-shrink-0 shadow-inner">
                                {{ $icon }}
                            </div>
                            <div class="space-y-1">
                                <h4 class="font-bold text-slate-800 text-sm md:text-base">
                                    <a href="{{ route('customer.history.detail', $booking->id) }}" class="hover:underline hover:text-slate-600 transition">{{ $booking->service_name }}</a>
                                </h4>
                                <p class="text-xs text-gray-500 font-medium">
                                    Terapis: <span class="text-slate-700 font-semibold">{{ $booking->therapist ? $booking->therapist->name : 'Tidak Memilih Terapis' }}</span> 
                                    <span class="mx-1 text-gray-300">|</span> 
                                    Jadwal: <span class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }} • {{ $booking->schedule_time }} WIB</span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-left sm:text-right w-full sm:w-auto">
                            <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold">Total Belanja</span>
                            <span class="text-slate-900 font-extrabold text-base md:text-lg">Rp {{ number_format($booking->total_payment, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-12 text-center text-gray-500">
                    <span class="text-4xl block mb-3">📦</span>
                    <p class="font-medium text-slate-600">Belum ada pesanan</p>
                    <p class="text-xs text-gray-400 mt-1">Anda tidak memiliki riwayat pesanan.</p>
                </div>
            @endforelse

            <!-- Client Filter Empty State -->
            <div id="empty-state" class="bg-white border border-gray-100 rounded-2xl shadow-sm p-12 text-center text-gray-500 hidden">
                <span class="text-4xl block mb-3">📦</span>
                <p class="font-medium text-slate-600">Belum ada pesanan</p>
                <p class="text-xs text-gray-400 mt-1">Anda tidak memiliki riwayat pesanan untuk status ini.</p>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        let currentActiveTab = "Semua";

        function changeTab(tabName) {
            currentActiveTab = tabName;
            
            // Normalize tab element IDs for element lookup
            const targetId = "tab-" + tabName.replace(" ", "_");
            
            // Reset all tab styling
            const tabs = ["Semua", "Menunggu_Pembayaran", "Akan_Datang", "Selesai", "Dibatalkan"];
            tabs.forEach(t => {
                const el = document.getElementById("tab-" + t);
                if (el) {
                    el.className = "py-3 border-b-2 border-transparent hover:text-slate-800 focus:outline-none whitespace-nowrap transition-all";
                }
            });

            // Activate current tab styling
            const activeEl = document.getElementById(targetId);
            if (activeEl) {
                activeEl.className = "py-3 border-b-2 border-slate-900 text-slate-900 font-bold focus:outline-none whitespace-nowrap transition-all";
            }

            // Filter booking cards client side
            const cards = document.querySelectorAll('.booking-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                if (tabName === "Semua" || cardStatus.toLowerCase() === tabName.toLowerCase()) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const emptyState = document.getElementById('empty-state');
            if (visibleCount === 0 && cards.length > 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function handleAction(orderId, actionType) {
            if (actionType === "Beri Ulasan") {
                const ulasan = prompt(`Beri penilaian untuk pesanan ${orderId} (1-5):`);
                if (ulasan) {
                    alert(`Terima kasih atas ulasan bintang ${ulasan} Anda!`);
                }
            } else if (actionType === "Pesan Lagi") {
                window.location.href = "{{ route('customer.booking') }}";
            }
        }
    </script>
@endsection
