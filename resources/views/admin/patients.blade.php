@extends('layouts.admin')

@section('title', 'Manajemen Pasien - Nusa Terapi Center')
@section('page_title', 'Manajemen Pasien')

@section('content')
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 text-sm font-medium flex items-center gap-2 mb-6">
            <span>✅</span> {{ session('success') }}
        </div>
    @endif

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
                        <th class="pb-3 px-4 font-medium text-center">Status Member</th>
                        <th class="pb-3 px-4 font-medium w-32 text-center">Action</th>
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
                        <td class="py-4 px-4 text-center">
                            @if($patient->is_member)
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Member</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">Bukan Member</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center">
                            <form action="{{ route('admin.patients.toggle_membership', $patient->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition duration-200 {{ $patient->is_member ? 'border-rose-200 text-rose-600 bg-rose-50/50 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 bg-emerald-50/50 hover:bg-emerald-50' }}">
                                    {{ $patient->is_member ? 'Nonaktifkan' : 'Jadikan Member' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-gray-400">
                            Belum ada data pasien terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
