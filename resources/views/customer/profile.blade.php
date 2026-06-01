@extends('layouts.app')

@section('title', 'Profil Saya - Nusa Terapi Center')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg">Profil Saya</h3>
                    <p class="text-xs text-gray-400 mt-1">Kelola informasi profil Anda di Nusa Terapi Center.</p>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Left Side: Avatar Profile -->
                    <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                        <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-slate-100 shadow-md mb-4 relative group">
                            <img id="avatar-preview" src="{{ $user->avatar_path ? asset($user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0f172a&color=fff' }}" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        
                        <button type="button" onclick="triggerPhotoUpload()" class="flex items-center space-x-2 border border-gray-300 px-4 py-2 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm mb-3">
                            <span>📁</span>
                            <span>Ubah Foto</span>
                        </button>
                        <input type="file" name="avatar" id="photo-file" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                        
                        <p class="text-[10px] text-gray-400 leading-relaxed max-w-[200px]">
                            Format: JPG/PNG.<br>Maksimal ukuran: 2MB.
                        </p>
                        @error('avatar')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Right Side: Profile Details Form -->
                    <div class="w-full md:w-2/3 space-y-5">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email *</label>
                            <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Nomor HP</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium">
                            @error('phone')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Alamat Lengkap</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm font-medium resize-none">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="border-gray-100 my-6">

                        <h4 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Ganti Password</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Kosongkan kolom di bawah jika Anda tidak ingin mengganti password.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
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
            </div>

            <!-- Form Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('landing') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-slate-700 hover:bg-gray-50 bg-white transition shadow-sm text-center">
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
