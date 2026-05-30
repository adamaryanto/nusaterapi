@extends('layouts.admin')

@section('title', 'Rating & Review - Nusa Terapi Center')
@section('page_title', 'Rating & Review')

@section('content')
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <div class="mb-6">
            <h3 class="font-bold text-slate-900 text-lg">Daftar Ulasan Pelanggan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-sm text-gray-500">
                        <th class="pb-3 px-4 font-medium w-16">No</th>
                        <th class="pb-3 px-4 font-medium w-48">Pasien</th>
                        <th class="pb-3 px-4 font-medium w-48">Terapis</th>
                        <th class="pb-3 px-4 font-medium w-36">Rating</th>
                        <th class="pb-3 px-4 font-medium">Review</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">1</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Budi Santoso</td>
                        <td class="py-4 px-4 text-gray-600">Adam Aryanto</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★★</td>
                        <td class="py-4 px-4 text-gray-500 italic">"Pijatannya sangat enak, badan jadi segar dan ringan kembali. Mantap!"</td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">2</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Siti Aminah</td>
                        <td class="py-4 px-4 text-gray-600">Diana Putri</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★<span class="text-gray-300">☆</span></td>
                        <td class="py-4 px-4 text-gray-500 italic">"Pelayanan sangat baik dan terapis datang tepat waktu ke lokasi."</td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">3</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Andi Wijaya</td>
                        <td class="py-4 px-4 text-gray-600">Rizky Firmansyah</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★★</td>
                        <td class="py-4 px-4 text-gray-500 italic">"Sangat ramah dan profesional. Sangat direkomendasikan untuk home service."</td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">4</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Rina Marlina</td>
                        <td class="py-4 px-4 text-gray-600">Siti Aminah</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★<span class="text-gray-300">☆</span></td>
                        <td class="py-4 px-4 text-gray-500 italic">"Pijatannya lumayan, tapi mungkin karena macet jadi terapis agak telat datang."</td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">5</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Joko Susilo</td>
                        <td class="py-4 px-4 text-gray-600">Adam Aryanto</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★★</td>
                        <td class="py-4 px-4 text-gray-500 italic">"Bekamnya mantap banget, langsung terasa khasiatnya di badan."</td>
                    </tr>
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">6</td>
                        <td class="py-4 px-4 font-semibold text-slate-900">Diana Putri</td>
                        <td class="py-4 px-4 text-gray-600">Rani Suryani</td>
                        <td class="py-4 px-4 text-amber-400 font-medium tracking-wider">★★★★<span class="text-gray-300">☆</span></td>
                        <td class="py-4 px-4 text-gray-500 italic">"Lulurnya wangi dan bikin rileks banget. Besok pasti langganan lagi."</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
