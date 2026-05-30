<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - Nusa Terapi Center')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-gray-50 h-screen flex overflow-hidden text-gray-800">

    <!-- Sidebar Aside -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col hidden md:flex z-20 shadow-sm">
        <div class="h-16 flex items-center px-8 border-b border-gray-100">
            <a href="{{ route('landing') }}" class="font-bold text-lg text-slate-900">Nusa Terapi Center</a>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Dashboard
            </a>
            <a href="{{ route('admin.patients') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.patients*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Manajemen Pasien
            </a>
            <a href="{{ route('admin.therapists') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.therapists*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Manajemen Terapis
            </a>
            <a href="{{ route('admin.bookings') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.bookings*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Manajemen Booking
            </a>
            <a href="{{ route('admin.transactions') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.transactions*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Data Transaksi
            </a>
            <a href="{{ route('admin.web_management') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.web_management*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Manajemen Web
            </a>
            <a href="{{ route('admin.reviews') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.reviews*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition mt-4">
                Rating & Review
            </a>
            <a href="{{ route('admin.reports') }}" class="block px-4 py-3 rounded-lg text-sm font-medium {{ Route::is('admin.reports*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition">
                Laporan
            </a>
        </nav>
    </aside>

    <!-- Main Container -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">@yield('page_title', 'Admin Dashboard')</h2>
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
    </script>
    @yield('scripts')
</body>
</html>
