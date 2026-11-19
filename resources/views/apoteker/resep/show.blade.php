@extends('layouts.app')

@section('title', 'Resep ' . $rx)
@section('page-title', 'Resep ' . $rx)

@section('breadcrumb')
    <span>Beranda</span>
    <span class="mx-1">/</span>
    <a href="{{ route('apoteker.resep.index') }}" class="hover:underline">Daftar resep</a>
    <span class="mx-1">/</span>
    <span>{{ $rx }}</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:var(--klinika-text)">Resep {{ $rx }}</h2>
            <p class="text-sm klinika-muted mt-0.5">RME final 10.05 • dr. Andi Prasetyo • Verifikasi apt. Rina Susanti</p>
        </div>
        <a href="{{ route('apoteker.resep.index') }}"
           class="px-4 py-2 rounded-lg border klinika-border bg-white text-sm font-medium klinika-muted hover:bg-gray-50 transition-colors shrink-0">
            ← Daftar resep
        </a>
    </div>

    {{-- Patient Card --}}
    <div class="bg-white rounded-xl border klinika-border p-5 space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="text-lg font-bold" style="color:var(--klinika-text)">Siti Aminah</h3>
                <p class="text-xs klinika-muted mt-0.5">RM-000128 • Perempuan • 14 Mei 1988 (38 tahun) • NIK 3273015405880002</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border border-teal-200 bg-teal-50 text-teal-800 self-start sm:self-center">
                Dalam pelayanan • Menunggu farmasi
            </span>
        </div>
        <div class="pt-3 border-t klinika-border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
            <span class="klinika-muted">
                KJ-20261006-023 • 6 Okt 2026 • 09.12 | Poli Umum | dr. Andi Prasetyo | Umum / pribadi | A-023
            </span>
            <a href="#" class="font-semibold klinika-primary-text hover:underline shrink-0">
                Lihat riwayat →
            </a>
        </div>
    </div>

    {{-- Status Badges Row --}}
    <div class="flex items-center gap-2.5 flex-wrap text-xs">
        <span class="px-3 py-1 rounded-full font-medium border border-teal-200 bg-teal-50 text-teal-800">
            RME: final
        </span>
        <span class="px-3 py-1 rounded-full font-medium border border-teal-200 bg-teal-50 text-teal-800">
            Resep: siap diserahkan
        </span>
        <span class="px-3 py-1 rounded-full font-medium border border-gray-200 bg-white text-gray-600">
            Penyerahan: belum dicatat
        </span>
        <span class="klinika-muted text-xs ml-1">
            Identitas dan alergi telah diverifikasi. Informasi dosis adalah contoh data simulasi.
        </span>
    </div>

    {{-- Item Resep Dokter Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Item resep dari dokter • hanya-baca</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Obat / kekuatan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Jumlah</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Aturan pakai</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Durasi</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Harga</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Paracetamol 500 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">10 tablet</td>
                        <td class="py-3.5 px-5">
                            <div class="text-gray-800">3×1 bila perlu</div>
                            <div class="text-xs klinika-muted">Sesudah makan</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">3 hari</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp2.000</td>
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-teal)">Rp20.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Cetirizine 10 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">5 tablet</td>
                        <td class="py-3.5 px-5">
                            <div class="text-gray-800">1×1 malam</div>
                            <div class="text-xs klinika-muted">Oral</div>
                        </td>
                        <td class="py-3.5 px-5 klinika-muted">5 hari</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp5.000</td>
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-teal)">Rp25.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t klinika-border text-sm klinika-muted">
            Total obat: <strong class="font-bold text-gray-900">Rp45.000</strong> • Tidak termasuk tarif pelayanan Rp100.000
        </div>
    </div>

    {{-- Batch dan Kesiapan Obat Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Batch dan kesiapan obat</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Obat</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Batch</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kedaluwarsa</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Stok tersedia</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Jumlah disiapkan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kesiapan</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Paracetamol 500 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">PCM-2607-A</td>
                        <td class="py-3.5 px-5 klinika-muted">31 Jul 2027</td>
                        <td class="py-3.5 px-5 klinika-muted">240 tablet</td>
                        <td class="py-3.5 px-5 klinika-muted">10 tablet</td>
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-teal)">Siap</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Cetirizine 10 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">CTZ-2608-B</td>
                        <td class="py-3.5 px-5 klinika-muted">31 Agu 2027</td>
                        <td class="py-3.5 px-5 klinika-muted">12 tablet</td>
                        <td class="py-3.5 px-5 klinika-muted">5 tablet</td>
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-teal)">Siap</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t klinika-border flex flex-wrap gap-5 text-sm">
            <label class="flex items-center gap-2 cursor-default">
                <input type="checkbox" checked disabled class="rounded border-gray-300 text-teal-600 focus:ring-0">
                <span class="text-gray-700 text-xs">Pasien / RM sesuai</span>
            </label>
            <label class="flex items-center gap-2 cursor-default">
                <input type="checkbox" checked disabled class="rounded border-gray-300 text-teal-600 focus:ring-0">
                <span class="text-gray-700 text-xs">Jumlah dan label sesuai</span>
            </label>
            <label class="flex items-center gap-2 cursor-default">
                <input type="checkbox" checked disabled class="rounded border-gray-300 text-teal-600 focus:ring-0">
                <span class="text-gray-700 text-xs">Edukasi penyerahan diberikan</span>
            </label>
            <label class="flex items-center gap-2 cursor-default">
                <input type="checkbox" checked disabled class="rounded border-gray-300 text-teal-600 focus:ring-0">
                <span class="text-gray-700 text-xs">Batch sesuai resep</span>
            </label>
        </div>
    </div>

    {{-- 2-col Catatan & Linimasa --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border klinika-border p-5 space-y-3">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Catatan apoteker</h3>
            <div>
                <label class="block text-xs font-semibold klinika-muted mb-1.5">Catatan penyerahan</label>
                <textarea rows="4" class="w-full border klinika-border rounded-lg p-3 text-sm focus:outline-none focus:border-teal-600 leading-relaxed" style="color:var(--klinika-text)">Resep diverifikasi; obat disiapkan sesuai batch. Identitas dikonfirmasi sebelum penyerahan.</textarea>
            </div>
        </div>

        <div class="bg-white rounded-xl border klinika-border p-5 space-y-3">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Linimasa resep</h3>
            <div class="space-y-2 text-sm pt-1">
                <div class="flex items-center gap-2 text-gray-700">
                    <span class="font-medium">10.05</span>
                    <span class="klinika-muted">•</span>
                    <span>Resep diterima</span>
                </div>
                <div class="flex items-center gap-2 text-gray-700">
                    <span class="font-medium">10.08</span>
                    <span class="klinika-muted">•</span>
                    <span>Verifikasi selesai</span>
                </div>
                <div class="flex items-center gap-2 text-gray-700">
                    <span class="font-medium">10.12</span>
                    <span class="klinika-muted">•</span>
                    <span>Obat siap</span>
                </div>
                <div class="text-xs klinika-muted italic pt-1">
                    Penyerahan: belum dicatat
                </div>
            </div>
        </div>
    </div>

    {{-- Notice Box --}}
    <div class="rounded-xl border border-teal-200 bg-teal-50/70 p-4 flex items-start gap-3 text-sm">
        <span class="text-teal-600 font-bold shrink-0 mt-0.5">ⓘ</span>
        <div>
            <span class="font-semibold" style="color:var(--klinika-teal)">Catat penyerahan hanya setelah obat diterima pasien</span>
            <span class="klinika-muted ml-1">— Tindakan mengurangi stok 10 dan 5 tablet, menandai resep Diserahkan, serta mengarahkan kunjungan ke kasir.</span>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap items-center gap-3">
        <button type="button" class="px-5 py-2.5 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity">
            Catat penyerahan obat
        </button>
        <button type="button" class="px-5 py-2.5 rounded-lg border klinika-border bg-white text-sm font-semibold klinika-muted hover:bg-gray-50 transition-colors">
            Cetak label obat
        </button>
    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
