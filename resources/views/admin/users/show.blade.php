@extends('layouts.app')

@section('title', 'Detail Akun Pengguna')
@section('page-title', 'Daftar akun')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Daftar akun</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Akun pengguna</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">{{ $user->name }}</span>
@endsection

@php
    $words = explode(' ', trim($user->name));
    $initials = count($words) >= 2
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
        : strtoupper(substr($user->name, 0, 2));

    $primaryRole = $user->roles->first()?->name ?? 'User';
    $roleKey = strtolower($primaryRole);

    $roleMatrix = [
        'super_admin' => [
            'role_label' => 'Super Admin • Role sistem',
            'pasien' => 'Lihat, tambah, ubah, hapus (semua klinik)',
            'kunjungan' => 'Akses penuh lintas unit & klinik',
            'antrean' => 'Akses penuh seluruh antrean',
            'klinis' => 'Akses penuh konfigurasi, pengawasan, dan master data',
            'admin' => 'Akses penuh akun, role, audit, dan seluruh organisasi',
        ],
        'pendaftaran' => [
            'role_label' => 'Pendaftaran • Role sistem',
            'pasien' => 'Lihat, tambah, ubah',
            'kunjungan' => 'Lihat, tambah, ubah',
            'antrean' => 'Lihat, tambah, ubah',
            'klinis' => 'Tidak ada akses SOAP, resep, stok, atau pembayaran',
            'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
        ],
        'dokter' => [
            'role_label' => 'Dokter • Role sistem',
            'pasien' => 'Lihat rekam medis & riwayat pasien',
            'kunjungan' => 'Pemeriksaan klinis kunjungan aktif',
            'antrean' => 'Panggil antrean poli terkait',
            'klinis' => 'Isi SOAP, diagnosis, tindakan, dan pembuatan resep',
            'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
        ],
        'perawat' => [
            'role_label' => 'Perawat • Role sistem',
            'pasien' => 'Lihat antrean dan asesmen awal',
            'kunjungan' => 'Asesmen awal & skrining',
            'antrean' => 'Panggil antrean pemeriksaan awal',
            'klinis' => 'Input tanda vital dan keluhan utama (tanpa resep)',
            'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
        ],
        'kasir' => [
            'role_label' => 'Kasir • Role sistem',
            'pasien' => 'Lihat data identitas pasien',
            'kunjungan' => 'Lihat status kunjungan & tagihan',
            'antrean' => 'Lihat antrean pembayaran kasir',
            'klinis' => 'Membuat tagihan, memproses pembayaran, cetak kwitansi',
            'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
        ],
        'apoteker' => [
            'role_label' => 'Apoteker • Role sistem',
            'pasien' => 'Lihat identitas & riwayat resep',
            'kunjungan' => 'Lihat resep kunjungan aktif',
            'antrean' => 'Panggil antrean pengambilan obat',
            'klinis' => 'Telaah resep, dispensing obat, kelola stok farmasi',
            'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
        ],
    ];

    $matrix = $roleMatrix[$roleKey] ?? [
        'role_label' => $primaryRole . ' • Role sistem',
        'pasien' => $user->can('patient.view') ? 'Lihat data pasien' : 'Tidak ada akses',
        'kunjungan' => $user->can('visit.view') ? 'Lihat kunjungan' : 'Tidak ada akses',
        'antrean' => $user->can('queue.view') ? 'Lihat antrean' : 'Tidak ada akses',
        'klinis' => 'Sesuai izin spesifik role',
        'admin' => 'Tidak ada akses akun, role, audit, atau lintas klinik',
    ];
