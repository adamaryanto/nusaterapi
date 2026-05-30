@extends('layouts.admin')

@section('title', 'Manajemen Pasien - Nusa Terapi Center')
@section('page_title', 'Manajemen Pasien')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Data Pasien</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="pb-3 px-4 font-medium w-16">No</th>
                        <th class="pb-3 px-4 font-medium">Nama</th>
                        <th class="pb-3 px-4 font-medium">No HP</th>
                        <th class="pb-3 px-4 font-medium">Alamat</th>
                        <th class="pb-3 px-4 font-medium">Terakhir Terapi</th>
                        <th class="pb-3 px-4 font-medium w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">1</td>
                        <td class="py-4 px-4 font-medium text-slate-900">Budi Santoso</td>
                        <td class="py-4 px-4">0812-3456-7890</td>
                        <td class="py-4 px-4 text-gray-500">Jl. Slamet Riyadi No. 12, Solo</td>
                        <td class="py-4 px-4">10 Mei 2026</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-4 py-1 rounded-md text-gray-600 hover:bg-gray-50 text-xs font-medium transition">Edit</button></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">2</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Siti Aminah</td>
                        <td class="py-4 px-4">0813-9876-5432</td>
                        <td class="py-4 px-4 text-gray-500">Jl. Adi Sucipto No. 8, Solo</td>
                        <td class="py-4 px-4">12 Mei 2026</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-3 py-1 rounded text-gray-600 hover:bg-gray-100 text-xs font-medium">Edit</button></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">3</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Andi Wijaya</td>
                        <td class="py-4 px-4">0857-1122-3344</td>
                        <td class="py-4 px-4 text-gray-500">Jl. Veteran No. 45, Solo</td>
                        <td class="py-4 px-4 text-gray-400 italic">Belum Pernah</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-3 py-1 rounded text-gray-600 hover:bg-gray-100 text-xs font-medium">Edit</button></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">4</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Rina Marlina</td>
                        <td class="py-4 px-4">0811-2233-4455</td>
                        <td class="py-4 px-4 text-gray-500">Jl. Gajah Mada No. 9, Solo</td>
                        <td class="py-4 px-4">05 Mei 2026</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-3 py-1 rounded text-gray-600 hover:bg-gray-100 text-xs font-medium">Edit</button></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">5</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Joko Susilo</td>
                        <td class="py-4 px-4">0821-5566-7788</td>
                        <td class="py-4 px-4 text-gray-500">Jl. Rajawali No. 2, Solo</td>
                        <td class="py-4 px-4">14 Mei 2026</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-3 py-1 rounded text-gray-600 hover:bg-gray-100 text-xs font-medium">Edit</button></td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">6</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Diana Putri</td>
                        <td class="py-4 px-4">0815-9988-7766</td>
                        <td class="py-4 px-4 text-gray-500">Jl. MT Haryono No. 15, Solo</td>
                        <td class="py-4 px-4">02 Mei 2026</td>
                        <td class="py-4 px-4"><button class="border border-gray-300 px-3 py-1 rounded text-gray-600 hover:bg-gray-100 text-xs font-medium">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
