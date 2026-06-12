<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Nusa Terapi Center')</title>
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

    <!-- Sidebar Aside -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col z-35 shadow-sm transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 overflow-hidden">
        <div class="h-16 flex items-center px-8 border-b border-gray-100">
            <a href="{{ route('landing') }}" class="font-bold text-lg text-slate-900">Nusa Terapi Center</a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Dashboard
            </a>
            <a href="{{ route('admin.patients') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.patients*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Manajemen Pasien
            </a>
            <a href="{{ route('admin.membership') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.membership*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Manajemen Membership
            </a>
            <a href="{{ route('admin.therapists') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.therapists*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Manajemen Terapis
            </a>
            <a href="{{ route('admin.bookings') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.bookings*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Manajemen Booking
            </a>
            <a href="{{ route('admin.transactions') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.transactions*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Data Transaksi
            </a>
            <a href="{{ route('admin.web_management') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.web_management*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Manajemen Web
            </a>
            <a href="{{ route('admin.reviews') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.reviews*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition mt-4">
                Rating & Review
            </a>
            <a href="{{ route('admin.reports') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.reports*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Laporan
            </a>
            <a href="{{ route('admin.settings.system') }}" class="block px-4 py-3 rounded-lg text-sm {{ Route::is('admin.settings.system*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:bg-gray-50 font-medium' }} transition">
                Pengaturan Sistem
            </a>
        </nav>
    </aside>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

    <!-- Main Container -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 md:px-8 shadow-sm">
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 focus:outline-none text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-slate-900">@yield('page_title', 'Admin Dashboard')</h2>
            </div>
            <div onclick="toggleAdminDropdown(event)" class="flex items-center space-x-3 cursor-pointer relative">
                <div class="w-9 h-9 rounded-full overflow-hidden border border-gray-200 shadow-md relative">
                    @if(auth()->user()->avatar_path)
                        <img id="admin-header-avatar" src="{{ asset(auth()->user()->avatar_path) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <div id="admin-header-initials" class="w-full h-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <span id="admin-name" class="text-sm font-medium text-slate-700">{{ auth()->user()->name }} ▾</span>
                
                <div id="admin-dropdown" class="hidden absolute top-10 right-0 mt-2 w-40 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden z-30">
                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil Saya</a>
                    <hr class="border-gray-100">
                    <button onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">Logout</button>
                </div>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </header>

        <!-- Dynamic Content Section -->
        <div class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </div>
    </main>

    <!-- Admin Global JS -->
    <script>
        // Toggle admin dropdown on click
        function toggleAdminDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('admin-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            const dropdown = document.getElementById('admin-dropdown');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        }

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