@endphp

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Detail akun pengguna</h2>
            <p class="text-sm klinika-muted mt-1">
                Identitas dan akses efektif {{ $user->name }}. Semua aksi administratif tercatat.
            </p>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary transition-colors shrink-0">
            <span>Edit akun</span>
        </a>
    </div>

    {{-- User profile banner --}}
    <div class="klinika-card p-5 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold text-base tracking-wide"
                 style="background:#E6F5F5;color:var(--klinika-primary)">
                {{ $initials }}
            </div>
            <div>
                <h3 class="font-bold text-base leading-tight" style="color:var(--klinika-text)">{{ $user->name }}</h3>
                <p class="text-xs klinika-muted mt-1">
                    {{ $user->email }} • USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }} • Email terverifikasi
                </p>
            </div>
        </div>
        <div>
            @if ($user->is_active)
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="background:#ecfdf5;color:#065f46">Aktif</span>
            @else
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="background:#FFF3CD;color:#856404">Nonaktif</span>
            @endif
        </div>
    </div>

    {{-- Main content 2-columns --}}
    <div class="flex flex-col lg:flex-row gap-5 items-start">

        {{-- LEFT COLUMN --}}
        <div class="flex-1 space-y-5 min-w-0">

            {{-- Card 1: Akses efektif --}}
            <div class="klinika-card p-6 space-y-4">
                <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Akses efektif</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-2">
                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Role</span>
                        <span class="text-sm font-bold" style="color:var(--klinika-text)">{{ $matrix['role_label'] }}</span>
                    </div>
                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Scope klinik</span>
                        <span class="text-sm font-bold" style="color:var(--klinika-text)">KSS-01 • Sehat Sentosa Bandung</span>
                    </div>
                </div>

                <div class="rounded-lg border overflow-hidden" style="border-color:var(--klinika-border)">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                                <th class="py-3 px-4 w-1/3">Modul</th>
                                <th class="py-3 px-4">Izin efektif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--klinika-border)">
                            <tr>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Pasien</td>
                                <td class="py-3.5 px-4 klinika-muted">{{ $matrix['pasien'] }}</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Kunjungan</td>
                                <td class="py-3.5 px-4 klinika-muted">{{ $matrix['kunjungan'] }}</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Antrean</td>
                                <td class="py-3.5 px-4 klinika-muted">{{ $matrix['antrean'] }}</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Klinis &amp; keuangan</td>
                                <td class="py-3.5 px-4 klinika-muted">{{ $matrix['klinis'] }}</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Administrasi sistem</td>
                                <td class="py-3.5 px-4 klinika-muted">{{ $matrix['admin'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="text-[11px] klinika-muted">
                    Akses = izin role ∩ scope klinik ∩ status akun. Tidak ada izin tambahan langsung pada akun.
                </p>
            </div>

            {{-- Card 2: Riwayat audit ringkas --}}
            <div class="klinika-card p-6 space-y-4">
                <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Riwayat audit ringkas</h3>

                <div class="rounded-lg border overflow-hidden" style="border-color:var(--klinika-border)">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                                <th class="py-3 px-4 w-1/4">Waktu (WIB)</th>
                                <th class="py-3 px-4 w-1/4">Pelaku</th>
                                <th class="py-3 px-4">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--klinika-border)">
                            <tr>
                                <td class="py-3.5 px-4 klinika-muted">
                                    {{ $user->last_login_at ? $user->last_login_at->translatedFormat('j M, H.i') : '6 Okt, 08.12' }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">{{ $user->name }}</td>
                                <td class="py-3.5 px-4 klinika-muted">Login berhasil • KSS-01</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 klinika-muted">5 Okt, 15.20</td>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">Fajar Pratama</td>
                                <td class="py-3.5 px-4 klinika-muted">Klinik: KBS-02 &rarr; KSS-01 • mutasi loket</td>
                            </tr>
                            <tr>
                                <td class="py-3.5 px-4 klinika-muted">
                                    {{ $user->created_at ? $user->created_at->translatedFormat('j M, H.i') : '1 Okt, 09.10' }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold" style="color:var(--klinika-text)">{{ $user->name }}</td>
                                <td class="py-3.5 px-4 klinika-muted">Aktivasi: Menunggu aktivasi &rarr; Aktif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <a href="#" class="inline-flex items-center gap-1 text-xs font-semibold hover:opacity-80 transition-opacity" style="color:var(--klinika-primary)">
                        <span>Lihat audit lengkap &rarr;</span>
                    </a>
                </div>
            </div>

        </div>{{-- end left --}}

        {{-- RIGHT COLUMN (Sidebar) --}}
        <div class="w-full lg:w-72 shrink-0 space-y-4">

            {{-- Card 1: Informasi akun --}}
            <div class="klinika-card p-5 space-y-4">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Informasi akun</h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Dibuat pada</span>
                        <span class="font-semibold" style="color:var(--klinika-text)">
                            {{ $user->created_at ? $user->created_at->translatedFormat('j M Y, H.i') . ' WIB' : '1 Okt 2026, 08.30 WIB' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Login terakhir</span>
                        <span class="font-semibold" style="color:var(--klinika-text)">
                            {{ $user->last_login_at ? $user->last_login_at->translatedFormat('j M Y, H.i') . ' WIB' : '6 Okt 2026, 08.12 WIB' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Kata sandi</span>
                        <p class="klinika-muted mb-2">Tersimpan aman; tidak dapat dilihat</p>
                        <a href="{{ route('admin.users.reset-password', $user) }}"
                           class="block w-full py-2 px-3 rounded-md border text-xs font-semibold transition-colors bg-white hover:bg-slate-50 text-center"
                           style="border-color:var(--klinika-border);color:var(--klinika-text)">
                            Kirim tautan reset
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card 2: Nonaktifkan akses --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Nonaktifkan akses</h3>
                <p class="text-xs klinika-muted leading-relaxed">
                    Hentikan login dan cabut sesi aktif. Riwayat rekam medis dan transaksi tetap tersimpan.
                </p>

                @if ($user->is_active)
                    <a href="{{ route('admin.users.deactivate', $user) }}"
                       class="block w-full py-2.5 px-4 rounded-md text-white text-xs font-semibold text-center transition-colors"
                       style="background:#9F1239"
                       onmouseover="this.style.background='#881337'"
                       onmouseout="this.style.background='#9F1239'">
                        Nonaktifkan akun
                    </a>
                @else
                    <div class="p-2.5 rounded-md text-center text-xs font-semibold" style="background:#F4F7F8;color:var(--klinika-muted)">
                        Akun saat ini Nonaktif
                    </div>
                @endif
            </div>

            {{-- Card 3: Data tetap terlindungi --}}
            <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Data tetap terlindungi</p>
                        <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                            Role administratif tidak memberikan akses isi pelayanan individual secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

        </div>{{-- end right --}}

    </div>{{-- end flex --}}

    {{-- Footer note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
