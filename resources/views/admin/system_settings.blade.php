@extends('layouts.admin')

@section('title', 'Pengaturan Sistem - Admin Dashboard')
@section('page_title', 'Pengaturan Sistem > Biaya & PPN')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        
        <!-- Form Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-105">
                <h3 class="font-extrabold text-slate-800 text-base">Konfigurasi Biaya Tambahan & Pajak</h3>
            </div>

            <!-- Body -->
            <form action="{{ url('/admin/settings/system') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                
                <!-- Admin Fee -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-red-650 uppercase tracking-wider">Biaya Admin Fixed (Rp) *</label>
                    <input type="number" name="admin_fee" required min="0" value="{{ old('admin_fee', $adminFee) }}" placeholder="e.g. 5000" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30 font-semibold">
                    <p class="text-xs text-gray-400">Nominal biaya administrasi tetap yang dibebankan kepada pasien per transaksi pemesanan.</p>
                    @error('admin_fee')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PPN Percentage -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-red-650 uppercase tracking-wider">Pajak Pertambahan Nilai / PPN (%) *</label>
                    <input type="number" step="0.01" name="ppn_percentage" required min="0" max="100" value="{{ old('ppn_percentage', $ppnPercentage) }}" placeholder="e.g. 11" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition bg-gray-50/30 font-semibold">
                    <p class="text-xs text-gray-400">Persentase tarif PPN yang berlaku. Isikan angka saja tanpa simbol % (misal: 11 atau 12).</p>
                    @error('ppn_percentage')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Warning Alert Box -->
                <div class="bg-amber-50/50 border border-amber-200/60 rounded-xl p-4 flex items-start space-x-3 text-amber-800 text-xs font-medium leading-relaxed">
                    <span class="text-base select-none">💡</span>
                    <div>
                        <span class="font-bold">Info:</span> Perubahan nilai di atas akan langsung diterapkan secara real-time pada struk rincian pembayaran rincian pasien saat melakukan konfirmasi booking baru.
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-gray-50 bg-white transition shadow-sm text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-sm font-bold transition shadow-sm">
                        Simpan Setelan
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
