<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nusa Terapi Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-[#f0f7f4] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl w-full max-w-md">
        
        <div class="text-center mb-8">
            <h2 class="font-bold text-lg text-slate-900 mb-6">Nusa Terapi Center</h2>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Masuk ke Akun Anda</h1>
            <p class="text-sm text-gray-500">Silakan masukkan email dan password Anda.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@nusaterapi.com" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
                @error('email')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" name="password" placeholder="••••••••" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700 pr-10">
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-between items-center mt-2">
                <a href="#" class="text-xs font-semibold text-slate-700 ml-auto hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" class="w-full bg-[#0f172a] text-white py-3 rounded-lg font-semibold hover:bg-slate-800 transition shadow-md">
                Login
            </button>
        </form>

        <p class="text-center mt-8 text-sm text-slate-700">
            Belum punya akun? <a href="{{ route('register') }}" class="font-bold hover:underline">Buat Akun Baru</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const passInput = document.getElementsByName('password')[0];
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />`;
            } else {
                passInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />`;
            }
        }
    </script>
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#0f172a'
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#0f172a'
            });
        </script>
    @endif
    @if($errors->any())
        <script>
            Swal.fire({
                title: 'Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                icon: 'error',
                confirmButtonColor: '#0f172a'
            });
        </script>
    @endif
</body>
</html>
