@extends('layouts.app')

@section('title', 'Tambah Role Kustom')
@section('page-title', 'Hak akses')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.roles.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Hak akses</a>
    <span>/</span>
    <a href="{{ route('admin.roles.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Role</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Tambah role</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Tambah role kustom</h2>
        <p class="text-sm klinika-muted mt-1">
            Contoh pengisian sebelum Koordinator Pendaftaran dibuat. Mulai tanpa izin; pilih hanya yang diperlukan.
        </p>
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

    <form method="POST" action="{{ route('admin.roles.store') }}" id="form-tambah-role" class="space-y-5">
        @csrf

        {{-- Card 1: Identitas role --}}
        <div class="klinika-card p-6 space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Identitas role</h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-8">
                    <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Nama role <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', 'Koordinator Pendaftaran') }}" required
                           placeholder="Contoh: Koordinator Pendaftaran"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    <p class="text-[11px] klinika-muted mt-1">Nama unik; tidak boleh menggunakan nama role sistem.</p>
                </div>

                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Jenis role
                    </label>
                    <input type="text" value="Kustom" disabled
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm klinika-muted"
                           style="border-color:var(--klinika-border);background:#F4F7F8">
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                    Deskripsi <span class="text-rose-500">*</span>
                </label>
                <textarea id="description" name="description" rows="3" required
                          placeholder="Koordinasi pasien, kunjungan, dan antrean loket pendaftaran. Tanpa akses klinis, farmasi, keuangan, atau manajemen role."
                          class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                          style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                          onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">{{ old('description', 'Koordinasi pasien, kunjungan, dan antrean loket pendaftaran. Tanpa akses klinis, farmasi, keuangan, atau manajemen role.') }}</textarea>
            </div>
        </div>

        {{-- Teal Callout Box --}}
        <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
            <div class="flex items-start gap-2.5">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Scope klinik ditetapkan pada akun, bukan pada matriks izin</p>
                    <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                        Contoh penetapan: KSS-01 untuk Alya Intan dan Rika Amalia. Memilih izin tidak membuka data klinik lain.
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Matriks izin per modul --}}
        <div class="klinika-card p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-base" style="color:var(--klinika-text)">Matriks izin per modul</h3>
                    <p class="text-xs klinika-muted mt-0.5" id="matrix-summary-text">
                        <span id="selected-perm-count">9</span> izin dipilih • hanya pasien, kunjungan, dan antrean
                    </p>
                </div>
                <div>
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded" style="background:#E6F5F5;color:var(--klinika-primary)">
                        Bukan akses penuh
                    </span>
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
                                <input type="checkbox" name="permissions[]" value="patient.view" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.create" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.update" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="patient.delete" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 2. Kunjungan --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Kunjungan</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.view" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.create" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.update" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.delete" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="visit.cancel" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 3. Antrean --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Antrean</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.view" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.create" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.call" checked class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="queue.cancel" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 4. Pemeriksaan perawat --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Pemeriksaan perawat</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.view" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.create" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.update" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="vital.finalize" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
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
                                <input type="checkbox" name="permissions[]" value="medicine.view" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.create" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.dispense" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="medicine.export" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
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
                                <input type="checkbox" name="permissions[]" value="master.view" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.create" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.update" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="master.delete" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            </td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                            <td class="py-3 px-3 text-center text-slate-300">N/A</td>
                        </tr>

                        {{-- 10. Laporan --}}
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">Laporan</td>
                            <td class="py-3 px-3 text-center">
                                <input type="checkbox" name="permissions[]" value="report.view" class="perm-cb w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
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
                <span><strong class="text-slate-600">Terkunci</strong> = di luar hak delegasi pembuat</span>
                <span><strong class="text-slate-400">N/A</strong> = aksi tidak relevan</span>
            </div>

            {{-- Amber Callout Box --}}
            <div class="rounded-lg border p-4" style="background:#FFF8ED;border-color:#F5D9A8">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-xs font-semibold" style="color:#96611C">Hak sensitif tidak dipilih</p>
                        <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#B07A30">
                            SOAP, resep, transaksi individual, ekspor sensitif, akun, role, dan audit terkunci pada konfigurasi ini. Super Admin tidak mendapat akses isi tersebut secara default.
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-xs klinika-muted">
                Tidak ada "pilih semua". Izin yang dapat didelegasikan tetap diverifikasi server; izin sensitif hanya dapat diberikan oleh pemberi yang berwenang dan memerlukan konfirmasi.
            </p>
        </div>

        {{-- Footer actions --}}
        <div class="flex items-center justify-between pt-1">
            <span class="text-xs klinika-muted">* Wajib diisi</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.roles.index') }}"
                   class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                   style="border-color:var(--klinika-border);color:var(--klinika-text)">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                    Buat role kustom
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
function updateCount() {
    const checked = document.querySelectorAll('.perm-cb:checked').length;
    const countEl = document.getElementById('selected-perm-count');
    if (countEl) countEl.textContent = checked;
}

document.querySelectorAll('.perm-cb').forEach(cb => {
    cb.addEventListener('change', updateCount);
});
updateCount();
</script>
@endpush
