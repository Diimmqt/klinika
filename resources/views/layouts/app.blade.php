<!DOCTYPE html>
<html lang="id" class="h-full" style="background-color:#F4F7F8">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Klinika') - KLINIKA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --klinika-bg: #F4F7F8;
            --klinika-border: #DFE7EA;
            --klinika-primary: #087F83;
            --klinika-text: #182B35;
            --klinika-muted: #627680;
            --klinika-warn-bg: #FFF5E4;
            --klinika-warn-text: #96611C;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--klinika-text);
            background-color: var(--klinika-bg);
        }
        .klinika-sidebar { width: 224px; border-color: var(--klinika-border); }
        .klinika-border { border-color: var(--klinika-border); }
        .klinika-primary { background-color: var(--klinika-primary); }
        .klinika-primary:hover { background-color: #066669; }
        .klinika-primary-text { color: var(--klinika-primary); }
        .klinika-muted { color: var(--klinika-muted); }
        .klinika-card { background: #fff; border: 1px solid var(--klinika-border); border-radius: 8px; }
        .klinika-nav-active { background-color: var(--klinika-primary); color: #fff; }
        .klinika-nav-idle { color: var(--klinika-muted); }
        .klinika-nav-idle:hover { background-color: #F4F7F8; color: var(--klinika-text); }
    </style>
</head>
<body class="h-full antialiased flex flex-col md:flex-row">

    <aside class="w-full md:w-56 klinika-sidebar bg-white border-r flex flex-col shrink-0">
        <div class="p-5 border-b klinika-border">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg klinika-primary flex items-center justify-center text-white font-bold text-lg">
                    +
                </div>
                <div>
                    <h1 class="font-bold text-base leading-tight" style="color:var(--klinika-text)">Klinika</h1>
                    <p class="text-xs klinika-muted">RME & Pelayanan</p>
                </div>
            </div>
        </div>

        <div class="px-4 py-3 border-b klinika-border">
            <p class="text-xs klinika-muted truncate">Klinik Sehat Sentosa</p>
            <p class="text-xs font-semibold" style="color:var(--klinika-text)">Bandung • KSS-01</p>
        </div>

        <nav class="flex-1 p-4 space-y-0.5 overflow-y-auto">
            @role('super_admin')
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.users.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Daftar akun</span>
            </a>

            <a href="{{ route('admin.roles.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.roles.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Hak akses</span>
            </a>

            <a href="{{ route('admin.permissions.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.permissions.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <span>Master data</span>
            </a>

            <a href="{{ route('admin.audits.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.audits.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>Audit aktivitas</span>
            </a>

            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('admin.settings.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Pengaturan</span>
            </a>
            @endrole

            @role('pendaftaran')
            <a href="{{ route('pendaftaran.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('pendaftaran.dashboard') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('patients.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm transition-colors {{ request()->routeIs('patients.*') ? 'klinika-nav-active' : 'klinika-nav-idle' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Data pasien</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm klinika-nav-idle opacity-50 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Kunjungan</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md font-medium text-sm klinika-nav-idle opacity-50 cursor-not-allowed">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                <span>Antrean</span>
            </a>
            @endrole
        </nav>

        <div class="p-4 border-t klinika-border space-y-3" style="background-color:#F4F7F8">
            <div class="text-xs klinika-muted font-medium">AKSES: {{ auth()->user()?->getRoleNames()->first() ?? 'Pengguna' }}</div>
            <a href="#" class="block text-xs klinika-muted hover:opacity-80">Bantuan & panduan</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs font-medium" style="background:none;border:none;padding:0;cursor:pointer;color:var(--klinika-muted)">
                    Keluar dari aplikasi
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <header class="bg-white border-b klinika-border px-6 py-3 flex items-center justify-between shrink-0">
            <div class="hidden sm:block">
                <span class="text-sm font-medium" style="color:var(--klinika-text)">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
                <span class="text-xs klinika-muted"> • WIB</span>
            </div>
            <div class="flex items-center gap-4">
                {{-- Bell icon with badge --}}
                <button class="relative p-1 rounded-md klinika-nav-idle" style="color:var(--klinika-muted)" aria-label="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-0.5 right-0.5 w-4 h-4 rounded-full text-white flex items-center justify-center text-[9px] font-bold" style="background:var(--klinika-primary)">3</span>
                </button>
                {{-- User info --}}
                <div class="flex items-center gap-2 cursor-pointer">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-semibold" style="color:var(--klinika-text)">{{ auth()->user()?->name ?? 'Pengguna' }}</div>
                        <div class="text-[11px] klinika-muted">{{ auth()->user()?->getRoleNames()->first() ?? 'Pengguna' }}</div>
                    </div>
                    <svg class="w-3 h-3 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                {{-- Breadcrumb --}}
                @hasSection('breadcrumb')
                <div class="flex items-center gap-1.5 text-xs klinika-muted -mt-2 mb-1">
                    @yield('breadcrumb')
                </div>
                @endif

                @if (session('success'))
                    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#ecfdf5;border-color:#a7f3d0;color:#065f46">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#fff1f2;border-color:#fecdd3;color:#9f1239">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 rounded-lg border" style="background:var(--klinika-warn-bg);border-color:#f5d9a8;color:var(--klinika-warn-text)">
                        <div class="flex items-center gap-2 font-semibold text-sm mb-1">
                            <span>Periksa kembali isian form Anda:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
