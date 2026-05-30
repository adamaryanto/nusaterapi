@extends('layouts.admin')

@section('title', 'Manajemen Terapis - Nusa Terapi Center')
@section('page_title', 'Manajemen Terapis')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Data Terapis</h3>
            <a href="{{ route('admin.therapists.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-800 transition shadow-sm">
                + Tambah Terapis
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="pb-3 px-4 font-medium w-16">No</th>
                        <th class="pb-3 px-4 font-medium">Nama</th>
                        <th class="pb-3 px-4 font-medium">Spesialisasi</th>
                        <th class="pb-3 px-4 font-medium">Status</th>
                        <th class="pb-3 px-4 font-medium w-48">Action</th>
                    </tr>
                </thead>
                <tbody id="therapists-list" class="text-sm text-slate-700">
                    @forelse($therapists as $index => $t)
                        @php
                            $statusClass = $t->status === 'Active' 
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                                : 'bg-rose-50 text-rose-700 border-rose-100';
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-4">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 font-semibold text-slate-900">{{ $t->name }}</td>
                            <td class="py-4 px-4 text-gray-600">{{ $t->specialty }}</td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-0.5 rounded-full border text-[10px] font-bold uppercase {{ $statusClass }}">{{ $t->status }}</span>
                            </td>
                            <td class="py-4 px-4 space-x-2 flex">
                                <a href="{{ route('admin.therapists.edit', $t->id) }}" class="border border-blue-200 bg-white px-3 py-1 rounded-md text-blue-600 hover:bg-blue-50 text-xs font-medium transition shadow-sm inline-block">Edit</a>
                                <form action="{{ route('admin.therapists.delete', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus terapis ini?')" class="inline">
                                    @csrf
                                    <button type="submit" class="border border-rose-200 bg-white px-3 py-1 rounded-md text-rose-600 hover:bg-rose-50 text-xs font-medium transition shadow-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-400">Belum ada terapis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
