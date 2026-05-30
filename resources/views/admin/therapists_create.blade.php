@extends('layouts.admin')

@section('title', 'Tambah Terapis - Nusa Terapi Center')
@section('page_title', 'Manajemen Terapis > Tambah Terapis')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form action="{{ url('/admin/therapists/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Card Container -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-slate-900 text-lg">Data Terapis Baru</h3>
                </div>

                <!-- Card Body -->
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                    
                    <!-- Left Side: Photo Upload Placeholder -->
                    <div class="w-full md:w-1/3 flex flex-col items-center">
                        <div onclick="triggerPhotoUpload()" class="w-36 h-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-gray-100 transition relative overflow-hidden shadow-sm">
                            <img id="photo-preview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                            <div id="upload-placeholder" class="flex flex-col items-center justify-center text-center p-3">
                                <span class="text-2xl mb-2 text-gray-400">📷</span>
                                <span class="text-xs font-semibold text-slate-600">Upload Foto</span>
                            </div>
                        </div>
                        <input type="file" name="avatar" id="therapist-photo-file" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                        
                        <p class="text-[10px] text-gray-400 mt-2 text-center max-w-[150px]">
                            Format: JPG/PNG. Maksimal 2MB.
                        </p>
                        @error('avatar')
                            <p class="text-xs text-red-600 mt-1 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Right Side: Details Inputs -->
                    <div class="w-full md:w-2/3 space-y-4">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wide mb-1.5">Nama Lengkap *</label>
                            <input type="text" name="name" required placeholder="Masukkan nama lengkap terapis" value="{{ old('name') }}"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spesialisasi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wide mb-1.5">Spesialisasi *</label>
                            <input type="text" name="specialty" required placeholder="Contoh: Pijat Tradisional, Bekam, Lulur" value="{{ old('specialty') }}"
                                class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm">
                            @error('specialty')
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
                    Simpan Data
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
                    alert("Ukuran file terlalu besar! Maksimal ukuran adalah 2MB.");
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                    document.getElementById('photo-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
