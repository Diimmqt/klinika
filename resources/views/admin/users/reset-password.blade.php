@extends('layouts.app')

@section('title', 'Kirim Tautan Reset Kata Sandi')
@section('page-title', 'Klinik & pengguna')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Klinik & pengguna</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Akun pengguna</a>
    <span>/</span>
    <a href="{{ route('admin.users.show', $user) }}" class="hover:opacity-80" style="color:var(--klinika-primary)">{{ $user->name }}</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Reset kata sandi</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Kirim tautan reset kata sandi</h2>
        <p class="text-sm klinika-muted mt-1">
            Konfirmasi dan hasil pengiriman. Tidak ada kata sandi plaintext yang dibuat atau ditampilkan.
        </p>
    </div>

    {{-- Top 2-Columns Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

        {{-- LEFT COLUMN: Konfirmasi pengiriman --}}
        <div class="lg:col-span-7 space-y-4">
            <div class="klinika-card p-6 space-y-4">

                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Konfirmasi pengiriman</h3>
                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#F1F5F9;color:#475569">
                        Sebelum dikirim
                    </span>
                </div>

                <div>
                    <h4 class="font-bold text-base leading-tight" style="color:var(--klinika-text)">{{ $user->name }}</h4>
                    <p class="text-xs klinika-muted mt-1">
                        {{ $user->email }} • KSS-01 • {{ $user->is_active ? 'Akun Aktif' : 'Nonaktif' }}
                    </p>
                </div>

                {{-- Teal info callout --}}
                <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Tautan hanya untuk pemilik email</p>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                                Pengguna menetapkan kata sandi baru melalui tautan sekali pakai.<br>
                                Pengelola tidak dapat melihat kata sandinya.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Bullets --}}
                <ul class="space-y-1.5 text-xs klinika-muted leading-relaxed">
                    <li>• Tautan kedaluwarsa dalam 60 menit.</li>
                    <li>• Tautan reset sebelumnya dibatalkan.</li>
                    <li>• Pengiriman tidak mengubah role atau scope klinik.</li>
                    <li>• Sesi dicabut setelah kata sandi berhasil direset.</li>
                </ul>

                <form method="POST" action="{{ route('admin.users.send-reset-password', $user) }}" class="space-y-4 pt-2">
                    @csrf

                    <div>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" id="verified_identity" name="verified_identity" value="1" required checked
                                   class="w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            <span class="text-xs" style="color:var(--klinika-text)">
                                Saya telah memverifikasi identitas pengguna dan alamat email tujuan.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                           style="border-color:var(--klinika-border);color:var(--klinika-text)">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                            Kirim tautan reset
                        </button>
                    </div>

                    <p class="text-[11px] klinika-muted pt-2 border-t" style="border-color:var(--klinika-border)">
                        Pengiriman tercatat sebagai tindakan {{ auth()->user()?->name ?? 'Fajar Pratama' }}.
                    </p>
                </form>

            </div>
        </div>

        {{-- RIGHT COLUMN: Hasil pengiriman --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="klinika-card p-6 space-y-4">

                <div class="flex items-center justify-between gap-3">
                    <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Hasil pengiriman</h3>
                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#ECFDF5;color:#065F46">
                        Setelah berhasil
                    </span>
                </div>

                {{-- Green success callout --}}
                <div class="rounded-lg border p-4" style="background:#ECFDF5;border-color:#A7F3D0">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-emerald-900">Tautan reset berhasil dikirim</p>
                            <p class="text-[11px] text-emerald-800 mt-0.5 leading-relaxed">
                                Email dikirim ke {{ $user->email }} pada {{ session('sent_at') ? \Carbon\Carbon::parse(session('sent_at'))->translatedFormat('j M Y, H.i') : now()->translatedFormat('j M Y, H.i') }} WIB.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <p class="text-xs font-bold" style="color:var(--klinika-text)">
                        Berlaku hingga {{ session('sent_at') ? \Carbon\Carbon::parse(session('sent_at'))->addMinutes(60)->format('H.i') : now()->addMinutes(60)->format('H.i') }} WIB
                    </p>
                    <p class="text-xs klinika-muted leading-relaxed">
                        Status akun tetap Aktif. Kata sandi lama baru diganti saat pengguna menyelesaikan reset.
                    </p>
                </div>

                <div class="space-y-2 pt-2">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="block w-full py-2 px-3 rounded-md border text-xs font-semibold text-center transition-colors bg-white hover:bg-slate-50"
                       style="border-color:var(--klinika-border);color:var(--klinika-text)">
                        Kembali ke detail akun
                    </a>

                    <button type="button" disabled
                            class="block w-full py-2 px-3 rounded-md text-xs font-semibold text-center bg-slate-100 text-slate-400 cursor-not-allowed">
                        Kirim ulang • tunggu 60 detik
                    </button>
                </div>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t" style="border-color:var(--klinika-border)">
                    Pembatasan pengiriman melindungi pengguna dari permintaan berulang.
                </p>

            </div>
        </div>

    </div>

    {{-- Bottom Card: Perbedaan reset dan aktivasi --}}
    <div class="klinika-card p-6 space-y-4">
        <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Perbedaan reset dan aktivasi</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
            <div class="space-y-1">
                <span class="font-bold block" style="color:var(--klinika-text)">Aktif</span>
                <p class="klinika-muted leading-relaxed">
                    Kirim tautan reset 60 menit. Tidak pernah menampilkan sandi.
                </p>
            </div>
            <div class="space-y-1">
                <span class="font-bold block" style="color:var(--klinika-text)">Menunggu aktivasi</span>
                <p class="klinika-muted leading-relaxed">
                    Gunakan kirim ulang undangan aktivasi 24 jam, bukan reset.
                </p>
            </div>
            <div class="space-y-1">
                <span class="font-bold block" style="color:var(--klinika-text)">Nonaktif</span>
                <p class="klinika-muted leading-relaxed">
                    Reset tidak tersedia. Tinjau dan aktifkan kembali akun terlebih dahulu.
                </p>
            </div>
        </div>
    </div>

    {{-- Amber Warning Callout --}}
    <div class="rounded-lg border p-4" style="background:#FFF8ED;border-color:#F5D9A8">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold" style="color:#96611C">Email gagal terkirim?</p>
                <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#B07A30">
                    Akun dan sandi tidak berubah. Tampilkan pesan kegagalan dan tombol Coba lagi; jangan tampilkan token atau tautan rahasia di layar.
                </p>
            </div>
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
