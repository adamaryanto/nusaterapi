@extends('layouts.admin')

@section('title', 'Laporan & Statistik - Nusa Terapi Center')
@section('page_title', 'Laporan & Statistik')

@section('content')
    <div class="space-y-6">
        <!-- Report Navigation and Export Buttons -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-200 pb-2 space-y-4 sm:space-y-0">
            <div class="flex space-x-6 text-sm font-medium text-gray-400">
                <a href="#" class="pb-3 hover:text-slate-700 transition">Laporan Booking</a>
                <a href="#" class="pb-3 hover:text-slate-700 transition">Laporan Transaksi</a>
                <a href="#" class="pb-3 text-slate-900 font-bold border-b-2 border-slate-900 transition">Laporan Pendapatan</a>
            </div>
            <div class="flex space-x-3 text-xs font-medium">
                <button class="flex items-center border border-red-200 text-red-600 bg-red-50/50 px-3 py-2 rounded-lg hover:bg-red-100 transition shadow-sm">
                    <span class="mr-1.5 font-bold">📄</span> Export PDF
                </button>
                <button class="flex items-center border border-emerald-200 text-emerald-600 bg-emerald-50/50 px-3 py-2 rounded-lg hover:bg-emerald-100 transition shadow-sm">
                    <span class="mr-1.5 font-bold">📊</span> Export Excel
                </button>
            </div>
        </div>

        <!-- Revenue Report Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-slate-900 text-base">Rekapitulasi Pendapatan (Bulan Ini)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-200 text-xs text-gray-400 uppercase tracking-wider">
                            <th class="py-4 px-6 font-medium w-16">No</th>
                            <th class="py-4 px-6 font-medium">Periode / Tanggal</th>
                            <th class="py-4 px-6 font-medium">Total Booking Masuk</th>
                            <th class="py-4 px-6 font-medium">Booking Selesai</th>
                            <th class="py-4 px-6 font-medium">Booking Dibatalkan</th>
                            <th class="py-4 px-6 font-medium text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">1</td>
                            <td class="py-4 px-6 font-bold text-slate-900">Minggu 1 (1 - 7 Mei)</td>
                            <td class="py-4 px-6 text-gray-600">42</td>
                            <td class="py-4 px-6 text-gray-600">40</td>
                            <td class="py-4 px-6 text-gray-600">2</td>
                            <td class="py-4 px-6 font-bold text-slate-900 text-right">Rp 6.400.000</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">2</td>
                            <td class="py-4 px-6 font-bold text-slate-900">Minggu 2 (8 - 14 Mei)</td>
                            <td class="py-4 px-6 text-gray-600">55</td>
                            <td class="py-4 px-6 text-gray-600">51</td>
                            <td class="py-4 px-6 text-gray-600">4</td>
                            <td class="py-4 px-6 font-bold text-slate-900 text-right">Rp 8.150.000</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">3</td>
                            <td class="py-4 px-6 font-bold text-slate-900">Minggu 3 (15 - 21 Mei)</td>
                            <td class="py-4 px-6 text-gray-600">38</td>
                            <td class="py-4 px-6 text-gray-600">38</td>
                            <td class="py-4 px-6 text-gray-600">0</td>
                            <td class="py-4 px-6 font-bold text-slate-900 text-right">Rp 6.080.000</td>
                        </tr>
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">4</td>
                            <td class="py-4 px-6 font-bold text-slate-900">Minggu 4 (22 - 31 Mei)</td>
                            <td class="py-4 px-6 text-gray-600">60</td>
                            <td class="py-4 px-6 text-gray-600">58</td>
                            <td class="py-4 px-6 text-gray-600">2</td>
                            <td class="py-4 px-6 font-bold text-slate-900 text-right">Rp 9.280.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-blue-50/40 border-t border-gray-200 px-6 py-4 flex justify-between items-center text-sm">
                <span class="font-bold text-slate-900 uppercase tracking-wide">Total Pendapatan Bulan Ini</span>
                <span class="font-extrabold text-slate-900 text-base">Rp 29.910.000</span>
            </div>
        </div>
    </div>
@endsection
