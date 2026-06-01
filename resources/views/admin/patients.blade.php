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
                        <th class="pb-3 px-4 font-medium">Jenis Kelamin</th>
                        <th class="pb-3 px-4 font-medium">Email</th>
                        <th class="pb-3 px-4 font-medium">No HP</th>
                        <th class="pb-3 px-4 font-medium">Alamat</th>
                        <th class="pb-3 px-4 font-medium">Terakhir Terapi</th>
                        <th class="pb-3 px-4 font-medium w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($patients as $index => $patient)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">{{ $index + 1 }}</td>
                        <td class="py-4 px-4 font-medium text-slate-900">{{ $patient->name }}</td>
                        <td class="py-4 px-4 text-slate-650 font-medium">{{ $patient->gender ?: '—' }}</td>
                        <td class="py-4 px-4 text-gray-500 font-medium">{{ $patient->email }}</td>
                        <td class="py-4 px-4">{{ $patient->phone ?: '—' }}</td>
                        <td class="py-4 px-4 text-gray-500">{{ $patient->address ?: '—' }}</td>
                        <td class="py-4 px-4">
                            @if($patient->latest_therapy === 'Belum Pernah')
                                <span class="text-gray-400 italic">Belum Pernah</span>
                            @else
                                {{ $patient->latest_therapy }}
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-gray-300 text-xs">—</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-400">
                            Belum ada data pasien terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
