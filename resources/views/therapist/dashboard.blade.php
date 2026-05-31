@extends('layouts.therapist')

@section('title', 'Dashboard Terapis - Nusa Terapi Center')
@section('page_title', 'Dashboard (Terapis)')

@section('content')
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Booking Hari Ini --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <p class="text-sm text-blue-500 font-medium mb-2">Booking Hari Ini</p>
            <p class="text-4xl font-bold text-blue-600">{{ $bookingHariIni }}</p>
        </div>
        {{-- Booking Pending --}}
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-5">
            <p class="text-sm text-amber-500 font-medium mb-2">Booking Pending</p>
            <p class="text-4xl font-bold text-amber-500">{{ $bookingPending }}</p>
        </div>
        {{-- Booking Selesai --}}
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5">
            <p class="text-sm text-emerald-500 font-medium mb-2">Booking Selesai</p>
            <p class="text-4xl font-bold text-emerald-500">{{ $bookingSelesai }}</p>
        </div>
        {{-- Pendapatan Harian --}}
        <div class="bg-purple-50 border border-purple-100 rounded-xl p-5">
            <p class="text-sm text-purple-500 font-medium mb-2">Pendapatan Harian</p>
            <p class="text-2xl font-bold text-purple-600">
                Rp {{ number_format($pendapatanHarian / 1000, 0, ',', '.') }}K
            </p>
        </div>
    </div>

    {{-- Weekly Chart --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <p class="text-sm text-gray-500 font-medium mb-1">Statistik Pendapatan (Bulan ini)</p>
        <p class="text-3xl font-bold text-slate-900 mb-6">
            Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
        </p>
        <canvas id="weeklyChart" height="100"></canvas>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = ['#93c5fd','#6ee7b7','#fde68a','#c4b5fd','#1e293b','#f9a8d4','#86efac'];
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyLabels) !!},
            datasets: [{
                data: {!! json_encode($weeklyData) !!},
                backgroundColor: colors,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    border: { display: false },
                    ticks: {
                        callback: (val) => 'Rp ' + (val/1000) + 'K'
                    }
                }
            }
        }
    });
</script>
@endsection
