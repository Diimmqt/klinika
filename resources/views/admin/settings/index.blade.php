@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')
@section('page-title', 'Pengaturan')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.settings.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Pengaturan</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Konfigurasi sistem</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Konfigurasi sistem</h2>
        <p class="text-sm klinika-muted mt-1">
            Pengaturan global aplikasi, keamanan akun, dan notifikasi administrasi.
        </p>
    </div>

    {{-- Cyan/Teal Info Banner --}}
    <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Pengaturan global • Berlaku lintas klinik</p>
                <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                    Nilai selain masa undangan dan reset adalah nilai awal ilustratif (simulasi), bukan kebijakan resmi klinik. Semua perubahan tercatat.
                </p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-lg border flex items-center justify-between" style="background:#ECFDF5;border-color:#A7F3D0;color:#065F46">
        <div class="flex items-center gap-2.5 text-xs font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#FFF1F2;border-color:#FECDD3">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#E11D48">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold" style="color:#9F1239">Periksa kembali {{ $errors->count() }} isian</p>
            <p class="text-xs mt-0.5" style="color:#BE123C">
                {{ $errors->first() }}
            </p>
        </div>
    </div>
    @endif

    {{-- Main Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- LEFT COLUMN: Settings Form --}}
        <div class="lg:col-span-8 space-y-5">
            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
                @csrf

                {{-- Card 1: Umum --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Umum</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="app_name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Nama aplikasi
                            </label>
                            <input type="text" id="app_name" name="app_name"
                                   value="{{ old('app_name', $settings['app_name'] ?? 'Klinika RME') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>

                        <div>
                            <label for="app_locale" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Bahasa
                            </label>
                            <select id="app_locale" name="app_locale" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="Bahasa Indonesia" {{ old('app_locale', $settings['app_locale'] ?? '') === 'Bahasa Indonesia' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="English" {{ old('app_locale', $settings['app_locale'] ?? '') === 'English' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>

                        <div>
                            <label for="app_timezone" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Zona waktu
                            </label>
                            <select id="app_timezone" name="app_timezone" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="WIB • Asia/Jakarta" {{ old('app_timezone', $settings['app_timezone'] ?? '') === 'WIB • Asia/Jakarta' ? 'selected' : '' }}>WIB • Asia/Jakarta</option>
                                <option value="WITA • Asia/Makassar" {{ old('app_timezone', $settings['app_timezone'] ?? '') === 'WITA • Asia/Makassar' ? 'selected' : '' }}>WITA • Asia/Makassar</option>
                                <option value="WIT • Asia/Jayapura" {{ old('app_timezone', $settings['app_timezone'] ?? '') === 'WIT • Asia/Jayapura' ? 'selected' : '' }}>WIT • Asia/Jayapura</option>
                                <option value="UTC" {{ old('app_timezone', $settings['app_timezone'] ?? '') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        </div>

                        <div>
                            <label for="app_date_format" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Format tanggal
                            </label>
                            <select id="app_date_format" name="app_date_format" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="DD MMMM YYYY" {{ old('app_date_format', $settings['app_date_format'] ?? '') === 'DD MMMM YYYY' ? 'selected' : '' }}>DD MMMM YYYY</option>
                                <option value="DD/MM/YYYY" {{ old('app_date_format', $settings['app_date_format'] ?? '') === 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="YYYY-MM-DD" {{ old('app_date_format', $settings['app_date_format'] ?? '') === 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>
                    </div>

                    <p class="text-[11px] klinika-muted pt-1">
                        Nilai awal simulasi • Pengaturan tampilan berlaku di seluruh aplikasi.
                    </p>
                </div>

                {{-- Card 2: Autentikasi dan sesi --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Autentikasi dan sesi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="session_idle_timeout" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Batas sesi tidak aktif
                            </label>
                            <select id="session_idle_timeout" name="session_idle_timeout" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="15 menit" {{ old('session_idle_timeout', $settings['session_idle_timeout'] ?? '') === '15 menit' ? 'selected' : '' }}>15 menit</option>
                                <option value="30 menit" {{ old('session_idle_timeout', $settings['session_idle_timeout'] ?? '') === '30 menit' ? 'selected' : '' }}>30 menit</option>
                                <option value="60 menit" {{ old('session_idle_timeout', $settings['session_idle_timeout'] ?? '') === '60 menit' ? 'selected' : '' }}>60 menit</option>
                                <option value="120 menit" {{ old('session_idle_timeout', $settings['session_idle_timeout'] ?? '') === '120 menit' ? 'selected' : '' }}>120 menit</option>
                            </select>
                        </div>

                        <div>
                            <label for="session_max_lifetime" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Durasi maksimum sesi
                            </label>
                            <select id="session_max_lifetime" name="session_max_lifetime" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="4 jam" {{ old('session_max_lifetime', $settings['session_max_lifetime'] ?? '') === '4 jam' ? 'selected' : '' }}>4 jam</option>
                                <option value="8 jam" {{ old('session_max_lifetime', $settings['session_max_lifetime'] ?? '') === '8 jam' ? 'selected' : '' }}>8 jam</option>
                                <option value="12 jam" {{ old('session_max_lifetime', $settings['session_max_lifetime'] ?? '') === '12 jam' ? 'selected' : '' }}>12 jam</option>
                                <option value="24 jam" {{ old('session_max_lifetime', $settings['session_max_lifetime'] ?? '') === '24 jam' ? 'selected' : '' }}>24 jam</option>
                            </select>
                        </div>

                        <div>
                            <label for="password_min_length" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Panjang minimum kata sandi
                            </label>
                            <input type="text" id="password_min_length" name="password_min_length"
                                   value="{{ old('password_min_length', $settings['password_min_length'] ?? '12 karakter') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>

                        <div>
                            <label for="admin_2fa_enabled" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Autentikasi tambahan admin
                            </label>
                            <select id="admin_2fa_enabled" name="admin_2fa_enabled" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="Diaktifkan" {{ old('admin_2fa_enabled', $settings['admin_2fa_enabled'] ?? '') === 'Diaktifkan' ? 'selected' : '' }}>Diaktifkan</option>
                                <option value="Dinonaktifkan" {{ old('admin_2fa_enabled', $settings['admin_2fa_enabled'] ?? '') === 'Dinonaktifkan' ? 'selected' : '' }}>Dinonaktifkan</option>
                            </select>
                        </div>
                    </div>

                    <p class="text-[11px] klinika-muted pt-1">
                        Nilai awal simulasi. Kata sandi tidak pernah ditampilkan atau dikirim dalam bentuk teks biasa. Menonaktifkan akun mencabut seluruh sesi akun tersebut.
                    </p>
                </div>

                {{-- Card 3: Undangan dan reset kata sandi --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Undangan dan reset kata sandi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="invitation_expiry" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Undangan aktivasi
                            </label>
                            <input type="text" id="invitation_expiry" name="invitation_expiry"
                                   value="{{ old('invitation_expiry', $settings['invitation_expiry'] ?? '24 jam') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>

                        <div>
                            <label for="password_reset_expiry" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Reset kata sandi
                            </label>
                            <input type="text" id="password_reset_expiry" name="password_reset_expiry"
                                   value="{{ old('password_reset_expiry', $settings['password_reset_expiry'] ?? '60 menit') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>
                    </div>

                    <p class="text-[11px] klinika-muted pt-1">
                        Tautan aktivasi dan reset memiliki masa berlaku. Tidak ada kata sandi yang ditampilkan di pengaturan ini.
                    </p>
                </div>

                {{-- Card 4: Notifikasi administrasi --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Notifikasi administrasi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="notify_access_request" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Permintaan akses baru
                            </label>
                            <select id="notify_access_request" name="notify_access_request" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="Dalam aplikasi dan email" {{ old('notify_access_request', $settings['notify_access_request'] ?? '') === 'Dalam aplikasi dan email' ? 'selected' : '' }}>☑ Dalam aplikasi dan email</option>
                                <option value="Dalam aplikasi saja" {{ old('notify_access_request', $settings['notify_access_request'] ?? '') === 'Dalam aplikasi saja' ? 'selected' : '' }}>☑ Dalam aplikasi saja</option>
                                <option value="Dinonaktifkan" {{ old('notify_access_request', $settings['notify_access_request'] ?? '') === 'Dinonaktifkan' ? 'selected' : '' }}>Dinonaktifkan</option>
                            </select>
                        </div>

                        <div>
                            <label for="notify_role_change" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Perubahan role / status akun
                            </label>
                            <select id="notify_role_change" name="notify_role_change" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="Dalam aplikasi" {{ old('notify_role_change', $settings['notify_role_change'] ?? '') === 'Dalam aplikasi' ? 'selected' : '' }}>☑ Dalam aplikasi</option>
                                <option value="Dalam aplikasi dan email" {{ old('notify_role_change', $settings['notify_role_change'] ?? '') === 'Dalam aplikasi dan email' ? 'selected' : '' }}>☑ Dalam aplikasi dan email</option>
                                <option value="Dinonaktifkan" {{ old('notify_role_change', $settings['notify_role_change'] ?? '') === 'Dinonaktifkan' ? 'selected' : '' }}>Dinonaktifkan</option>
                            </select>
                        </div>

                        <div>
                            <label for="security_digest" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Ringkasan keamanan
                            </label>
                            <select id="security_digest" name="security_digest" required
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                    onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                                <option value="Harian • 17.00 WIB" {{ old('security_digest', $settings['security_digest'] ?? '') === 'Harian • 17.00 WIB' ? 'selected' : '' }}>Harian • 17.00 WIB</option>
                                <option value="Mingguan • Senin 09.00 WIB" {{ old('security_digest', $settings['security_digest'] ?? '') === 'Mingguan • Senin 09.00 WIB' ? 'selected' : '' }}>Mingguan • Senin 09.00 WIB</option>
                                <option value="Dinonaktifkan" {{ old('security_digest', $settings['security_digest'] ?? '') === 'Dinonaktifkan' ? 'selected' : '' }}>Dinonaktifkan</option>
                            </select>
                        </div>

                        <div>
                            <label for="admin_email" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Email administrasi
                            </label>
                            <input type="email" id="admin_email" name="admin_email"
                                   value="{{ old('admin_email', $settings['admin_email'] ?? 'admin@klinika.test') }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>
                    </div>

                    <p class="text-[11px] klinika-muted pt-1">
                        Preferensi dan alamat penerima adalah contoh simulasi. Notifikasi hanya memuat informasi administratif, bukan isi rekam medis.
                    </p>
                </div>

                {{-- Footer actions --}}
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                        Simpan pengaturan
                    </button>
                    <a href="{{ route('admin.settings.index') }}"
                       class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                       style="border-color:var(--klinika-border);color:var(--klinika-text)">
                        Batal
                    </a>
                    <span class="text-xs klinika-muted flex-1 min-w-[200px]">
                        Perubahan konfigurasi disimpan untuk seluruh organisasi dan masuk ke audit aktivitas.
                    </span>
                </div>

            </form>
        </div>

        {{-- RIGHT COLUMN: Akses seluruh modul --}}
        <div class="lg:col-span-4 space-y-4">
            <div class="klinika-card p-6 space-y-4">
                <div>
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Akses seluruh modul</h3>
                    <p class="text-xs font-semibold text-slate-700 mt-2">Super Admin • Akses tertinggi</p>
                    <p class="text-xs klinika-muted mt-1 leading-relaxed">
                        Seluruh organisasi dan modul dapat diakses sesuai konteks tugas. Tautan berikut membuka ruang kerja modul, bukan isi pasien pada halaman ini.
                    </p>
                </div>

                <div class="divide-y divide-slate-100 border-t pt-2" style="border-color:var(--klinika-border)">

                    {{-- 1. Administrasi klinik --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Administrasi klinik</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Profil klinik, poli, tenaga kesehatan, jadwal</p>
                    </a>

                    {{-- 2. Pendaftaran --}}
                    <a href="{{ route('patients.index') }}" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Pendaftaran</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Pasien, kunjungan, antrean</p>
                    </a>

                    {{-- 3. Perawat --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Perawat</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Antrean dan pemeriksaan awal</p>
                    </a>

                    {{-- 4. Dokter / RME --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Dokter / RME</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Antrean, riwayat, pemeriksaan dan resep</p>
                    </a>

                    {{-- 5. Farmasi --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Farmasi</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Resep, obat, stok dan mutasi</p>
                    </a>

                    {{-- 6. Penagihan --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Penagihan</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Penyusunan tagihan, invoice dan pembayaran</p>
                    </a>

                    {{-- 7. Laporan --}}
                    <a href="#" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Laporan</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Transaksi, farmasi dan operasional</p>
                    </a>

                    {{-- 8. Pengguna & role --}}
                    <a href="{{ route('admin.users.index') }}" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Pengguna &amp; role</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Akun dan hak akses</p>
                    </a>

                    {{-- 9. Pengaturan sistem --}}
                    <a href="{{ route('admin.settings.index') }}" class="block py-2.5 group">
                        <span class="text-xs font-semibold flex items-center justify-between group-hover:text-teal-700 transition-colors" style="color:var(--klinika-text)">
                            <span>Pengaturan sistem</span>
                            <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </span>
                        <p class="text-[11px] klinika-muted mt-0.5">Konfigurasi global dan audit aktivitas</p>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- Footer Note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
