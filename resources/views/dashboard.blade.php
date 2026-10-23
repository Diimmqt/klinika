<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Klinika</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --teal: #0d9488;
            --teal-dark: #0f766e;
            --teal-bg: #f0fdfa;
            --text-dark: #1a2e2c;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --sidebar-w: 220px;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f9fafb;
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            position: fixed;
            top: 0; left: 0; bottom: 0;
        }
        .sidebar-logo {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--teal-dark);
            margin-bottom: 2rem;
            padding-left: 0.5rem;
        }
        .sidebar-logo span { font-size: 1.2rem; }
        .nav-item {
            display: block;
            padding: 0.55rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
            transition: background 0.12s, color 0.12s;
        }
        .nav-item:hover, .nav-item.active {
            background: var(--teal-bg);
            color: var(--teal-dark);
            font-weight: 500;
        }
        .nav-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 1rem 0.75rem 0.4rem;
        }
        .sidebar-spacer { flex: 1; }
        .sidebar-user {
            border-top: 1px solid var(--border);
            padding-top: 1rem;
            font-size: 0.85rem;
        }
        .user-name { font-weight: 600; color: var(--text-dark); }
        .user-role { color: var(--text-muted); font-size: 0.78rem; }
        .logout-link {
            display: inline-block;
            margin-top: 0.5rem;
            color: var(--teal);
            font-size: 0.82rem;
            text-decoration: none;
        }
        .logout-link:hover { text-decoration: underline; }

        /* Main */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 2rem 2.5rem;
        }
        .page-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.3rem;
        }
        .page-sub {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Welcome card */
        .welcome-card {
            background: linear-gradient(135deg, var(--teal), var(--teal-dark));
            color: #fff;
            border-radius: 10px;
            padding: 1.75rem 2rem;
            margin-bottom: 2rem;
        }
        .welcome-card h2 { font-size: 1.2rem; margin-bottom: 0.4rem; }
        .welcome-card p { font-size: 0.9rem; opacity: 0.85; }

        /* Stats grid */
        .stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem; }
        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1.25rem 1.5rem;
        }
        .stat-label { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.4rem; }
        .stat-value { font-size: 1.8rem; font-weight: 700; color: var(--text-dark); }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo"><span>+</span> Klinika</div>

    <a href="{{ route('dashboard') }}" class="nav-item active">🏠 Dashboard</a>

    @canany(['patient.view'])
    <div class="nav-section">Pelayanan</div>
    <a href="{{ route('patients.index') }}" class="nav-item">👤 Pasien</a>
    @endcanany

    @role('super_admin|admin_klinik')
    <div class="nav-section">Administrasi</div>
    <a href="{{ route('admin.users.index') }}" class="nav-item">👥 Pengguna</a>
    <a href="{{ route('admin.roles.index') }}" class="nav-item">🔐 Role</a>
    <a href="{{ route('admin.permissions.index') }}" class="nav-item">🛡 Permission</a>
    @endrole

    <div class="sidebar-spacer"></div>

    <div class="sidebar-user">
        <div class="user-name">{{ Auth::user()->name }}</div>
        <div class="user-role">
            @foreach(Auth::user()->getRoleNames() as $role)
                {{ $role }}@if(!$loop->last), @endif
            @endforeach
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;" class="logout-link">
                Keluar
            </button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="page-title">Dashboard</div>
    <p class="page-sub">{{ now()->translatedFormat('l, d F Y') }}</p>

    <div class="welcome-card">
        <h2>Selamat datang, {{ Auth::user()->name }}! 👋</h2>
        <p>Sistem berjalan normal. Gunakan menu di kiri untuk mengakses modul sesuai peran Anda.</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Role aktif</div>
            <div class="stat-value">{{ Auth::user()->getRoleNames()->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Login terakhir</div>
            <div class="stat-value" style="font-size:1rem;padding-top:0.4rem">
                {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->diffForHumans() : '—' }}
            </div>
        </div>
    </div>
</main>

</body>
</html>
