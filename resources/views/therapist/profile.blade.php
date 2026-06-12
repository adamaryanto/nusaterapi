@extends('layouts.therapist')

@section('title', 'Profil Saya - Nusa Terapi Center')
@section('page_title', 'Profil Saya')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ url('/terapis/profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                    <a href="javascript:history.back()" class="text-slate-800 hover:text-slate-650 font-bold transition mr-3 text-lg">
                        ←
                    </a>
                    <h3 class="font-bold text-slate-900 text-lg">Informasi Profil Terapis</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Left Side: Avatar Profile -->
                    <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-slate-100 shadow-md mb-4 relative group">
                            <img id="avatar-preview" src="{{ $therapist->avatar_path ? asset($therapist->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($therapist->name) . '&background=2563eb&color=fff' }}" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        
                        <button type="button" onclick="triggerPhotoUpload()" class="flex items-center space-x-2 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm mb-3">
                            <span>📷</span>
                            <span>Ubah Foto</span>
                        </button>
                        <input type="file" name="avatar" id="photo-file" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                        
                        <p class="text-xs text-gray-400 leading-relaxed max-w-[200px]">
                            Format: JPG/PNG.<br>Maksimal ukuran: 2MB.
                        </p>
                        @error('avatar')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Right Side: Profile Details Form -->
                    <div class="w-full md:w-2/3 space-y-5">
                        
                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-red-600 uppercase tracking-wide">Nama Lengkap *</label>
                            <input type="text" name="name" required value="{{ old('name', $therapist->name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Jenis Kelamin</label>
                            <div class="relative">
                                <select name="gender" class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium appearance-none">
                                    <option value="" disabled {{ is_null($therapist->gender) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('gender', $therapist->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $therapist->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <span>▼</span>
                                </div>
                            </div>
                            @error('gender')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $therapist->birth_date ? \Carbon\Carbon::parse($therapist->birth_date)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                            @error('birth_date')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Email</label>
                            <input type="email" name="email" required value="{{ old('email', $therapist->email) }}"
                                class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-red-600 uppercase tracking-wide">Nomor HP *</label>
                            <input type="tel" name="phone" required value="{{ old('phone', $therapist->phone) }}"
                                class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                            @error('phone')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Alamat Lengkap</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium resize-none"
                                placeholder="Masukkan alamat lengkap Anda...">{{ old('address', $therapist->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-gray-100 my-6">

                        <h4 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Ganti Password</h4>
                        <p class="text-[11px] text-gray-400">Kosongkan kolom di bawah jika Anda tidak ingin mengganti password.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <!-- Password Baru -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                                @error('password')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 transition text-sm font-medium">
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('therapist.dashboard') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-bold text-slate-700 hover:bg-gray-50 bg-white transition shadow-sm text-center">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Trigger file upload dialog
        function triggerPhotoUpload() {
            document.getElementById('photo-file').click();
        }

        // Preview photo
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                // Limit to 2MB
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Ukuran file terlalu besar! Maksimal ukuran adalah 2MB.',
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
