<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nusa Terapi Center')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .faq-transition { transition: all 0.3s ease-in-out; }
    </style>
    @yield('styles')
    <!-- Navbar -->
    <nav class="flex justify-between items-center py-4 px-8 md:px-16 bg-white border-b sticky top-0 z-50">
        @if(Route::is('customer.booking'))
            <span class="font-bold text-lg text-slate-800">Buat Pesanan &gt; Form Booking</span>
        @elseif(Route::is('customer.profile'))
            <span class="font-bold text-lg text-slate-800">Data Diri &gt; Edit Profil</span>
        @else
            <a href="{{ route('landing') }}" class="font-bold text-xl text-slate-900 cursor-pointer">Nusa Terapi Center</a>
        @endif
        
        @if(!Route::is('customer.booking') && !Route::is('customer.profile'))
        <div class="hidden md:flex space-x-8 text-sm font-medium text-gray-600 items-center">
            <a id="nav-link-beranda" href="{{ route('landing') }}#beranda" class="hover:text-slate-900 transition py-1 text-gray-600">Beranda</a>
            <a id="nav-link-layanan" href="{{ route('landing') }}#layanan" class="hover:text-slate-900 transition py-1 text-gray-600">Layanan</a>
            @auth
                @if(auth()->user()->role === 'customer')
                    <a id="nav-history" href="{{ route('customer.history') }}" class="hover:text-slate-900 transition py-1 {{ Route::is('customer.history') ? 'text-slate-900 font-bold border-b-2 border-slate-900' : '' }}">Riwayat Pesanan</a>
                @endif
            @endauth
            <a id="nav-link-tentang-kami" href="{{ route('landing') }}#tentang-kami" class="hover:text-slate-900 transition py-1 text-gray-600">Tentang Kami</a>
            <a id="nav-link-testimoni" href="{{ route('landing') }}#testimoni" class="hover:text-slate-900 transition py-1 text-gray-600">Testimoni</a>
            <a id="nav-link-faq" href="{{ route('landing') }}#faq" class="hover:text-slate-900 transition py-1 text-gray-600">FAQ</a>
        </div>
        @endif

        <div id="auth-panel" class="flex items-center space-x-3">
            @guest
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Login</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-medium text-white bg-slate-900 rounded-lg hover:bg-slate-800 transition">Register</a>
            @endguest
            
            @auth
                <div class="relative inline-block text-left z-50">
                    <button onclick="toggleDropdown(event)" class="flex items-center space-x-2 focus:outline-none group">
                        <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden border border-gray-300">
                            @if(auth()->user()->avatar_path)
                                <img src="{{ asset(auth()->user()->avatar_path) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f1f5f9&color=0f172a" alt="Profile" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <span class="text-sm font-medium text-slate-800 group-hover:text-slate-600 transition">{{ auth()->user()->name }} ▾</span>
                    </button>
                    
                    <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-44 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil Saya</a>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition font-semibold">Dashboard Admin</a>
                        @else
                            <a href="{{ route('customer.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil Saya</a>
                            <a href="{{ route('customer.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Riwayat Pesanan</a>
                        @endif
                        <hr class="border-gray-100">
                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">Logout</button>
                    </div>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#171e2c] text-white py-16 px-8 md:px-24">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 max-w-7xl mx-auto">
            <div class="col-span-1">
                <h4 class="font-bold text-xl mb-6">Nusa Terapi Center</h4>
                <p class="text-sm text-gray-400 leading-relaxed mb-6">
                    Jl. Slamet Riyadi No. 123,<br>Kec. Banjarsari, Kota Solo<br>Jawa Tengah, 57131
                </p>
                <div class="text-sm text-gray-400 space-y-2">
                    <p>📞 0812-3456-7890</p>
                    <p>✉️ halo@nusaterapi.com</p>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-sm mb-6 uppercase tracking-wider">Layanan Kami</h4>
                <ul class="text-sm text-gray-400 space-y-3">
                    <li><a href="#" class="hover:text-white transition">Pijat Tradisional</a></li>
                    <li><a href="#" class="hover:text-white transition">Refleksi Kaki</a></li>
                    <li><a href="#" class="hover:text-white transition">Terapi Bekam</a></li>
                    <li><a href="#" class="hover:text-white transition">Lulur & Scrub</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm mb-6 uppercase tracking-wider">Tautan Cepat</h4>
                <ul class="text-sm text-gray-400 space-y-3">
                    <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition">Testimoni</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-sm mb-6 uppercase tracking-wider">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center hover:bg-blue-600 transition cursor-pointer font-bold">f</div>
                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center hover:bg-pink-600 transition cursor-pointer font-bold">ig</div>
                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center hover:bg-green-500 transition cursor-pointer font-bold">wa</div>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800 mt-16 pt-8 text-center text-xs text-gray-500">
            &copy; 2026 Nusa Terapi Center. Seluruh Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Global JavaScript for Auth and Dropdowns -->
    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('profile-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        window.onclick = function(event) {
            const dropdown = document.getElementById('profile-dropdown');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }
    </script>
    @yield('scripts')
</body>
</html>
