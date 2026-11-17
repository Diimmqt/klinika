@extends('layouts.app')

@section('title', 'Role dan Hak Akses')
@section('page-title', 'Role dan hak akses')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span class="klinika-muted">Hak akses</span>
    <span class="mx-1">/</span>
    <span>Role</span>
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
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Role dan hak akses</h2>
            <p class="text-xs klinika-muted mt-0.5">
                Delapan role sistem dilindungi. Buat role kustom untuk kebutuhan operasional yang lebih spesifik.
            </p>
        </div>
        <a href="{{ route('admin.roles.create') }}"
           class="px-4 py-2 rounded-lg text-white text-xs font-semibold klinika-primary hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah role
        </a>
    </div>

    {{-- Teal Callout Banner --}}
    <div class="rounded-lg border p-3.5 bg-teal-50/60 border-teal-200">
        <div class="flex items-start gap-2.5 text-xs">
            <svg class="w-4 h-4 mt-0.5 shrink-0 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
            </svg>
            <div>
                <p class="font-bold text-teal-900">Role sistem tidak dapat diubah atau dihapus</p>
                <p class="text-[11px] text-teal-800 mt-0.5 leading-relaxed">
                    Super Admin mengelola organisasi dan izin. Isi SOAP, resep pasien, dan transaksi individual tidak terbuka secara default.
                </p>
            </div>
        </div>
    </div>

    {{-- Main Table Card: Daftar role --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Daftar role</h3>

            {{-- Filters Bar --}}
            <form method="GET" action="{{ route('admin.roles.index') }}" class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Cari role</label>
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="Nama atau deskripsi role"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Jenis role</label>
                    <select name="type" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua jenis</option>
                        <option value="system" {{ $type === 'system' ? 'selected' : '' }}>Sistem</option>
                        <option value="custom" {{ $type === 'custom' ? 'selected' : '' }}>Kustom</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Penggunaan</label>
                    <select name="usage" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="all" {{ $usage === 'all' ? 'selected' : '' }}>Semua penggunaan</option>
                        <option value="used" {{ $usage === 'used' ? 'selected' : '' }}>Digunakan</option>
                        <option value="unused" {{ $usage === 'unused' ? 'selected' : '' }}>Belum digunakan</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold uppercase tracking-wider border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                        <th class="py-3 px-5">Nama role</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Ringkasan akses</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse ($roles as $role)
                        @php
                            $isSystem = in_array(strtolower($role->name), $systemRoleNames);
                            $cleanKey = strtolower($role->name);
                            $summary = $roleSummaries[$cleanKey] ?? ($role->description ?: 'Konfigurasi izin operasional khusus');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">
                                {{ ucwords(str_replace('_', ' ', $role->name)) }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($isSystem)
                                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                        Sistem
                                    </span>
                                @else
                                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded bg-teal-50 text-teal-700 border border-teal-200">
                                        Kustom
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 klinika-muted">
                                {{ $role->users_count }} akun
                            </td>
                            <td class="py-3.5 px-4 klinika-muted leading-relaxed">
                                {{ $summary }}
                            </td>
                            <td class="py-3.5 px-5 text-right space-y-0.5">
                                @if ($isSystem)
                                    <a href="{{ route('admin.roles.show', $role) }}"
                                       class="font-semibold klinika-primary-text hover:underline inline-flex items-center gap-0.5">
                                        <span>Detail</span> &rarr;
                                    </a>
                                    <span class="text-[11px] klinika-muted block">Dilindungi</span>
                                @else
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                       class="font-semibold klinika-primary-text hover:underline inline-flex items-center gap-0.5">
                                        <span>Detail / Edit</span> &rarr;
                                    </a>
                                    @if ($role->users_count > 0)
                                        <span class="text-[11px] klinika-muted block">Hapus terkunci &bull; masih dipakai</span>
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
        <div class="px-5 py-3 border-t klinika-border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs klinika-muted">
            <div>
                {{ $stats['total'] }} role &bull; {{ $stats['system'] }} sistem dan {{ $stats['custom'] }} kustom &bull; {{ $stats['assignments'] }} penetapan akun, termasuk akun nonaktif dan menunggu aktivasi
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
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
