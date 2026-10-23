<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Klinika</title>
    <meta name="description" content="Masuk ke sistem Rekam Medis Elektronik Klinika">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal:        #0d9488;
            --teal-dark:   #0f766e;
            --teal-light:  #ccfbf1;
            --teal-bg:     #e8f5f4;
            --text-dark:   #1a2e2c;
            --text-mid:    #374151;
            --text-muted:  #6b7280;
            --border:      #d1d5db;
            --error-bg:    #fef2f2;
            --error-text:  #b91c1c;
            --info-bg:     #f0fdfa;
            --info-border: #99f6e4;
            --info-text:   #0f766e;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            display: flex;
            min-height: 100vh;
            background: #fff;
        }

        /* ── Left panel ─────────────────────────────────── */
        .panel-left {
            width: 46%;
            background: var(--teal-bg);
            padding: 3rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--teal-dark);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .logo span { font-size: 1.3rem; }

        .tagline {
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.25;
            margin-bottom: 1rem;
        }

        .tagline-sub {
            color: var(--text-mid);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
            margin-bottom: 3.5rem;
        }
        .features li {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            color: var(--text-mid);
            font-size: 0.93rem;
        }
        .features li svg {
            flex-shrink: 0;
            color: var(--teal);
        }

        .panel-footer {
            color: var(--text-muted);
            font-size: 0.8rem;
            line-height: 1.6;
        }

        /* ── Right panel ─────────────────────────────────── */
        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 4rem;
        }

        .form-wrap {
            width: 100%;
            max-width: 420px;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
        }

        .form-hint {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* error alert */
        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        /* form fields */
        .field { margin-bottom: 1.25rem; }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.45rem;
        }
        label .req { color: var(--teal); margin-left: 2px; }

        .input-wrap { position: relative; }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            font-size: 0.95rem;
            color: var(--text-dark);
            background: #fff;
            outline: none;
            transition: border-color 0.15s;
        }
        input:focus { border-color: var(--teal); }
        input.is-error { border-color: var(--error-text); }

        #password-input { padding-right: 5.5rem; }

        .toggle-pass {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--teal);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
        }

        /* remember + forgot */
        .row-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-mid);
            cursor: pointer;
        }
        .remember input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--teal);
        }
        .forgot {
            font-size: 0.875rem;
            color: var(--teal);
            text-decoration: none;
        }
        .forgot:hover { text-decoration: underline; }

        /* submit button */
        .btn-login {
            display: block;
            width: 100%;
            padding: 0.7rem;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 1.25rem;
        }
        .btn-login:hover { background: var(--teal-dark); }
        .btn-login:active { transform: scale(0.99); }

        /* info box */
        .info-box {
            background: var(--info-bg);
            border: 1.5px solid var(--info-border);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            display: flex;
            gap: 0.6rem;
            font-size: 0.85rem;
            color: var(--info-text);
            margin-bottom: 1.5rem;
        }
        .info-box svg { flex-shrink: 0; margin-top: 1px; }
        .info-box p { line-height: 1.4; }
        .info-box p strong { display: block; margin-bottom: 0.1rem; }

        /* bottom note */
        .bottom-note {
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ── Responsive ─────────────────────────────────── */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .panel-left { width: 100%; padding: 2rem; }
            .tagline { font-size: 1.6rem; }
            .panel-right { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="panel-left">
    <div class="logo">
        <span>+</span> Klinika
    </div>

    <h2 class="tagline">Pelayanan terhubung,<br>Rekam medis tertata.</h2>
    <p class="tagline-sub">
        Ruang kerja klinik yang menyatukan pendaftaran, pelayanan medis, farmasi, dan kasir.
    </p>

    <ul class="features">
        <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
            </svg>
            Akses sesuai tanggung jawab
        </li>
        <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
            </svg>
            Identitas pasien dan kunjungan selalu jelas
        </li>
        <li>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
            </svg>
            Perubahan RME dan transaksi tercatat
        </li>
    </ul>

    <div class="panel-footer">
        Lingkungan simulasi &bull; Seluruh data contoh fiktif<br>
        Aplikasi ini bukan sumber rekomendasi medis.
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="panel-right">
    <div class="form-wrap">
        <h1>Login</h1>
        <p class="form-hint">Gunakan akun yang diberikan administrator klinik.</p>

        @if ($errors->any())
            <div class="alert-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="field">
                <label for="email">
                    Email atau nama pengguna <span class="req">*</span>
                </label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="dina.lestari@klinik.example"
                    autocomplete="username"
                    class="{{ $errors->has('email') ? 'is-error' : '' }}"
                    autofocus
                >
            </div>

            <div class="field">
                <label for="password-input">
                    Kata sandi <span class="req">*</span>
                </label>
                <div class="input-wrap">
                    <input
                        id="password-input"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        class="{{ $errors->has('email') ? 'is-error' : '' }}"
                    >
                    <button type="button" class="toggle-pass" onclick="togglePassword()" id="toggle-btn">
                        Tampilkan
                    </button>
                </div>
            </div>

            <div class="row-options">
                <label class="remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat perangkat ini
                </label>
                <a href="#" class="forgot">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="btn-login" id="btn-login">Login</button>
        </form>

        <div class="info-box" role="note">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <p>
                <strong>Perangkat bersama?</strong>
                Jangan aktifkan ingat perangkat. Selalu keluar setelah tugas selesai.
            </p>
        </div>

        <p class="bottom-note">
            Butuh akses atau bantuan? Hubungi Admin Klinik.<br>
            Setelah masuk, sistem membuka dashboard sesuai peran aktif.
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    const btn   = document.getElementById('toggle-btn');
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'Sembunyikan';
    } else {
        input.type = 'password';
        btn.textContent = 'Tampilkan';
    }
}
</script>
</body>
</html>
