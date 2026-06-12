<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Terapis - Nusa Terapi Center')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #sidebar.collapsed {
            width: 0 !important;
            border-right-width: 0 !important;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 h-screen flex overflow-hidden text-gray-800" style="font-family: 'Inter', sans-serif;">

    <!-- Sidebar -->
    <aside id="sidebar" class="w-56 bg-white border-r border-gray-200 flex flex-col z-35 shadow-sm flex-shrink-0 transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 overflow-hidden">
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('landing') }}" class="font-bold text-base text-slate-900">Nusa Terapi Center</a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
            <a href="{{ route('therapist.dashboard') }}"
               class="block px-4 py-2.5 rounded-lg text-sm transition
               {{ Route::is('therapist.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }}">
                Dashboard
            </a>
            <a href="{{ route('therapist.bookings') }}"
               class="block px-4 py-2.5 rounded-lg text-sm transition
               {{ Route::is('therapist.bookings') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }}">
                List Booking Masuk
            </a>

            <a href="{{ route('therapist.income') }}"
               class="block px-4 py-2.5 rounded-lg text-sm transition
               {{ Route::is('therapist.income') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }}">
                Pendapatan
            </a>
            <a href="{{ route('therapist.reviews') }}"
               class="block px-4 py-2.5 rounded-lg text-sm transition
               {{ Route::is('therapist.reviews') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }}">
                Rating & Review
            </a>
        </nav>
    </aside>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

    <!-- Main -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 md:px-8 shadow-sm flex-shrink-0">
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard (Terapis)')</h2>
            </div>
            <div onclick="toggleTherapistDropdown(event)" class="flex items-center space-x-3 cursor-pointer relative">
                <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 shadow-sm">
                    @if(auth()->user()->avatar_path)
                        <img src="{{ asset(auth()->user()->avatar_path) }}" class="w-full h-full object-cover" alt="Avatar">
                    @else
                        <div class="w-full h-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name }} ▾</span>

                <div id="therapist-dropdown" class="hidden absolute top-10 right-0 mt-2 w-44 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden z-30">
                    <a href="{{ route('therapist.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil Saya</a>
                    <hr class="border-gray-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleTherapistDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('therapist-dropdown');
            dropdown.classList.toggle('hidden');
        }
        document.addEventListener('click', function() {
            const dropdown = document.getElementById('therapist-dropdown');
            if (dropdown) dropdown.classList.add('hidden');
        });

        // Toggle Sidebar open/close
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('collapsed');
            } else {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                }
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
    @yield('scripts')
</body>
</html>
