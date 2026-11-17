@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail pengguna')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Daftar akun</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Akun pengguna</a>
    <span class="mx-1">/</span>
    <span>{{ $user->name }}</span>
@endsection

@php
    $words = explode(' ', trim($user->name));
    $initials = count($words) >= 2
        ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
        : strtoupper(substr($user->name, 0, 2));

    $primaryRole = $user->roles->first()?->name ?? 'pendaftaran';
    $roleLabels = [
        'super_admin'  => 'Super Admin',
        'admin_klinik' => 'Admin Klinik',
        'pendaftaran'  => 'Pendaftaran',
        'dokter'       => 'Dokter',
        'perawat'      => 'Perawat',
        'kasir'        => 'Kasir',
        'apoteker'     => 'Apoteker',
        'manajemen'    => 'Manajemen',
    ];
    $roleName = $roleLabels[$primaryRole] ?? ucfirst(str_replace('_', ' ', $primaryRole));
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Detail pengguna</h2>
            <p class="text-xs klinika-muted mt-0.5">
                Identitas, role, status, dan riwayat login {{ $user->name }}. Super Admin dapat mengedit pengguna dan menonaktifkan akses.
            </p>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}"
           class="px-4 py-2 rounded-lg text-white text-xs font-semibold klinika-primary hover:opacity-90 transition-opacity shrink-0 text-center">
            Edit pengguna
        </a>
    </div>

    {{-- User Profile Card --}}
    <div class="klinika-card p-5 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center font-bold text-base tracking-wide"
                 style="background:#E6F5F5;color:var(--klinika-primary)">
                {{ $initials }}
            </div>
            <div>
                <h3 class="font-bold text-sm leading-tight" style="color:var(--klinika-text)">{{ $user->name }}</h3>
                <p class="text-xs klinika-muted mt-0.5">
                    {{ $user->email }} &bull; USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}
                </p>
            </div>
        </div>
        <div>
            @if ($user->is_active)
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded text-emerald-700 bg-emerald-50 border border-emerald-200">Aktif</span>
            @else
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded text-slate-600 bg-slate-100 border border-slate-200">Nonaktif</span>
            @endif
        </div>
    </div>

    {{-- 2 Columns Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- LEFT COLUMN (2 cols) --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Detail akun --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Detail akun</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Role</span>
                        <span class="text-xs font-bold" style="color:var(--klinika-text)">{{ $roleName }} &bull; Role sistem</span>
                    </div>
                    <div>
                        <span class="text-xs klinika-muted block mb-0.5">Klinik</span>
                        <span class="text-xs font-bold" style="color:var(--klinika-text)">KSS-01 &bull; Sehat Sentosa Bandung</span>
                    </div>
                </div>

                <p class="text-xs klinika-muted pt-2 border-t klinika-border leading-relaxed">
                    Pengguna dapat login langsung tanpa aktivasi email. Perubahan role dan status tercatat di audit.
                </p>
            </div>

            {{-- Riwayat audit ringkas --}}
            <div class="klinika-card overflow-hidden">
                <div class="p-5 border-b klinika-border">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Riwayat audit ringkas</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="font-semibold uppercase tracking-wider border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                                <th class="py-3 px-5">Waktu (WIB)</th>
                                <th class="py-3 px-5">Pelaku</th>
                                <th class="py-3 px-5">Perubahan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y klinika-border">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-5 klinika-muted">6 Okt, 08.12</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">{{ $user->name }}</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">Login berhasil &bull; KSS-01</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-5 klinika-muted">5 Okt, 15.20</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">Fajar Pratama</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">Informasi akun diperbarui</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-5 klinika-muted">1 Okt, 08.30</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">Fajar Pratama</td>
                                <td class="py-3 px-5" style="color:var(--klinika-text)">Akun dibuat langsung &bull; Aktif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t klinika-border">
                    <a href="{{ route('admin.audits.index') }}" class="text-xs font-semibold klinika-primary-text hover:underline">
                        Lihat audit lengkap &rarr;
                    </a>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (1 col) --}}
        <div class="space-y-4">

            {{-- Informasi akun --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Informasi akun</h3>

                <div class="space-y-2.5 text-xs">
                    <div>
                        <span class="klinika-muted block">Dibuat pada</span>
                        <span class="font-medium" style="color:var(--klinika-text)">1 Okt 2026, 08.30 WIB</span>
                    </div>

                    <div>
                        <span class="klinika-muted block">Login terakhir</span>
                        <span class="font-medium" style="color:var(--klinika-text)">6 Okt 2026, 08.12 WIB</span>
                    </div>

                    <div>
                        <span class="klinika-muted block">Kata sandi</span>
                        <span class="font-medium" style="color:var(--klinika-text)">Tersimpan aman; tidak dapat dilihat</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="w-full inline-block text-center px-4 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                        Reset melalui Edit pengguna
                    </a>
                </div>
            </div>

            {{-- Nonaktifkan akun --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Nonaktifkan akun</h3>
                <p class="text-xs klinika-muted leading-relaxed">
                    Hentikan login dan cabut sesi aktif. Riwayat rekam medis dan transaksi tetap tersimpan.
                </p>
                <div>
                    <a href="{{ route('admin.users.deactivate', $user) }}"
                       class="w-full inline-block text-center px-4 py-2 rounded-lg text-xs font-semibold text-white bg-rose-700 hover:bg-rose-800 transition-colors">
                        Nonaktifkan akun
                    </a>
                </div>
            </div>

            {{-- Data tetap terlindungi --}}
            <div class="rounded-lg border p-3.5 bg-teal-50/60 border-teal-200 text-xs">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-teal-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <div>
                        <p class="font-bold text-teal-900">Data tetap terlindungi</p>
                        <p class="text-[11px] text-teal-800 mt-0.5 leading-relaxed">
                            Role administratif tidak memberikan akses isi pelayanan individual secara otomatis.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
