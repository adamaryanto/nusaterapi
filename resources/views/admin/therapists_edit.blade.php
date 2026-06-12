@extends('layouts.admin')

@section('title', 'Edit Terapis - Nusa Terapi Center')
@section('page_title', 'Manajemen Terapis > Edit Terapis')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ url('/admin/therapists/' . $therapist->id . '/edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Card Container -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-slate-900 text-lg">Formulir Data Profesional Terapis</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Left Side: Circular Avatar Profile Picture -->
                    <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-slate-100 shadow-md mb-4 relative group">
                            <img id="avatar-preview" src="{{ $therapist->avatar_path ? asset($therapist->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($therapist->name) . '&background=0f172a&color=fff' }}" alt="Avatar Terapis" class="w-full h-full object-cover">
                        </div>
                        
                        <button type="button" onclick="triggerPhotoUpload()" class="flex items-center space-x-2 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm mb-3">
                            <span>📁</span>
                            <span>Ubah Foto</span>
                        </button>
                        <input type="file" name="avatar" id="therapist-photo-file" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                        
                        <p class="text-xs text-gray-400 leading-relaxed max-w-[200px]">
                            Format: JPG/PNG.<br>Maksimal ukuran: 2MB.
                        </p>
                        @error('avatar')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Right Side: Details Inputs -->
                    <div class="w-full md:w-2/3 space-y-4">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" required placeholder="Masukkan nama lengkap terapis" value="{{ old('name', $therapist->name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email *</label>
                            <input type="email" name="email" required placeholder="Contoh: terapis@nusaterapi.com" value="{{ old('email', $therapist->user?->email) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" placeholder="Masukkan password minimal 8 karakter"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. Telepon -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">No. Telepon / WhatsApp</label>
                            <input type="text" name="phone" placeholder="Contoh: 081234567890" value="{{ old('phone', $therapist->user?->phone) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('phone')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Jenis Kelamin *</label>
                            <select name="gender" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                                <option value="" disabled {{ is_null($therapist->user?->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender', $therapist->user?->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $therapist->user?->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spesialisasi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Spesialisasi *</label>
                            <input type="text" name="specialty" required placeholder="Contoh: Pijat Tradisional, Bekam, Lulur" value="{{ old('specialty', $therapist->specialty) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('specialty')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Alamat</label>
                            <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap terapis"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">{{ old('address', $therapist->user?->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Keaktifan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Status Keaktifan</label>
                            <select name="status" id="therapist-status"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                                <option value="Active" {{ old('status', $therapist->status) == 'Active' ? 'selected' : '' }}>Aktif</option>
                                <option value="Inactive" {{ old('status', $therapist->status) == 'Inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex justify-end space-x-3 mb-10">
                <a href="{{ route('admin.therapists') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm text-center">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#0f172a] text-white rounded-lg text-sm font-semibold hover:bg-slate-800 transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Trigger file select dialog
        function triggerPhotoUpload() {
            document.getElementById('therapist-photo-file').click();
        }

        // Preview photo on upload
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: "Ukuran file terlalu besar! Maksimal ukuran adalah 2MB.",
                        icon: 'error',
                        confirmButtonColor: '#0f172a'
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
