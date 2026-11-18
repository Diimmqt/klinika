@extends('layouts.app')

@section('title', 'Daftar Resep')
@section('page-title', 'Daftar resep')

@section('breadcrumb')
    <span>Beranda</span>
    <span class="mx-1">/</span>
    <span>Daftar resep</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:var(--klinika-text)">Daftar resep</h2>
            <p class="text-sm klinika-muted mt-0.5">Hanya resep dari RME final yang masuk ke pelayanan farmasi.</p>
        </div>
        <button type="button" class="px-4 py-2 rounded-lg border klinika-border bg-white text-sm font-medium klinika-muted hover:bg-gray-50 transition-colors shrink-0">
            Muat ulang
        </button>
    </div>

    {{-- Stats Cards (4) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Menunggu verifikasi</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">6</p>
            <p class="text-xs klinika-muted mt-1">Perlu pemeriksaan apoteker</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Diracik</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">3</p>
            <p class="text-xs klinika-muted mt-1">Belum diserahkan</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Siap diserahkan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">2</p>
            <p class="text-xs klinika-muted mt-1">Konfirmasi identitas pasien</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Diserahkan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">28</p>
            <p class="text-xs klinika-muted mt-1">Stok dan tagihan diperbarui</p>
        </div>
    </div>

    {{-- Resep Hari Ini Table Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Resep hari ini</h3>

            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3.5 top-3 klinika-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Cari nomor resep, pasien, atau RM"
                        class="w-full border klinika-border rounded-lg pl-10 pr-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div class="flex gap-3">
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>Semua status</option>
                        <option>Menunggu verifikasi</option>
                        <option>Diracik</option>
                        <option>Siap diserahkan</option>
                        <option>Diserahkan</option>
                    </select>
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>6 Oktober 2026</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Resep / masuk</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Pasien / RM</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kunjungan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Dokter</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Status resep</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5">
                            <div class="font-semibold" style="color:var(--klinika-text)">RX-20261006-023</div>
                            <div class="text-xs klinika-muted">10.05</div>
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Siti Aminah</div>
                            <div class="text-xs klinika-muted">RM-000128</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-023</td>
                        <td class="py-3.5 px-5 klinika-muted">dr. Andi Prasetyo</td>
                        <td class="py-3.5 px-5 klinika-muted">Menunggu verifikasi</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-023') }}" class="font-semibold klinika-primary-text hover:underline">
                                Buka resep →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5">
                            <div class="font-semibold" style="color:var(--klinika-text)">RX-20261006-021</div>
                            <div class="text-xs klinika-muted">09.50</div>
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Dewi Kartika</div>
                            <div class="text-xs klinika-muted">RM-000130</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-021</td>
                        <td class="py-3.5 px-5 klinika-muted">dr. Laila Nuraini</td>
                        <td class="py-3.5 px-5 klinika-muted">Diracik</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-021') }}" class="font-semibold klinika-primary-text hover:underline">
                                Lanjutkan →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5">
                            <div class="font-semibold" style="color:var(--klinika-text)">RX-20261006-019</div>
                            <div class="text-xs klinika-muted">09.42</div>
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Hendra Wijaya</div>
                            <div class="text-xs klinika-muted">RM-000135</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-019</td>
                        <td class="py-3.5 px-5 klinika-muted">dr. Andi Prasetyo</td>
                        <td class="py-3.5 px-5 klinika-muted">Siap diserahkan</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-019') }}" class="font-semibold klinika-primary-text hover:underline">
                                Serahkan →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5">
                            <div class="font-semibold" style="color:var(--klinika-text)">RX-20261006-018</div>
                            <div class="text-xs klinika-muted">09.20</div>
                        </td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Lilis Suryani</div>
                            <div class="text-xs klinika-muted">RM-000136</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-018</td>
                        <td class="py-3.5 px-5 klinika-muted">dr. Andi Prasetyo</td>
                        <td class="py-3.5 px-5 klinika-muted">Diserahkan</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-018') }}" class="font-semibold klinika-primary-text hover:underline">
                                Detail →
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2 text-xs klinika-muted">
            <span>Menampilkan 1–4 dari 4 data simulasi</span>
            <div class="flex items-center gap-2">
                <span class="hover:underline cursor-pointer">Sebelumnya</span>
                <span class="px-2 py-0.5 rounded font-semibold text-white klinika-primary">1</span>
                <span class="hover:underline cursor-pointer">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Info Box --}}
    <div class="rounded-xl border border-teal-200 bg-teal-50/70 p-4 flex items-start gap-3 text-sm">
        <span class="text-teal-600 font-bold shrink-0 mt-0.5">ⓘ</span>
        <div>
            <span class="font-semibold" style="color:var(--klinika-teal)">Resep, antrean, dan tagihan memiliki status berbeda</span>
            <span class="klinika-muted ml-1">— Resep Diserahkan tidak berarti invoice Lunas. Setelah obat diserahkan, kunjungan berlanjut ke kasir.</span>
        </div>
    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
