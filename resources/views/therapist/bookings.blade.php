@extends('layouts.therapist')
@section('title', 'List Booking - Nusa Terapi Center')
@section('page_title', 'List Booking Masuk')

@section('content')

@if(session('success'))
    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h3 class="font-extrabold text-slate-800 text-base md:text-lg">Daftar Booking Pelanggan</h3>
        
        {{-- Period and Date Filter Dropdown --}}
        <form action="{{ route('therapist.bookings') }}" method="GET" id="filter-form" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 font-semibold">Filter:</span>
                <select name="filter" onchange="handleFilterChange(this.value)" class="bg-white border border-gray-200 text-xs font-semibold rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="date" {{ $filter === 'date' ? 'selected' : '' }}>Pilih Tanggal...</option>
                </select>
            </div>
            <div id="date-input-container" class="{{ $filter === 'date' ? '' : 'hidden' }}">
                <input type="date" name="date" value="{{ $date ?? '' }}" onchange="this.form.submit()" class="bg-white border border-gray-200 text-xs font-semibold rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200 text-[11px] text-gray-500 font-semibold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5 w-12 text-center">No</th>
                    <th class="px-5 py-3.5">Customer</th>
                    <th class="px-5 py-3.5">Layanan</th>
                    <th class="px-5 py-3.5">Lokasi</th>
                    <th class="px-5 py-3.5">Jadwal</th>
                    <th class="px-5 py-3.5 text-center">Status</th>
                    <th class="px-5 py-3.5 text-center w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-slate-700">
                @forelse($bookings as $index => $booking)
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="px-5 py-3.5 text-center text-xs font-medium text-gray-400">{{ $index + 1 }}</td>
                    <td class="px-5 py-3.5 font-semibold text-slate-800 text-sm">{{ $booking->user->name }}</td>
                    <td class="px-5 py-3.5 text-gray-600 text-sm">{{ $booking->service_name }}</td>
                    <td class="px-5 py-3.5 text-sm">
                        @if($booking->location_type === 'home')
                            <span class="text-slate-700 font-medium block leading-snug">{{ $booking->address }}</span>
                            <span class="text-[11px] text-gray-400">Home Service</span>
                        @else
                            <span class="text-slate-700 font-medium block">Klinik</span>
                            <span class="text-[11px] text-gray-400">Datang ke Tempat</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-sm text-gray-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('d M Y') }}<br>
                        <span class="text-[11px] text-gray-400">{{ $booking->schedule_time }} WIB</span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($booking->status === 'Pending')
                            <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-700 text-[11px] font-semibold rounded-full border border-amber-200">Pending</span>
                        @elseif($booking->status === 'Akan Datang')
                            <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[11px] font-semibold rounded-full border border-emerald-200">Dikonfirmasi</span>
                        @elseif($booking->status === 'Dalam Perjalanan')
                            <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 text-[11px] font-semibold rounded-full border border-blue-200">Dalam Perjalanan</span>
                        @elseif($booking->status === 'Sampai Tujuan')
                            <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[11px] font-semibold rounded-full border border-indigo-200">Sampai Tujuan</span>
                        @elseif($booking->status === 'Dibatalkan')
                            <span class="inline-block px-2.5 py-1 bg-rose-50 text-rose-700 text-[11px] font-semibold rounded-full border border-rose-200">Dibatalkan</span>
                        @elseif($booking->status === 'Selesai')
                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 text-[11px] font-semibold rounded-full border border-slate-200">Selesai</span>
                        @else
                            <span class="inline-block px-2.5 py-1 bg-gray-50 text-gray-600 text-[11px] font-semibold rounded-full border border-gray-200">{{ $booking->status }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($booking->status === 'Akan Datang')
                            @if($booking->location_type === 'home')
                                <form action="{{ route('therapist.start_journey', $booking->id) }}" method="POST"
                                      onsubmit="return confirm('Mulai perjalanan menuju lokasi customer?')">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                        Mulai Perjalanan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('therapist.complete', $booking->id) }}" method="POST"
                                      onsubmit="return confirm('Tandai booking ini sebagai Selesai?')">
                                    @csrf
                                    <button type="submit"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                        Selesaikan
                                    </button>
                                </form>
                            @endif
                        @elseif($booking->status === 'Dalam Perjalanan')
                            <form action="{{ route('therapist.arrive', $booking->id) }}" method="POST"
                                  onsubmit="return confirm('Konfirmasi bahwa Anda sudah sampai di lokasi?')">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    Sudah Sampai
                                </button>
                            </form>
                        @elseif($booking->status === 'Sampai Tujuan')
                            <form action="{{ route('therapist.complete', $booking->id) }}" method="POST"
                                  onsubmit="return confirm('Tandai booking ini sebagai Selesai?')">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
                                    Selesaikan
                                </button>
                            </form>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center text-gray-400">
                        <p class="text-sm font-medium">Belum ada booking masuk.</p>
                        <p class="text-xs text-gray-300 mt-1">Booking baru akan tampil di sini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function handleFilterChange(val) {
        const dateContainer = document.getElementById('date-input-container');
        if (val === 'date') {
            dateContainer.classList.remove('hidden');
        } else {
            dateContainer.classList.add('hidden');
            document.getElementById('filter-form').submit();
        }
    }
</script>
@endsection
