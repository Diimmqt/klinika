@extends('layouts.app')

@section('title', 'Nonaktifkan Akun')
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
    <span style="color:var(--klinika-text)">Nonaktifkan</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Nonaktifkan akun</h2>
        <p class="text-sm klinika-muted mt-1">
            Konfirmasi akses dan contoh penolakan tindakan untuk akun yang dilindungi.
        </p>
    </div>

    {{-- 2-Columns Layout --}}
    <div class="flex flex-col lg:flex-row gap-5 items-start">

        {{-- LEFT COLUMN: Main deactivation card & form --}}
        <div class="flex-1 space-y-4 min-w-0">

            <div class="klinika-card p-6 space-y-5">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b pb-4" style="border-color:var(--klinika-border)">
                    <div>
                        <h3 class="font-bold text-base" style="color:var(--klinika-text)">Nonaktifkan {{ $user->name }}?</h3>
                        <p class="text-sm font-semibold mt-1" style="color:var(--klinika-text)">
                            {{ $user->name }} • {{ $user->email }}
                        </p>
                        <p class="text-xs klinika-muted">
                            {{ $user->roles->first()?->name ?? 'Pendaftaran' }} • KSS-01
                        </p>
                    </div>
                    <div>
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded" style="background:#FFF3CD;color:#856404">
                            Aktif &rarr; Nonaktif
                        </span>
                    </div>
                </div>

                {{-- Amber Callout --}}
                <div class="rounded-lg border p-4" style="background:#FFF8ED;border-color:#F5D9A8">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#96611C">Login dan sesi akan dihentikan</p>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#B07A30">
                                Pengguna tidak dapat login. Semua sesi dan token akses dicabut setelah konfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Yang tetap tersimpan --}}
                <div class="space-y-1.5 text-xs">
                    <p class="font-semibold" style="color:var(--klinika-text)">Yang tetap tersimpan:</p>
                    <ul class="space-y-1 klinika-muted">
                        <li>• Identitas penulis pada rekam medis / SOAP</li>
                        <li>• Referensi pelaku pada transaksi dan pembayaran</li>
                        <li>• Riwayat audit beserta perubahan akses</li>
                    </ul>
                </div>

                {{-- Form action --}}
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="space-y-4 pt-2">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="reason" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Alasan nonaktifkan <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="reason" name="reason" rows="3" required
                                  placeholder="Penugasan di klinik berakhir pada 6 Oktober 2026."
                                  class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                  style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                  onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">Penugasan di klinik berakhir pada 6 Oktober 2026.</textarea>
                        <p class="text-[11px] klinika-muted mt-1">Alasan tercatat di audit, tidak menghapus data pelayanan.</p>
                    </div>

                    <div>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" id="confirm_deactivation" name="confirm_deactivation" value="1" required checked
                                   class="w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                            <span class="text-xs" style="color:var(--klinika-text)">
                                Saya memahami akses dihentikan dan riwayat tetap tersimpan.
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                           style="border-color:var(--klinika-border);color:var(--klinika-text)">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-md text-white text-sm font-medium transition-colors"
                                style="background:#9F1239"
                                onmouseover="this.style.background='#881337'"
                                onmouseout="this.style.background='#9F1239'">
                            Ya, nonaktifkan akun
                        </button>
                    </div>

                    <p class="text-xs klinika-muted pt-2 border-t" style="border-color:var(--klinika-border)">
                        Akun dapat diaktifkan kembali oleh pengelola berizin setelah penetapan akses ditinjau.
                    </p>
                </form>

            </div>

        </div>{{-- end left --}}

        {{-- RIGHT COLUMN (Sidebar info cards) --}}
        <div class="w-full lg:w-80 shrink-0 space-y-4">

            {{-- Card 1: Ditolak: Super Admin aktif terakhir --}}
            <div class="klinika-card p-5 space-y-3">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ditolak: Super Admin aktif terakhir</h3>
                </div>

                <div>
                    <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#FFE4E6;color:#9F1239">
                        Akun dilindungi
                    </span>
                </div>

                <div>
                    <p class="text-xs font-semibold" style="color:var(--klinika-text)">
                        Fajar Pratama • Super Admin
                    </p>
                </div>

                <div class="rounded-lg border p-3.5" style="background:#FFF1F2;border-color:#FECDD3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#E11D48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#9F1239">Tersisa 1 Super Admin aktif</p>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#BE123C">
                                Akun ini tidak dapat dinonaktifkan atau diturunkan rolenya. Pastikan ada Super Admin aktif lain yang telah terverifikasi.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" disabled
                            class="px-4 py-2 rounded-md text-xs font-semibold bg-slate-100 text-slate-400 cursor-not-allowed">
                        Nonaktifkan akun
                    </button>
                </div>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t" style="border-color:var(--klinika-border)">
                    Pemeriksaan diulang di server dalam transaksi; bukan hanya tombol yang dinonaktifkan.
                </p>
            </div>

            {{-- Card 2: Ditolak: kehilangan akses sendiri --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ditolak: kehilangan akses sendiri</h3>

                <div class="rounded-lg border p-3.5" style="background:#FFF1F2;border-color:#FECDD3">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#E11D48">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#9F1239">Akses akun Anda harus tetap tersedia</p>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#BE123C">
                                {{ auth()->user()?->name ?? 'Fajar Pratama' }} tidak dapat mencabut role atau scope miliknya sendiri hingga kehilangan akses pengelolaan, meskipun ada Super Admin lain.
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="button" disabled
                            class="px-4 py-2 rounded-md text-xs font-semibold bg-slate-100 text-slate-400 cursor-not-allowed">
                        Turunkan akses sendiri
                    </button>
                </div>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t" style="border-color:var(--klinika-border)">
                    Minta Super Admin lain melakukan peninjauan dan perubahan akses.
                </p>
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
