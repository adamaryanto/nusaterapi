<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Terapis - Nusa Terapi Center')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-gray-50 h-screen flex overflow-hidden text-gray-800" style="font-family: 'Inter', sans-serif;">

    <!-- Sidebar -->
    <aside class="w-56 bg-white border-r border-gray-200 flex flex-col hidden md:flex z-20 shadow-sm flex-shrink-0">
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

    <!-- Main -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm flex-shrink-0">
            <h2 class="text-lg font-bold text-slate-900">@yield('page_title', 'Dashboard (Terapis)')</h2>
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
                    <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-50">Role: Terapis</div>
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
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-3 text-sm font-medium flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
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
    </script>
    @yield('scripts')
</body>
</html>
