@extends('layouts.admin')

@section('title', 'Membership Tiers - Admin Dashboard')
@section('page_title', 'Membership Tiers')

@section('content')
    <div class="space-y-8">
        
        <!-- Flash Alert -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-250 text-emerald-800 px-4 py-3 rounded-lg text-sm font-medium shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-750 font-bold">×</button>
            </div>
        @endif

        <!-- 1. Membership Tiers Table Card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-150 bg-gray-50/50 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm">List Tiers</h3>
                
                <!-- Add New Tier Button -->
                <button onclick="openAddModal()" 
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-sm flex items-center">
                    + Add New Tier
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm text-slate-700">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-150 text-slate-550 font-bold text-xs uppercase tracking-wider">
                            <th class="py-4 px-6">NAME</th>
                            <th class="py-4 px-6">PRICE</th>
                            <th class="py-4 px-6 text-center">DISC (WD/WE)</th>
                            <th class="py-4 px-6 text-center">LIMIT (WD/WE)</th>
                            <th class="py-4 px-6 text-center">WINDOW</th>
                            <th class="py-4 px-6 text-center">DURATION</th>
                            <th class="py-4 px-6 text-center">STATUS</th>
                            <th class="py-4 px-6 text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tiers as $tier)
                            <tr class="hover:bg-gray-50/30 transition duration-150">
                                <td class="py-4 px-6 font-semibold text-slate-900">{{ $tier->name }}</td>
                                <td class="py-4 px-6 font-medium text-slate-800">Rp {{ number_format($tier->price, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-center">{{ $tier->discount_wd }}% / {{ $tier->discount_we }}%</td>
                                <td class="py-4 px-6 text-center">
                                    {{ $tier->limit_wd === null ? '∞' : $tier->limit_wd . 'x' }} / 
                                    {{ $tier->limit_we === null ? '∞' : $tier->limit_we . 'x' }}
                                </td>
                                <td class="py-4 px-6 text-center">{{ $tier->window }} Days</td>
                                <td class="py-4 px-6 text-center">{{ $tier->duration }} Days</td>
                                <td class="py-4 px-6 text-center">
                                    @if($tier->status === 'active')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/50 uppercase tracking-wider">
                                            ACTIVE
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-gray-200 uppercase tracking-wider">
                                            INACTIVE
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Edit button (Teal) -->
                                        <button onclick="openEditModal({{ json_encode($tier) }})" 
                                                class="p-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg transition shadow-sm"
                                                title="Edit Tier">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 20.082a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Delete button (Red) -->
                                        <form action="{{ route('admin.membership.delete', $tier->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket membership ini?')">
                                            @csrf
                                            <button type="submit" class="p-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition shadow-sm" title="Delete Tier">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-400">
                                    Belum ada data paket membership.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- ============================================ -->
    <!-- MODAL ADD NEW TIER -->
    <!-- ============================================ -->
    <div id="addTierModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl w-full max-w-xl p-8 shadow-xl border border-gray-150 transform transition duration-300 scale-95 mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <h4 class="font-bold text-slate-900 text-lg">Add New Membership Tier</h4>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
            </div>
            
            <form action="{{ route('admin.membership.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NAME</label>
                        <input type="text" name="name" required placeholder="e.g. Silver Member" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PRICE (Rp)</label>
                        <input type="number" name="price" required placeholder="e.g. 250000" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">STATUS</label>
                        <select name="status" required 
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400 bg-white">
                            <option value="active">ACTIVE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DISC WEEKDAY (%)</label>
                        <input type="number" name="discount_wd" required min="0" max="100" placeholder="e.g. 5" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DISC WEEKEND (%)</label>
                        <input type="number" name="discount_we" required min="0" max="100" placeholder="e.g. 5" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WEEKDAY</label>
                        <input type="number" name="limit_wd" placeholder="Unlimited jika kosong" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WEEKEND</label>
                        <input type="number" name="limit_we" placeholder="Unlimited jika kosong" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WINDOW (Days)</label>
                        <input type="number" name="window" required value="7" placeholder="e.g. 7" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DURATION (Days)</label>
                        <input type="number" name="duration" required value="30" placeholder="e.g. 30" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                    <button type="button" onclick="closeAddModal()" 
                            class="px-5 py-2 border border-gray-350 text-sm font-semibold rounded-lg hover:bg-gray-50 transition text-slate-700">Cancel</button>
                    <button type="submit" 
                            class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg shadow-sm transition">Add Tier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL EDIT TIER -->
    <!-- ============================================ -->
    <div id="editTierModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl w-full max-w-xl p-8 shadow-xl border border-gray-150 transform transition duration-300 scale-95 mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <h4 class="font-bold text-slate-900 text-lg">Edit Membership Tier</h4>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-semibold">&times;</button>
            </div>
            
            <form id="edit-tier-form" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NAME</label>
                        <input type="text" name="name" id="edit-name" required 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PRICE (Rp)</label>
                        <input type="number" name="price" id="edit-price" required 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">STATUS</label>
                        <select name="status" id="edit-status" required 
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400 bg-white">
                            <option value="active">ACTIVE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DISC WEEKDAY (%)</label>
                        <input type="number" name="discount_wd" id="edit-discount-wd" required min="0" max="100" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DISC WEEKEND (%)</label>
                        <input type="number" name="discount_we" id="edit-discount-we" required min="0" max="100" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WEEKDAY</label>
                        <input type="number" name="limit_wd" id="edit-limit-wd" placeholder="Unlimited jika kosong" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WEEKEND</label>
                        <input type="number" name="limit_we" id="edit-limit-we" placeholder="Unlimited jika kosong" 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">LIMIT WINDOW (Days)</label>
                        <input type="number" name="window" id="edit-window" required 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">DURATION (Days)</label>
                        <input type="number" name="duration" id="edit-duration" required 
                               class="w-full border border-gray-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-5 py-2 border border-gray-350 text-sm font-semibold rounded-lg hover:bg-gray-50 transition text-slate-700">Cancel</button>
                    <button type="submit" 
                            class="px-5 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-lg shadow-sm transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Modal toggling functions
        function openAddModal() {
            const modal = document.getElementById('addTierModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }

        // Close add modal
        function closeAddModal() {
            const modal = document.getElementById('addTierModal');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Open edit modal
        function openEditModal(tier) {
            const modal = document.getElementById('editTierModal');
            
            document.getElementById('edit-name').value = tier.name;
            document.getElementById('edit-price').value = tier.price;
            document.getElementById('edit-status').value = tier.status;
            document.getElementById('edit-discount-wd').value = tier.discount_wd;
            document.getElementById('edit-discount-we').value = tier.discount_we;
            document.getElementById('edit-limit-wd').value = tier.limit_wd !== null ? tier.limit_wd : '';
            document.getElementById('edit-limit-we').value = tier.limit_we !== null ? tier.limit_we : '';
            document.getElementById('edit-window').value = tier.window;
            document.getElementById('edit-duration').value = tier.duration;
            
            document.getElementById('edit-tier-form').action = `/admin/membership/${tier.id}/update`;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95');
                modal.firstElementChild.classList.add('scale-100');
            }, 10);
        }

        // Close edit modal
        function closeEditModal() {
            const modal = document.getElementById('editTierModal');
            modal.firstElementChild.classList.remove('scale-100');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }
    </script>
@endsection
