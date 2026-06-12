@extends('layouts.admin')

@section('title', 'Manajemen Web - Nusa Terapi Center')
@section('page_title', 'Manajemen Web > Pengaturan Konten')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">



    {{-- ===================================== --}}
    {{-- 1. BANNER UTAMA --}}
    {{-- ===================================== --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-slate-800 text-base">1. Banner Utama (Hero Section)</h3>
        </div>
        <form action="{{ route('admin.web_management.banner') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 flex flex-col lg:flex-row gap-6">
                {{-- Preview Foto --}}
                <div class="flex-shrink-0">
                    <div class="w-64 h-40 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden relative group cursor-pointer"
                         onclick="document.getElementById('banner_image_input').click()">
                        @if($settings->get('banner_image'))
                            <img src="/{{ $settings->get('banner_image') }}" id="banner_preview"
                                 class="w-full h-full object-cover" alt="Banner">
                        @else
                            <img src="" id="banner_preview" class="w-full h-full object-cover hidden" alt="Banner">
                        @endif
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-2xl">📷</span>
                            <span class="text-white text-xs font-semibold mt-1">Ubah Foto</span>
                        </div>
                        @if(!$settings->get('banner_image'))
                        <div id="banner_placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                            <span class="text-3xl">🖼️</span>
                            <span class="text-xs mt-1 font-medium">Klik untuk upload foto</span>
                        </div>
                        @endif
                    </div>
                    <input type="file" id="banner_image_input" name="banner_image" accept="image/*" class="hidden"
                           onchange="previewImage(this, 'banner_preview', 'banner_placeholder')">
                    <p class="text-xs text-gray-400 mt-2 text-center">Max 3MB · JPG, PNG, WebP</p>
                </div>

                {{-- Form Fields --}}
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teks Headline</label>
                        <input type="text" name="banner_headline"
                               value="{{ $settings->get('banner_headline', 'Kembalikan Kebugaran Tubuh Tanpa Perlu Keluar Rumah') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition"
                               placeholder="Masukkan teks headline banner...">
                        @error('banner_headline') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Teks Sub-Headline</label>
                        <input type="text" name="banner_subheadline"
                               value="{{ $settings->get('banner_subheadline', 'Layanan pijat & terapi profesional langsung ke rumah Anda') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition"
                               placeholder="Masukkan sub-headline...">
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition shadow-sm">
                            Simpan Banner
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ===================================== --}}
    {{-- 2. TENTANG KAMI --}}
    {{-- ===================================== --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-slate-800 text-base">2. Bagian Tentang Kami</h3>
        </div>
        <form action="{{ route('admin.web_management.about') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 flex flex-col lg:flex-row gap-6">
                {{-- Preview Foto --}}
                <div class="flex-shrink-0">
                    <div class="w-64 h-40 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden relative group cursor-pointer"
                         onclick="document.getElementById('about_image_input').click()">
                        @if($settings->get('about_image'))
                            <img src="/{{ $settings->get('about_image') }}" id="about_preview"
                                 class="w-full h-full object-cover" alt="About">
                        @else
                            <img src="" id="about_preview" class="w-full h-full object-cover hidden" alt="About">
                        @endif
                        <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <span class="text-white text-2xl">📷</span>
                            <span class="text-white text-xs font-semibold mt-1">Ubah Foto</span>
                        </div>
                        @if(!$settings->get('about_image'))
                        <div id="about_placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400">
                            <span class="text-3xl">🖼️</span>
                            <span class="text-xs mt-1 font-medium">Klik untuk upload foto</span>
                        </div>
                        @endif
                    </div>
                    <input type="file" id="about_image_input" name="about_image" accept="image/*" class="hidden"
                           onchange="previewImage(this, 'about_preview', 'about_placeholder')">
                    <p class="text-xs text-gray-400 mt-2 text-center">Max 3MB · JPG, PNG, WebP</p>
                </div>

                {{-- Form Fields --}}
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul (Headline)</label>
                        <input type="text" name="about_title"
                               value="{{ $settings->get('about_title', 'Kesehatan & Relaksasi Anda Adalah Prioritas Kami') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition"
                               placeholder="Masukkan judul bagian Tentang Kami...">
                        @error('about_title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi / Teks Paragraf</label>
                        <textarea name="about_description" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 transition resize-none"
                                  placeholder="Masukkan deskripsi tentang klinik...">{{ $settings->get('about_description', 'Nusa Terapi Center hadir di Solo Raya untuk memberikan layanan pijat dan terapi profesional. Dengan terapis bersertifikat...') }}</textarea>
                        @error('about_description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition shadow-sm">
                            Simpan Teks
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ===================================== --}}
    {{-- 3. KATALOG LAYANAN --}}
    {{-- ===================================== --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">3. Katalog Layanan & Harga</h3>
            <button onclick="openModal('modal-add-service')"
                    class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition shadow-sm flex items-center gap-2">
                <span>+</span> Tambah Layanan Baru
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                        <th class="px-4 py-3 w-20">Foto</th>
                        <th class="px-4 py-3">Nama Layanan</th>
                        <th class="px-4 py-3">Durasi</th>
                        <th class="px-4 py-3">Harga Klinik</th>
                        <th class="px-4 py-3">Harga Home</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                @if($service->image_path)
                                    <img src="/{{ $service->image_path }}" class="w-full h-full object-cover" alt="{{ $service->name }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 text-xl">💆</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-800">
                            {{ $service->name }}
                            @if($service->description)
                                <p class="text-xs text-gray-400 font-normal mt-0.5">{{ Str::limit($service->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $service->default_duration }}</td>
                        <td class="px-4 py-3 text-slate-800 font-medium">Rp {{ number_format($service->price_clinic, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-slate-800 font-medium">Rp {{ number_format($service->price_home, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            @if($service->status === 'Active')
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        class="btn-edit-service border border-gray-300 text-gray-600 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-medium transition"
                                        data-id="{{ $service->id }}"
                                        data-name="{{ $service->name }}"
                                        data-duration="{{ $service->default_duration }}"
                                        data-price-clinic="{{ $service->price_clinic }}"
                                        data-price-home="{{ $service->price_home }}"
                                        data-description="{{ $service->description ?? '' }}"
                                        data-status="{{ $service->status }}">
                                    ✏️ Edit
                                </button>
                                <form action="{{ route('admin.web_management.services.destroy', $service->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus layanan {{ addslashes($service->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="border border-rose-200 text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                            <div class="text-3xl mb-2">💆</div>
                            Belum ada layanan. Tambahkan layanan pertama!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>



</div>

{{-- ===================================== --}}
{{-- MODAL TAMBAH LAYANAN --}}
{{-- ===================================== --}}
<div id="modal-add-service" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h4 class="font-bold text-slate-800 text-base">Tambah Layanan Baru</h4>
            <button onclick="closeModal('modal-add-service')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form action="{{ route('admin.web_management.services.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Layanan <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="e.g., Pijat Tradisional"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi Default <span class="text-rose-500">*</span></label>
                        <input type="text" name="default_duration" required placeholder="e.g., 90 Menit"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Klinik (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price_clinic" required placeholder="150000" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Home Service (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price_home" required placeholder="170000" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat layanan..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Layanan</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:border-gray-400 transition"
                         onclick="document.getElementById('add_service_image').click()">
                        <img id="add_service_preview" src="" class="hidden w-full h-32 object-cover rounded-lg mb-2" alt="">
                        <div id="add_service_placeholder">
                            <span class="text-2xl">📷</span>
                            <p class="text-xs text-gray-400 mt-1">Klik untuk upload foto layanan</p>
                        </div>
                    </div>
                    <input type="file" id="add_service_image" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this, 'add_service_preview', 'add_service_placeholder')">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-add-service')"
                        class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT LAYANAN --}}
<div id="modal-edit-service" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h4 class="font-bold text-slate-800 text-base">Edit Layanan</h4>
            <button onclick="closeModal('modal-edit-service')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>
        <form id="edit-service-form" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Layanan <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_name" name="name" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi Default <span class="text-rose-500">*</span></label>
                        <input type="text" id="edit_duration" name="default_duration" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select id="edit_status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Klinik (Rp)</label>
                        <input type="number" id="edit_price_clinic" name="price_clinic" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Home Service (Rp)</label>
                        <input type="number" id="edit_price_home" name="price_home" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi Singkat</label>
                    <textarea id="edit_description" name="description" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-slate-400 transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Ganti Foto (Opsional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center cursor-pointer hover:border-gray-400 transition"
                         onclick="document.getElementById('edit_service_image').click()">
                        <img id="edit_service_preview" src="" class="hidden w-full h-32 object-cover rounded-lg mb-2" alt="">
                        <div id="edit_service_placeholder">
                            <span class="text-2xl">📷</span>
                            <p class="text-xs text-gray-400 mt-1">Klik untuk ganti foto</p>
                        </div>
                    </div>
                    <input type="file" id="edit_service_image" name="image" accept="image/*" class="hidden"
                           onchange="previewImage(this, 'edit_service_preview', 'edit_service_placeholder')">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-edit-service')"
                        class="border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close modal on backdrop click
    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    function previewImage(input, previewId, placeholderId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                const placeholder = document.getElementById(placeholderId);
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditModal(id, name, duration, priceClinic, priceHome, description, status) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_duration').value = duration;
        document.getElementById('edit_price_clinic').value = priceClinic;
        document.getElementById('edit_price_home').value = priceHome;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_status').value = status;

        // Reset preview
        document.getElementById('edit_service_preview').classList.add('hidden');
        document.getElementById('edit_service_preview').src = '';
        const ph = document.getElementById('edit_service_placeholder');
        if (ph) ph.classList.remove('hidden');

        // Set form action
        document.getElementById('edit-service-form').action =
            '{{ url("admin/web-management/services") }}/' + id + '/update';

        openModal('modal-edit-service');
    }

    // Bind event listeners to edit buttons using data attributes
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit-service').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const duration = this.getAttribute('data-duration');
                const priceClinic = this.getAttribute('data-price-clinic');
                const priceHome = this.getAttribute('data-price-home');
                const description = this.getAttribute('data-description');
                const status = this.getAttribute('data-status');

                openEditModal(id, name, duration, priceClinic, priceHome, description, status);
            });
        });
    });

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
            document.body.style.overflow = '';
        }
    });
</script>
@endsection
