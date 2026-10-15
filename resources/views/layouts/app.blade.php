<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Data Pasien') - KLINIKA</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="h-full text-slate-800 antialiased flex flex-col md:flex-row">

    <!-- Sidebar Navigasi -->
    <aside class="w-full md:w-64 bg-white border-r border-slate-200 flex flex-col shrink-0">
        <!-- Logo & Nama Klinik -->
        <div class="p-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center text-white font-bold text-xl shadow-sm shadow-teal-200">
                    +
                </div>
                <div>
                    <h1 class="font-bold text-lg text-slate-900 leading-tight">Klinika</h1>
                    <p class="text-xs text-slate-500">RME & Pelayanan Pasien</p>
                </div>
            </div>
            <div class="mt-3 px-2.5 py-1.5 rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-between text-xs text-teal-800">
                <span class="font-medium truncate">Klinik Sehat Sentosa</span>
                <span class="font-bold text-[10px] bg-teal-200 text-teal-900 px-1.5 py-0.5 rounded">KSS-01</span>
            </div>
        </div>

        <!-- Menu Sidebar -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>

            <!-- Pasien (Aktif) -->
            <a href="{{ route('patients.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors {{ request()->routeIs('patients.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('patients.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Data Pasien</span>
            </a>

            <!-- Kunjungan -->
            <a href="{{ route('visits.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors {{ request()->routeIs('visits.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('visits.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Kunjungan</span>
            </a>

            <!-- Antrean -->
            <a href="{{ route('queues.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors {{ request()->routeIs('queues.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('queues.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Antrean Poli</span>
            </a>
        </nav>

        <!-- User Role Profile Footer -->
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-sm">
                    P
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-slate-900 truncate">Petugas Pendaftaran</p>
                    <p class="text-[11px] text-teal-600 font-medium">Akses: Pendaftaran Pasien</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar Navbar -->
        <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <a href="{{ route('patients.index') }}" class="hover:text-teal-600">Beranda</a>
                <span>/</span>
                <span class="text-slate-900 font-medium">@yield('page-title', 'Data Pasien')</span>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-xs text-slate-600 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    <span class="text-[11px] text-slate-400 block">WIB • Online</span>
                </div>
            </div>
        </header>

        <!-- Flash Message Feedback -->
        <main class="flex-1 p-6 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                @if (session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm animate-fade">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm animate-fade">
                        <svg class="w-5 h-5 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
                        <div class="flex items-center gap-2 font-semibold text-sm mb-1 text-amber-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Periksa kembali isian form Anda:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs text-amber-800 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
