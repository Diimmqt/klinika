@extends('layouts.app')

@section('title', 'Nonaktifkan Akun')
@section('page-title', 'Nonaktifkan akun')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Daftar akun</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Akun pengguna</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.show', $user) }}" class="hover:underline klinika-primary-text">{{ $user->name }}</a>
    <span class="mx-1">/</span>
    <span>Nonaktifkan</span>
@endsection

@php
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

    {{-- Page heading --}}
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Nonaktifkan akun</h2>
        <p class="text-xs klinika-muted mt-0.5">
            Konfirmasi akses dan contoh penolakan tindakan untuk akun yang dilindungi.
        </p>
    </div>

    {{-- 2-Columns Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

        {{-- LEFT COLUMN (2 cols): Main deactivation card & form --}}
        <div class="lg:col-span-2 space-y-4">

            <div class="klinika-card p-6 space-y-4">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b klinika-border">
                    <div>
                        <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Nonaktifkan {{ $user->name }}?</h3>
                        <p class="text-xs font-semibold mt-1" style="color:var(--klinika-text)">
                            {{ $user->name }} &bull; {{ $user->email }}
                        </p>
                        <p class="text-[11px] klinika-muted">
                            {{ $roleName }} &bull; KSS-01
                        </p>
                    </div>
                    <div>
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded bg-amber-50 text-amber-700 border border-amber-200">
                            Aktif &rarr; Nonaktif
                        </span>
                    </div>
                </div>

                {{-- Amber Callout --}}
                <div class="rounded-lg border p-3.5 bg-amber-50/70 border-amber-200">
                    <div class="flex items-start gap-2.5 text-xs">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                        </svg>
                        <div>
                            <p class="font-bold text-amber-900">Login dan sesi akan dihentikan</p>
                            <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                Pengguna tidak dapat login. Semua sesi dan token akses dicabut setelah konfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Yang tetap tersimpan --}}
                <div class="space-y-1.5 text-xs">
                    <p class="font-semibold" style="color:var(--klinika-text)">Yang tetap tersimpan:</p>
                    <ul class="space-y-1 klinika-muted text-[11px]">
                        <li>&bull; Identitas penulis pada rekam medis / SOAP</li>
                        <li>&bull; Referensi pelaku pada transaksi dan pembayaran</li>
                        <li>&bull; Riwayat audit beserta perubahan akses</li>
                    </ul>
                </div>

                {{-- Form action --}}
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="space-y-4 pt-1">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="reason" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                            Alasan nonaktifkan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="3" required
                                  placeholder="Penugasan di klinik berakhir pada 6 Oktober 2026."
                                  class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none resize-none" style="color:var(--klinika-text)">Penugasan di klinik berakhir pada 6 Oktober 2026.</textarea>
                        <p class="text-[11px] klinika-muted mt-1">Alasan tercatat di audit, tidak menghapus data pelayanan.</p>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" id="confirm_deactivation" name="confirm_deactivation" value="1" required checked
                                   class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500">
                            <span class="text-xs" style="color:var(--klinika-text)">
                                Saya memahami akses dihentikan dan riwayat tetap tersimpan.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-5 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2 rounded-lg text-white text-xs font-semibold bg-rose-700 hover:bg-rose-800 transition-colors">
                            Ya, nonaktifkan akun
                        </button>
                    </div>

                    <p class="text-[11px] klinika-muted pt-2 border-t klinika-border">
                        Akun dapat diaktifkan kembali oleh pengelola berizin setelah penetapan akses ditinjau.
                    </p>
                </form>

            </div>

        </div>

        {{-- RIGHT COLUMN (1 col): Protection examples --}}
        <div class="space-y-4">

            {{-- Card 1: Ditolak: Super Admin aktif terakhir --}}
            <div class="klinika-card p-5 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ditolak: Super Admin aktif terakhir</h3>
                </div>

                <div>
                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200">
                        Akun dilindungi
                    </span>
                </div>

                <div>
                    <p class="text-xs font-semibold" style="color:var(--klinika-text)">
                        Fajar Pratama &bull; Super Admin
                    </p>
                </div>

                <div class="rounded-lg border p-3 bg-rose-50/60 border-rose-200 text-xs">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-rose-900">Tersisa 1 Super Admin aktif</p>
                            <p class="text-[11px] text-rose-800 mt-0.5 leading-relaxed">
                                Akun ini tidak dapat dinonaktifkan atau diturunkan rolenya. Pastikan ada Super Admin aktif lain yang dapat mengakses aplikasi.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" disabled
                            class="px-4 py-2 rounded-lg text-xs font-semibold bg-slate-100 text-slate-400 cursor-not-allowed">
                        Nonaktifkan akun
                    </button>
                </div>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t klinika-border">
                    Pemeriksaan diulang di server dalam transaksi; bukan hanya tombol yang dinonaktifkan.
                </p>
            </div>

            {{-- Card 2: Ditolak: kehilangan akses sendiri --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ditolak: kehilangan akses sendiri</h3>

                <div class="rounded-lg border p-3 bg-rose-50/60 border-rose-200 text-xs">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-rose-900">Akses akun Anda harus tetap tersedia</p>
                            <p class="text-[11px] text-rose-800 mt-0.5 leading-relaxed">
                                Fajar Pratama tidak dapat mencabut role atau scope miliknya sendiri hingga kehilangan akses pengelolaan, meskipun ada Super Admin lain.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" disabled
                            class="px-4 py-2 rounded-lg text-xs font-semibold bg-slate-100 text-slate-400 cursor-not-allowed">
                        Turunkan akses sendiri
                    </button>
                </div>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t klinika-border">
                    Minta Super Admin lain melakukan peninjauan dan perubahan akses.
                </p>
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
