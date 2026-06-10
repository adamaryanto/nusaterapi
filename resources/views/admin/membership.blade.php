@extends('layouts.admin')

@section('title', 'Manajemen Membership - Admin Dashboard')
@section('page_title', 'Manajemen Membership')

@section('content')
    <div class="space-y-8">
        
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">×</button>
            </div>
        @endif

        <!-- 1. Membership Global Parameters -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-150 bg-gray-50/50">
                <h3 class="font-bold text-slate-800 text-sm">Pengaturan Parameter Membership</h3>
                <p class="text-xs text-gray-550 mt-1">Konfigurasi batasan kuota diskon mingguan dan besaran nilai potongan harga bagi member aktif.</p>
            </div>
            <form action="{{ route('admin.membership.update') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Batas Penggunaan Potongan per Minggu <span class="text-rose-500">*</span></label>
                            <input type="number" name="membership_weekly_limit" required min="0"
                                   value="{{ $weeklyLimit }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition"
                                   placeholder="Contoh: 3">
                            <p class="text-xs text-gray-400 mt-1.5">Berapa kali potongan harga dapat digunakan oleh member dalam seminggu.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah Potongan Harga (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="membership_discount_amount" required min="0"
                                   value="{{ $discountAmount }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition"
                                   placeholder="Contoh: 15000">
                            <p class="text-xs text-gray-400 mt-1.5">Besar potongan harga per transaksi dalam Rupiah (e.g. 15000).</p>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition shadow-sm">
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Customers Membership Status Management -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-150 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Status Keanggotaan Pasien</h3>
                    <p class="text-xs text-gray-550 mt-1">Aktifkan atau nonaktifkan status keanggotaan VIP pasien secara manual.</p>
                </div>
                <!-- Simple search input -->
                <div class="relative">
                    <input type="text" id="patient-search" onkeyup="filterPatients()" placeholder="Cari nama pasien..." 
                           class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs text-slate-850 focus:outline-none focus:ring-1 focus:ring-slate-400 w-48 transition">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm text-slate-700" id="patients-table">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-150 text-slate-500 font-semibold text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-6">Nama Pasien</th>
                            <th class="py-3.5 px-6">Email</th>
                            <th class="py-3.5 px-6">No. Telepon</th>
                            <th class="py-3.5 px-6 text-center">Status Keanggotaan</th>
                            <th class="py-3.5 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50/50 transition duration-150 patient-row">
                                <td class="py-4 px-6 font-semibold text-slate-800 patient-name">{{ $customer->name }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $customer->email }}</td>
                                <td class="py-4 px-6">{{ $customer->phone ?: '—' }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if($customer->is_member)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-250">
                                            Member VIP
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                            Bukan Member
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('admin.patients.toggle_membership', $customer->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border transition duration-200 {{ $customer->is_member ? 'border-rose-200 text-rose-600 bg-rose-50/50 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 bg-emerald-50/50 hover:bg-emerald-50' }}">
                                            {{ $customer->is_member ? 'Nonaktifkan' : 'Jadikan Member' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400">
                                    Belum ada data pasien terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        function filterPatients() {
            const input = document.getElementById('patient-search');
            const filter = input.value.toLowerCase();
            const rows = document.getElementsByClassName('patient-row');

            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByClassName('patient-name')[0];
                if (nameCell) {
                    const txtValue = nameCell.textContent || nameCell.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }
    </script>
@endsection
