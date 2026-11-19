@extends('layouts.app')

@section('title', 'Pelayanan Farmasi')
@section('page-title', 'Pelayanan farmasi')

@section('breadcrumb')
    <span>Beranda</span>
    <span class="mx-1">/</span>
    <span>Pelayanan farmasi</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:var(--klinika-text)">Pelayanan farmasi</h2>
            <p class="text-sm klinika-muted mt-0.5">Resep final yang masuk dan kesiapan persediaan obat.</p>
        </div>
        <a href="{{ route('apoteker.resep.index') }}"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            Buka daftar resep
        </a>
    </div>

    {{-- Stats Cards (4) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Menunggu verifikasi</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">6</p>
            <p class="text-xs klinika-muted mt-1">Resep dari RME final</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Sedang diracik</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">3</p>
            <p class="text-xs klinika-muted mt-1">Dalam proses apoteker</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Diserahkan hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">28</p>
            <p class="text-xs klinika-muted mt-1">Stok telah dikurangi</p>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <p class="text-xs klinika-muted font-medium">Stok di bawah minimum</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">2</p>
            <p class="text-xs klinika-muted mt-1">Perlu pengadaan</p>
        </div>
    </div>

    {{-- Amber Alert Box --}}
    <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-4 flex items-start gap-3 text-sm">
        <span class="text-amber-500 font-bold shrink-0 mt-0.5">ⓘ</span>
        <div>
            <span class="font-semibold text-amber-900">Persediaan perlu ditinjau</span>
            <span class="text-amber-800 ml-1">Oralit sachet: 18 dari minimum 30. Cetirizine 10 mg: 12 dari minimum 20.</span>
        </div>
    </div>

    {{-- Resep Terbaru Table Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Resep terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Resep</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Pasien / RM</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kunjungan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Status resep</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">RX-20261006-023</td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Siti Aminah</div>
                            <div class="text-xs klinika-muted">RM-000128</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-023</td>
                        <td class="py-3.5 px-5 klinika-muted">Menunggu verifikasi</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-023') }}" class="font-semibold klinika-primary-text hover:underline">
                                Verifikasi resep →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">RX-20261006-021</td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Dewi Kartika</div>
                            <div class="text-xs klinika-muted">RM-000130</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-021</td>
                        <td class="py-3.5 px-5 klinika-muted">Diracik</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-021') }}" class="font-semibold klinika-primary-text hover:underline">
                                Lanjutkan →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">RX-20261006-019</td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Hendra Wijaya</div>
                            <div class="text-xs klinika-muted">RM-000135</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-019</td>
                        <td class="py-3.5 px-5 klinika-muted">Siap diserahkan</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-019') }}" class="font-semibold klinika-primary-text hover:underline">
                                Serahkan →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">RX-20261006-018</td>
                        <td class="py-3.5 px-5">
                            <div class="font-medium" style="color:var(--klinika-text)">Lilis Suryani</div>
                            <div class="text-xs klinika-muted">RM-000136</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">KJ-20261006-018</td>
                        <td class="py-3.5 px-5 klinika-muted">Diserahkan</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.resep.show', 'RX-20261006-018') }}" class="font-semibold klinika-primary-text hover:underline">
                                Lihat detail →
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

    {{-- Bottom 2-col cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border klinika-border p-5">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Kontrol persediaan</h3>
            <p class="text-xs klinika-muted mt-2">86 item obat aktif • 124 batch tersimpan</p>
            <p class="text-xs klinika-muted mt-1">Batch mendekati kedaluwarsa: 3</p>
            <a href="{{ route('apoteker.stok.index') }}" class="inline-block mt-3 text-sm font-semibold klinika-primary-text hover:underline">
                Buka stok &amp; mutasi →
            </a>
        </div>
        <div class="bg-white rounded-xl border klinika-border p-5">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Aturan penyerahan</h3>
            <p class="text-xs klinika-muted mt-2 leading-relaxed">
                Verifikasi pasien, resep, jumlah, dan batch. Serahkan hanya resep final; pengurangan stok serta perubahan status dicatat dalam satu transaksi.
            </p>
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
