<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Nusa Terapi Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f0f7f4] min-h-screen flex items-center justify-center p-4 py-10">

    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-xl w-full max-w-md my-auto">
        
        <div class="text-center mb-8">
            <h2 class="font-bold text-lg text-slate-900 mb-6">Nusa Terapi Center</h2>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Buat Akun Baru</h1>
            <p class="text-sm text-gray-500">Lengkapi data Anda untuk mulai mendaftar.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Adam Aryanto" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
                @error('name')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
                @error('email')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor HP (Opsional)</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0812-3456-7890"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
                @error('phone')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat (Opsional)</label>
                <textarea name="address" rows="2" placeholder="Contoh: Jl. Slamet Riyadi No. 12, Solo"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">{{ old('address') }}</textarea>
                @error('address')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                <div class="relative">
                    <select name="gender" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700 appearance-none bg-white">
                        <option value="" selected disabled>Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 text-xs">
                        <span>▼</span>
                    </div>
                </div>
                @error('gender')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>


            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" required minlength="8"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
                @error('password')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required minlength="8"
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 transition text-sm text-slate-700">
            </div>

            <button type="submit" class="w-full bg-[#0f172a] text-white py-3 rounded-lg font-semibold hover:bg-slate-800 transition shadow-md mt-4">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-6 text-sm text-slate-700">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</body>
</html>
