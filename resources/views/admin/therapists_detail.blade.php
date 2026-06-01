@extends('layouts.admin')

@section('title', 'Detail & Pendapatan Terapis - Nusa Terapi Center')
@section('page_title')
    <div class="flex items-center space-x-2 text-sm text-gray-400">
        <a href="{{ route('admin.therapists') }}" class="hover:text-slate-700 font-medium">Manajemen Terapis</a>
        <span>&gt;</span>
        <span class="text-slate-900 font-semibold">Detail & Pendapatan</span>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- 1. Profile Summary Card -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <div class="flex items-center space-x-5">
                <div class="w-16 h-16 rounded-full overflow-hidden bg-slate-100 border border-gray-200 flex items-center justify-center shadow-inner">
                    @if($therapist->avatar_path)
                        <img src="{{ asset($therapist->avatar_path) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xl">
                            {{ strtoupper(substr($therapist->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-950">{{ $therapist->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Spesialisasi: <span class="font-medium text-slate-700">{{ $therapist->specialty }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        No HP: <span class="font-medium text-slate-700">{{ $therapist->user ? $therapist->user->phone : '—' }}</span>
                    </p>
                </div>
            </div>
            <div>
                @if($therapist->status === 'Active')
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-4 py-1.5 rounded-full border border-emerald-200 shadow-sm">Aktif</span>
                @else
                    <span class="bg-rose-100 text-rose-800 text-xs font-semibold px-4 py-1.5 rounded-full border border-rose-200 shadow-sm">Nonaktif</span>
                @endif
            </div>
        </div>

        <!-- 2. Revenue and Transactions Section -->
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 md:p-8 space-y-8">
            <!-- Section Header and Filter Buttons -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-4 space-y-4 sm:space-y-0">
                <h4 class="font-bold text-slate-900 text-lg">Laporan Pendapatan Terapis</h4>
                <div class="flex space-x-2 text-xs font-semibold">
                    <a href="?filter=today" class="px-4 py-2 rounded-lg border transition shadow-sm {{ $filter === 'today' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-gray-200 hover:bg-gray-50' }}">
                        Hari Ini
                    </a>
                    <a href="?filter=week" class="px-4 py-2 rounded-lg border transition shadow-sm {{ $filter === 'week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-gray-200 hover:bg-gray-50' }}">
                        Minggu Ini
                    </a>
                    <a href="?filter=month" class="px-4 py-2 rounded-lg border transition shadow-sm {{ $filter === 'month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-gray-200 hover:bg-gray-50' }}">
                        Bulan Ini
                    </a>
                </div>
            </div>

            <!-- Stats Highlights -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Income Card -->
                <div class="bg-gray-50 border border-gray-100 p-5 rounded-2xl flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">
                        Total Pendapatan ({{ $filter === 'today' ? 'Hari Ini' : ($filter === 'month' ? 'Bulan Ini' : 'Minggu Ini') }})
                    </span>
                    <span class="text-2xl font-black text-emerald-600 mt-2">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </span>
                </div>
                <!-- Completed Sessions Card -->
                <div class="bg-gray-50 border border-gray-100 p-5 rounded-2xl flex flex-col justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Total Sesi Selesai</span>
                    <span class="text-2xl font-black text-slate-900 mt-2">
                        {{ $totalSessions }} Sesi
                    </span>
                </div>
            </div>

            <!-- Rincian Transaksi Selesai Table -->
            <div class="space-y-4 pt-4">
                <h5 class="font-bold text-slate-800 text-base">Rincian Transaksi Selesai</h5>
                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200 text-xs text-gray-400 uppercase tracking-wider">
                                <th class="py-4 px-6 font-medium w-36">ID Transaksi</th>
                                <th class="py-4 px-6 font-medium">Tanggal Selesai</th>
                                <th class="py-4 px-6 font-medium">Nama Pasien</th>
                                <th class="py-4 px-6 font-medium">Layanan</th>
                                <th class="py-4 px-6 font-medium">Tarif Total</th>
                                <th class="py-4 px-6 font-medium">Fee Terapis</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-700">
                            @forelse($completedBookings as $b)
                                @php
                                    // Fee Terapis: 70% of service price, rounded to nearest 10,000
                                    $fee = round(($b->service_price * 0.7) / 10000) * 10000;
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                    <td class="py-4 px-6 font-mono font-bold text-slate-800">{{ $b->id }}</td>
                                    <td class="py-4 px-6 text-gray-500 font-medium">
                                        {{ \Carbon\Carbon::parse($b->schedule_date)->translatedFormat('d M Y') }}, {{ $b->schedule_time }}
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-900">{{ $b->user->name }}</td>
                                    <td class="py-4 px-6 text-gray-600 font-medium">{{ $b->service_name }}</td>
                                    <td class="py-4 px-6 text-slate-800 font-bold">
                                        Rp {{ number_format($b->service_price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-emerald-600 font-extrabold">
                                        Rp {{ number_format($fee, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        Tidak ada transaksi selesai pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
