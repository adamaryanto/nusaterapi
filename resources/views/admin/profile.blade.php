@extends('layouts.admin')

@section('title', 'Profil Admin - Nusa Terapi Center')
@section('page_title', 'Profil Admin')

@section('content')
    <!-- Main Card Container -->
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/admin/profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-slate-900 text-lg">Profil Anda</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Left Side: Avatar Profile -->
                    <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-slate-100 shadow-md mb-4 relative group">
                            <img id="avatar-preview" src="{{ $admin->avatar_path ? asset($admin->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($admin->name) . '&background=0f172a&color=fff' }}" alt="Avatar Admin" class="w-full h-full object-cover">
                        </div>
                        
                        <button type="button" onclick="triggerPhotoUpload()" class="flex items-center space-x-2 border border-gray-300 px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm mb-3">
                            <span>📁</span>
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
                    <div class="w-full md:w-2/3 space-y-4">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" required value="{{ old('name', $admin->name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email *</label>
                            <input type="email" name="email" required value="{{ old('email', $admin->email) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nomor HP</label>
                            <input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('phone')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Alamat Lengkap</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium resize-none">{{ old('address', $admin->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-gray-100 my-6">

                        <h4 class="font-bold text-slate-950 text-sm tracking-wide uppercase">Ganti Password</h4>
                        <p class="text-xs text-gray-400">Kosongkan kolom di bawah jika tidak ingin mengganti password.</p>

                        <!-- Password Baru -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                        </div>

                    </div>

                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="flex justify-end space-x-3 mb-10">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 bg-white transition shadow-sm text-center">
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
                    alert("Ukuran file terlalu besar! Maksimal ukuran adalah 2MB.");
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
