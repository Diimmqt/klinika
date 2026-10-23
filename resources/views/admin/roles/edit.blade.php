@extends('layouts.app')

@section('title', 'Edit ' . $role->name)
@section('page-title', 'Hak akses')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.roles.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Hak akses</a>
    <span>/</span>
    <a href="{{ route('admin.roles.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Role</a>
    <span>/</span>
    <a href="{{ route('admin.roles.show', $role) }}" class="hover:opacity-80" style="color:var(--klinika-primary)">{{ $role->name }}</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Edit</span>
@endsection

@section('content')
@php
    $isSystem = in_array($role->name, $systemRoleNames ?? []);
    $savedPermissions = $role->permissions->pluck('name')->toArray();
    $userCount = $role->users_count ?? $role->users->count();
@endphp

<div class="space-y-5">

    {{-- Page Heading --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">{{ $role->name }}</h2>
            <p class="text-sm klinika-muted mt-1">
                Role {{ $isSystem ? 'sistem' : 'kustom' }} • {{ $userCount }} pengguna • Versi 1 • Perubahan draf belum berlaku.
            </p>
        </div>
        <div>
            @if($isSystem)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                      style="background:#F1F5F9;color:#475569;border-color:#CBD5E1">
                    Sistem
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                      style="background:#E6F5F5;color:var(--klinika-primary);border-color:#B2DEDE">
                    Kustom
                </span>
            @endif
        </div>
    </div>

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

    <form method="POST" action="{{ route('admin.roles.update', $role) }}" id="form-edit-role" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Card 1: Informasi role --}}
        <div class="klinika-card p-6 space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Informasi role</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Nama role <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required
                           {{ $isSystem ? 'readonly' : '' }}
                           placeholder="Contoh: Koordinator Pendaftaran"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors {{ $isSystem ? 'cursor-not-allowed opacity-80' : '' }}"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    @if($isSystem)
                        <p class="text-[11px] klinika-muted mt-1">Nama role sistem dilindungi dan tidak dapat diubah.</p>
                    @endif
                </div>

                <div>
                    <label for="description" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Deskripsi <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="description" name="description" value="{{ old('description', $role->description) }}" required
                           placeholder="Koordinasi operasional loket pendaftaran klinik."
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                </div>
            </div>

            <p class="text-[11px] klinika-muted pt-1">
                Dibuat {{ $role->creator->name ?? 'Fajar Pratama' }} • {{ $role->created_at ? $role->created_at->format('j M Y, H.i') . ' WIB' : '5 Okt 2026, 10.00 WIB' }}. Scope klinik tetap mengikuti penetapan masing-masing akun.
            </p>
        </div>

        {{-- Card 2: Izin role • draf perubahan --}}
        <div class="klinika-card p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Izin role • draf perubahan</h3>
                    <p class="text-xs klinika-muted mt-0.5" id="matrix-diff-text">
                        <span id="saved-count">{{ count($savedPermissions) }}</span> izin tersimpan &rarr; <span id="draft-count">{{ count($savedPermissions) }}</span> izin pada draf
                    </p>
                </div>
                <div id="diff-chips-container" class="flex flex-wrap items-center gap-2">
                    {{-- Dynamically populated via JS --}}
                </div>
            </div>

            {{-- Matrix Table --}}
            <div class="overflow-x-auto border rounded-lg" style="border-color:var(--klinika-border)">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                            <th class="py-3 px-4 w-44">Modul</th>
                            <th class="py-3 px-3 text-center">Lihat</th>
                            <th class="py-3 px-3 text-center">Tambah</th>
                            <th class="py-3 px-3 text-center">Ubah</th>
                            <th class="py-3 px-3 text-center">Nonaktifkan</th>
                            <th class="py-3 px-3 text-center">Finalisasi</th>
                            <th class="py-3 px-3 text-center">Batalkan</th>
                            <th class="py-3 px-3 text-center">Ekspor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:var(--klinika-border)">

                        {{-- 1. Pasien --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Pasien</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.view" data-label="Lihat pasien"
                                       {{ in_array('patient.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.create" data-label="Tambah pasien"
                                       {{ in_array('patient.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.update" data-label="Ubah pasien"
                                       {{ in_array('patient.update', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.delete" data-label="Nonaktifkan pasien"
                                       {{ in_array('patient.delete', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 2. Kunjungan --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Kunjungan</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.view" data-label="Lihat kunjungan"
                                       {{ in_array('visit.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.create" data-label="Tambah kunjungan"
                                       {{ in_array('visit.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.update" data-label="Ubah kunjungan"
                                       {{ in_array('visit.update', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.delete" data-label="Nonaktifkan kunjungan"
                                       {{ in_array('visit.delete', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.cancel" data-label="Batalkan kunjungan"
                                       {{ in_array('visit.cancel', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 3. Antrean --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Antrean</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.view" data-label="Lihat antrean"
                                       {{ in_array('queue.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.create" data-label="Tambah antrean"
                                       {{ in_array('queue.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.call" data-label="Ubah antrean"
                                       {{ in_array('queue.call', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.cancel" data-label="Batalkan antrean"
                                       {{ in_array('queue.cancel', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 4. Pemeriksaan perawat --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Pemeriksaan perawat</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.view" data-label="Lihat perawat"
                                       {{ in_array('vital.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.create" data-label="Tambah perawat"
                                       {{ in_array('vital.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.update" data-label="Ubah perawat"
                                       {{ in_array('vital.update', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.finalize" data-label="Finalisasi perawat"
                                       {{ in_array('vital.finalize', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 5. RME / SOAP dokter --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">RME / SOAP dokter</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 6. Resep / farmasi --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Resep / farmasi</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 7. Stok obat --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Stok obat</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.view" data-label="Lihat stok obat"
                                       {{ in_array('medicine.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.create" data-label="Tambah stok obat"
                                       {{ in_array('medicine.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.dispense" data-label="Ubah stok obat"
                                       {{ in_array('medicine.dispense', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.export" data-label="Ekspor stok obat"
                                       {{ in_array('medicine.export', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                        </tr>

                        {{-- 8. Tagihan / pembayaran --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Tagihan / pembayaran</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                        </tr>

                        {{-- 9. Master data / jadwal --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Master data / jadwal</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.view" data-label="Lihat master data"
                                       {{ in_array('master.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.create" data-label="Tambah master data"
                                       {{ in_array('master.create', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.update" data-label="Ubah master data"
                                       {{ in_array('master.update', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.delete" data-label="Nonaktifkan master data"
                                       {{ in_array('master.delete', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 10. Laporan --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Laporan</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="report.view" data-label="Lihat laporan"
                                       {{ in_array('report.view', old('permissions', $savedPermissions)) ? 'checked' : '' }}
                                       class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                        </tr>

                        {{-- 11. Akun pengguna --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Akun pengguna</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 12. Role & hak akses --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Role &amp; hak akses</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 13. Audit aktivitas --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Audit aktivitas</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-medium">Terkunci</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-4 text-xs klinika-muted pt-1">
                <span class="flex items-center gap-1.5"><span class="font-bold text-slate-700">✓</span> Dipilih</span>
                <span class="flex items-center gap-1.5"><span class="font-bold text-slate-400">☐</span> Belum dipilih</span>
                <span><strong class="text-slate-600">Terkunci</strong> = di luar hak delegasi</span>
                <span><strong class="text-slate-400">N/A</strong> = tidak relevan</span>
            </div>
        </div>

        {{-- Card 3: Dampak perubahan izin --}}
        <div class="klinika-card p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Dampak perubahan izin</h3>
                    <p class="text-xs klinika-muted mt-0.5">
                        Ringkasan akun yang terikat pada role ini dan pengaruh langsung setelah perubahan disimpan.
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold"
                      style="background:#FFF8ED;color:#92400E;border:1px solid #FCD34D">
                    {{ $userCount }} Pengguna Terkait
                </span>
            </div>

            @if($role->users->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-1">
                    @foreach($role->users as $u)
                        <div class="flex items-center gap-3 p-3 rounded-lg border bg-slate-50/50" style="border-color:var(--klinika-border)">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs" style="background:#E6F5F5;color:var(--klinika-primary)">
                                {{ strtoupper(substr($u->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold truncate" style="color:var(--klinika-text)">{{ $u->name }}</p>
                                <p class="text-[11px] klinika-muted truncate">{{ $u->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-3.5 rounded-lg border text-xs klinika-muted" style="border-color:var(--klinika-border);background:#F8FAFC">
                    Saat ini belum ada akun pengguna yang menggunakan role ini. Perubahan hak akses akan berlaku saat akun baru ditugaskan ke role ini.
                </div>
            @endif

            <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Pembaruan hak akses berlaku seketika</p>
                        <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                            Setiap penambahan atau pencabutan izin modul akan langsung memengaruhi kapabilitas antarmuka dan otorisasi API pengguna di atas tanpa perlu login ulang.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer actions --}}
        <div class="flex items-center justify-between pt-1">
            <span class="text-xs klinika-muted">* Wajib diisi</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.roles.show', $role) }}"
                   class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                   style="border-color:var(--klinika-border);color:var(--klinika-text)">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

    {{-- Footer note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const savedPermissions = @json($savedPermissions);
    const chipsContainer = document.getElementById('diff-chips-container');
    const draftCountEl = document.getElementById('draft-count');

    const permLabels = {
        'patient.view': 'Lihat pasien',
        'patient.create': 'Tambah pasien',
        'patient.update': 'Ubah pasien',
        'patient.delete': 'Nonaktifkan pasien',
        'visit.view': 'Lihat kunjungan',
        'visit.create': 'Tambah kunjungan',
        'visit.update': 'Ubah kunjungan',
        'visit.delete': 'Nonaktifkan kunjungan',
        'visit.cancel': 'Batalkan kunjungan',
        'queue.view': 'Lihat antrean',
        'queue.create': 'Tambah antrean',
        'queue.call': 'Ubah antrean',
        'queue.cancel': 'Batalkan antrean',
        'vital.view': 'Lihat perawat',
        'vital.create': 'Tambah perawat',
        'vital.update': 'Ubah perawat',
        'vital.finalize': 'Finalisasi perawat',
        'medicine.view': 'Lihat obat',
        'medicine.create': 'Tambah obat',
        'medicine.dispense': 'Ubah obat',
        'medicine.export': 'Ekspor obat',
        'master.view': 'Lihat master',
        'master.create': 'Tambah master',
        'master.update': 'Ubah master',
        'master.delete': 'Nonaktifkan master',
        'report.view': 'Lihat laporan',
    };

    function updateDiff() {
        const checkedBoxes = Array.from(document.querySelectorAll('.perm-cb:checked'));
        const currentChecked = checkedBoxes.map(cb => cb.value);

        if (draftCountEl) {
            draftCountEl.textContent = currentChecked.length;
        }

        const added = currentChecked.filter(p => !savedPermissions.includes(p));
        const removed = savedPermissions.filter(p => !currentChecked.includes(p));

        if (!chipsContainer) return;
        chipsContainer.innerHTML = '';

        if (added.length === 0 && removed.length === 0) {
            return;
        }

        added.forEach(p => {
            const label = permLabels[p] || p;
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border';
            chip.style.backgroundColor = '#FEF3C7';
            chip.style.color = '#92400E';
            chip.style.borderColor = '#FCD34D';
            chip.textContent = '+ ' + label;
            chipsContainer.appendChild(chip);
        });

        removed.forEach(p => {
            const label = permLabels[p] || p;
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border';
            chip.style.backgroundColor = '#FFE4E6';
            chip.style.color = '#9F1239';
            chip.style.borderColor = '#FECDD3';
            chip.textContent = '- ' + label;
            chipsContainer.appendChild(chip);
        });
    }

    document.querySelectorAll('.perm-cb').forEach(cb => {
        cb.addEventListener('change', updateDiff);
    });

    updateDiff();
});
</script>
@endpush
