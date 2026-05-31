@extends('layouts.admin')

@section('title', 'Admin Dashboard - Nusa Terapi Center')
@section('page_title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
                <p class="text-sm text-blue-600/80 font-medium mb-2">Total Pasien</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $totalPatients }}</h3>
            </div>
            <div class="bg-amber-50 p-6 rounded-xl border border-amber-100 shadow-sm">
                <p class="text-sm text-amber-600/80 font-medium mb-2">Total Booking</p>
                <h3 class="text-3xl font-bold text-amber-500">{{ $totalBookings }}</h3>
            </div>
            <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 shadow-sm">
                <p class="text-sm text-emerald-600/80 font-medium mb-2">Total Transaksi</p>
                <h3 class="text-3xl font-bold text-emerald-500">Rp {{ number_format($totalTransactions, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-purple-50 p-6 rounded-xl border border-purple-100 shadow-sm">
                <p class="text-sm text-purple-600/80 font-medium mb-2">Total Terapis</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ $totalTherapists }}</h3>
            </div>
        </div>

        <!-- Chart and Recent Bookings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-6">Statistik Booking Mingguan</h3>
                <div class="h-64 w-full">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-slate-900 mb-6">Booking Terbaru</h3>
                <div class="space-y-5">
                    @forelse($recentBookings as $b)
                        @php
                            $statusClass = "";
                            if ($b->status === "Akan Datang") {
                                $statusClass = "bg-emerald-100 text-emerald-700 border-emerald-200";
                            } elseif ($b->status === "Selesai") {
                                $statusClass = "bg-emerald-100 text-emerald-700 border-emerald-200";
                            } elseif ($b->status === "Menunggu Pembayaran") {
                                $statusClass = "bg-amber-100 text-amber-700 border-amber-200";
                            } else {
                                $statusClass = "bg-rose-100 text-rose-700 border-rose-200";
                            }
                        @endphp
                        <div class="flex justify-between items-center border-b border-gray-50 pb-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $b->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $b->therapist ? $b->therapist->name : 'Tanpa Terapis' }} • {{ $b->location_type === 'home' ? 'Home Service' : 'Klinik' }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass }}">{{ $b->status === 'Akan Datang' ? 'Dikonfirmasi' : $b->status }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-4">Belum ada booking terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Statistik Menggunakan Chart.js
        const ctx = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Total Booking',
                    data: [35, 60, 40, 75, 100, 65, 110], 
                    backgroundColor: [
                        '#93c5fd', '#6ee7b7', '#fde047', '#c4b5fd', '#0f172a', '#f9a8d4', '#a3e635'
                    ],
                    borderRadius: 4,
                    borderSkipped: false,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { display: false, grid: { display: false } }
                }
            }
        });
    </script>
@endsection
