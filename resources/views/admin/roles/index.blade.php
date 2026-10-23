@extends('layouts.app')

@section('title', 'Role dan Hak Akses')
@section('page-title', 'Hak akses')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.roles.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Hak akses</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Role</span>
@endsection

@php
    $roleSummaries = [
        'super_admin' => 'Organisasi, akun, role, audit; bukan isi SOAP / resep / transaksi',
        'admin_klinik' => 'Administrasi satu klinik, jadwal, akun sesuai batas delegasi',
        'pendaftaran' => 'Pasien, kunjungan, antrean dalam klinik',
        'perawat' => 'Pemeriksaan awal, tanda vital, finalisasi catatan perawat',
        'dokter' => 'RME / SOAP, finalisasi pemeriksaan, resep dalam penugasan',
        'apoteker' => 'Resep, penyerahan obat, stok; tanpa SOAP lengkap',
        'kasir' => 'Tagihan, pembayaran, pembatalan sesuai kewenangan',
        'manajemen' => 'Laporan agregat klinik; bukan catatan klinis individual',
        'koordinator pendaftaran' => 'Pasien, kunjungan, antrean; tanpa akses klinis / keuangan',
        'petugas arsip administratif' => 'Lihat master data; belum diberikan kepada pengguna',
    ];
@endphp

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Role dan hak akses</h2>
            <p class="text-sm klinika-muted mt-1">
                Delapan role sistem dilindungi. Buat role kustom untuk kebutuhan operasional yang lebih spesifik.
            </p>
        </div>
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary transition-colors shrink-0">
            <span>+ Tambah role</span>
        </a>
    </div>

    {{-- Teal Callout Banner --}}
    <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Role sistem tidak dapat diubah atau dihapus</p>
                <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                    Super Admin mengelola organisasi dan izin. Isi SOAP, resep pasien, dan transaksi individual tidak terbuka secara default.
                </p>
            </div>
        </div>
    </div>

    {{-- Main Table Card: Daftar role --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-6 space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Daftar role</h3>

            {{-- Filters Bar --}}
            <form method="GET" action="{{ route('admin.roles.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Cari role</label>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Nama atau deskripsi role"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Jenis role</label>
                    <div class="relative">
                        <select name="type" onchange="this.form.submit()"
                                class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
                            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua jenis</option>
                            <option value="system" {{ $type === 'system' ? 'selected' : '' }}>Sistem</option>
                            <option value="custom" {{ $type === 'custom' ? 'selected' : '' }}>Kustom</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Penggunaan</label>
                    <div class="relative">
                        <select name="usage" onchange="this.form.submit()"
                                class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
                            <option value="all" {{ $usage === 'all' ? 'selected' : '' }}>Semua penggunaan</option>
                            <option value="used" {{ $usage === 'used' ? 'selected' : '' }}>Digunakan</option>
                            <option value="unused" {{ $usage === 'unused' ? 'selected' : '' }}>Belum digunakan</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto border-t" style="border-color:var(--klinika-border)">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                        <th class="py-3 px-5 w-44">Nama role</th>
                        <th class="py-3 px-4 w-24">Jenis</th>
                        <th class="py-3 px-4 w-28">Pengguna</th>
                        <th class="py-3 px-4">Ringkasan akses</th>
                        <th class="py-3 px-5 w-40 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse ($roles as $role)
                        @php
                            $isSystem = in_array(strtolower($role->name), $systemRoleNames);
                            $cleanKey = strtolower($role->name);
                            $summary = $roleSummaries[$cleanKey] ?? ($role->description ?: 'Konfigurasi izin operasional khusus');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5 font-bold" style="color:var(--klinika-text)">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($isSystem)
                                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#F1F5F9;color:#475569">
                                        Sistem
                                    </span>
                                @else
                                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#E6F5F5;color:var(--klinika-primary)">
                                        Kustom
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 klinika-muted">
                                {{ $role->users_count }} akun
                            </td>
                            <td class="py-4 px-4 klinika-muted leading-relaxed">
                                {{ $summary }}
                            </td>
                            <td class="py-4 px-5 space-y-0.5">
                                @if ($isSystem)
                                    <a href="{{ route('admin.roles.show', $role) }}"
                                       class="text-xs font-semibold inline-block hover:opacity-80"
                                       style="color:var(--klinika-primary)">
                                        Detail &rarr;
                                    </a>
                                    <span class="text-[11px] klinika-muted block">Dilindungi</span>
                                @else
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                       class="text-xs font-semibold inline-block hover:opacity-80"
                                       style="color:var(--klinika-primary)">
                                        Detail / Edit &rarr;
                                    </a>
                                    @if ($role->users_count > 0)
                                        <span class="text-[11px] klinika-muted block">Hapus terkunci • masih dipakai</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus role {{ addslashes($role->name) }}?');"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] text-rose-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center klinika-muted">
                                Tidak ada role yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table Footer Summary --}}
        <div class="px-5 py-3 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs klinika-muted"
             style="border-color:var(--klinika-border);background:#FAFCFC">
            <div>
                {{ $stats['total'] }} role • {{ $stats['system'] }} sistem dan {{ $stats['custom'] }} kustom • {{ $stats['assignments'] }} penetapan akun, termasuk akun nonaktif dan menunggu aktivasi
            </div>
            @if ($roles->hasPages())
                <div>
                    {{ $roles->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom 2-Panels Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="klinika-card p-5 space-y-1.5">
            <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Izin aksi &ne; scope klinik</h3>
            <p class="text-xs klinika-muted leading-relaxed">
                Role menentukan tindakan yang diizinkan. Penempatan akun menentukan klinik yang dapat diakses; keduanya diperiksa pada setiap request.
            </p>
        </div>

        <div class="klinika-card p-5 space-y-1.5">
            <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Batas delegasi</h3>
            <p class="text-xs klinika-muted leading-relaxed">
                Role kustom tidak boleh melebihi hak pembuat. Izin sensitif memerlukan peninjauan dan konfirmasi khusus.
            </p>
        </div>
    </div>

    {{-- Footer note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
