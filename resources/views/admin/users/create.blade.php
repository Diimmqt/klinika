@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah pengguna')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Daftar akun</a>
    <span class="mx-1">/</span>
    <span>Tambah pengguna</span>
@endsection

@section('content')
<div class="space-y-6 max-w-4xl">

    {{-- Page heading --}}
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Tambah pengguna</h2>
        <p class="text-xs klinika-muted mt-0.5">
            Buat akun langsung. Super Admin menetapkan identitas, role, dan kata sandi awal. Pengguna dapat login langsung tanpa aktivasi email.
        </p>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="p-3.5 rounded-lg border flex items-start gap-3 bg-rose-50 border-rose-200">
        <svg class="w-4 h-4 mt-0.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs text-rose-800">
            <p class="font-semibold">Periksa kembali isian form:</p>
            <p class="mt-0.5">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" id="form-tambah-user" class="space-y-5">
        @csrf

        {{-- Card 1: Identitas pengguna --}}
        <div class="klinika-card p-5 space-y-3">
            <div>
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Identitas pengguna</h3>
                <p class="text-xs klinika-muted mt-0.5">Gunakan identitas unik untuk pengguna. Email atau username digunakan sebagai identitas login.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label for="name" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                        Nama lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Nadia Putri"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                        Email atau username <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="nadia@klinika.test"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none @error('email') border-rose-500 @enderror" style="color:var(--klinika-text)">
                    <p class="text-[11px] klinika-muted mt-1">Digunakan sebagai identitas login. Pastikan unik untuk setiap pengguna.</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Akses dan kata sandi --}}
        <div class="klinika-card p-5 space-y-4">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Akses dan kata sandi</h3>

            <div>
                <label for="role_select" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                    Role <span class="text-rose-500">*</span>
                </label>
                <select id="role_select" name="roles[]" required
                        class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                    @php
                        $roleOrder = [
                            'admin_klinik' => 'Admin Klinik',
                            'pendaftaran'  => 'Pendaftaran',
                            'perawat'      => 'Perawat',
                            'dokter'       => 'Dokter',
                            'apoteker'     => 'Apoteker',
                            'kasir'        => 'Kasir',
                            'manajemen'    => 'Manajemen',
                            'super_admin'  => 'Super Admin',
                        ];
                    @endphp
                    @foreach ($roleOrder as $rName => $rLabel)
                        <option value="{{ $rName }}" {{ old('roles.0', 'pendaftaran') === $rName ? 'selected' : '' }}>
                            {{ $rLabel }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] klinika-muted mt-1">
                    Pilih role yang sesuai untuk pengguna. Opsi tersedia: Admin Klinik, Pendaftaran, Perawat, Dokter, Apoteker, Kasir, Manajemen.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                <div>
                    <label for="password" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                        Kata sandi awal <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none @error('password') border-rose-500 @enderror" style="color:var(--klinika-text)">
                    <p class="text-[11px] klinika-muted mt-1">Gunakan minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                        Konfirmasi kata sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                           placeholder="••••••••"
                           class="w-full px-3 py-2 rounded-lg border text-xs bg-white focus:outline-none {{ $errors->has('password') ? 'border-rose-500' : 'klinika-border' }}" style="color:var(--klinika-text)">
                    @error('password')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Bottom action bar --}}
        <div class="flex items-center justify-between pt-1">
            <span class="text-xs klinika-muted">* Wajib diisi</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-xs font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                    Simpan
                </button>
            </div>
        </div>

    </form>

    {{-- Card 3: Informasi akun yang dibuat --}}
    <div class="klinika-card p-5 space-y-2">
        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Informasi akun yang dibuat</h3>
        <p class="text-xs klinika-muted leading-relaxed">
            Setelah akun disimpan, status akun akan menjadi Aktif dan pengguna dapat login langsung menggunakan identitas login dan kata sandi awal.
        </p>

        <div class="pt-2 flex items-center gap-2 text-xs">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-teal-700 bg-teal-50 border border-teal-200">
                Aktif
            </span>
            <span class="klinika-muted">Akun dapat digunakan langsung tanpa aktivasi email. Email atau username hanya digunakan sebagai identitas login.</span>
        </div>
    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinik Sehat Sentosa &bull; KSS-01 &bull; Akses tercatat</span>
    </div>

</div>
@endsection
