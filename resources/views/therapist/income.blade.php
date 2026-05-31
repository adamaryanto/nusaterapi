@extends('layouts.therapist')
@section('title', 'Pendapatan - Nusa Terapi Center')
@section('page_title', 'Pendapatan')

@section('content')
<div class="space-y-6">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-5">
            <p class="text-sm text-emerald-600 font-medium mb-1">Pendapatan Bulan Ini</p>
            <p class="text-3xl font-bold text-emerald-700">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <p class="text-sm text-blue-600 font-medium mb-1">Total Pendapatan</p>
            <p class="text-3xl font-bold text-blue-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="font-bold text-slate-800 text-base mb-5">Statistik 6 Bulan Terakhir</h3>
        <canvas id="monthlyChart" height="80"></canvas>
    </div>

    {{-- Recent Income Table --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-slate-800 text-base">Riwayat Sesi Selesai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Pasien</th>
                        <th class="px-5 py-3">Layanan</th>
                        <th class="px-5 py-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentBookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-600">{{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3 font-semibold text-slate-800">{{ $booking->user->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $booking->service_name }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-emerald-700">
                            Rp {{ number_format($booking->total_payment, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400">Belum ada sesi selesai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($monthlyData) !!},
                backgroundColor: '#93c5fd',
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
                    ticks: { callback: (val) => 'Rp ' + (val/1000).toLocaleString('id-ID') + 'K' }
                }
            }
        }
    });
</script>
@endsection
