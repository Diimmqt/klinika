@extends('layouts.app')

@section('title', 'Stok Obat')
@section('page-title', 'Stok obat')

@section('breadcrumb')
    <span>Beranda</span>
    <span class="mx-1">/</span>
    <span>Stok obat</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:var(--klinika-text)">Stok obat</h2>
            <p class="text-sm klinika-muted mt-0.5">Saldo setelah penyerahan RX-20261006-023 pukul 10.15.</p>
        </div>
        <a href="#form-penerimaan"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Penerimaan stok
        </a>
    </div>

    {{-- Amber Alert Box --}}
    <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-4 flex items-start gap-3 text-sm">
        <span class="text-amber-500 font-bold shrink-0 mt-0.5">ⓘ</span>
        <div>
            <span class="font-semibold text-amber-900">2 item di bawah minimum</span>
            <span class="text-amber-800 ml-1">Cetirizine: 7 tablet, minimum 20. Oralit: 18 sachet, minimum 30. Pertimbangkan pengadaan sesuai prosedur klinik.</span>
        </div>
    </div>

    {{-- Persediaan per batch Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Persediaan per batch</h3>

            {{-- Search & Filters --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3.5 top-3 klinika-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Cari obat atau batch"
                        class="w-full border klinika-border rounded-lg pl-10 pr-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div class="flex gap-3">
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>Semua stok</option>
                        <option>Stok rendah</option>
                        <option>Stok cukup</option>
                    </select>
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>Urut: kedaluwarsa</option>
                        <option>Urut: nama</option>
                        <option>Urut: saldo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Obat</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Batch</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kedaluwarsa</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Saldo / minimum</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Status</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Paracetamol 500 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">PCM-2607-A</td>
                        <td class="py-3.5 px-5 klinika-muted">31 Jul 2027</td>
                        <td class="py-3.5 px-5 klinika-muted">230 / 50 tablet</td>
                        <td class="py-3.5 px-5 klinika-muted">Stok cukup</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.stok.index', ['mutasi' => 'PCM-2607-A']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Mutasi →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Cetirizine 10 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">CTZ-2608-B</td>
                        <td class="py-3.5 px-5 klinika-muted">31 Agu 2027</td>
                        <td class="py-3.5 px-5 klinika-muted">7 / 20 tablet</td>
                        <td class="py-3.5 px-5 font-medium text-orange-600">Stok rendah</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.stok.index', ['mutasi' => 'CTZ-2608-B']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Mutasi →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-semibold" style="color:var(--klinika-text)">Oralit sachet</td>
                        <td class="py-3.5 px-5 klinika-muted">ORT-2606-A</td>
                        <td class="py-3.5 px-5 klinika-muted">30 Jun 2027</td>
                        <td class="py-3.5 px-5 klinika-muted">18 / 30 sachet</td>
                        <td class="py-3.5 px-5 font-medium text-orange-600">Stok rendah</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.stok.index', ['mutasi' => 'ORT-2606-A']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Mutasi →
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2 text-xs klinika-muted">
            <span>Menampilkan 1–3 dari 3 data simulasi</span>
            <div class="flex items-center gap-2">
                <span class="hover:underline cursor-pointer">Sebelumnya</span>
                <span class="px-2 py-0.5 rounded font-semibold text-white klinika-primary">1</span>
                <span class="hover:underline cursor-pointer">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Mutasi Card (Always visible as mockup) --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Mutasi • Cetirizine 10 mg</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Waktu</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Sumber</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Masuk</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Keluar</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 klinika-muted">5 Okt • 15.20</td>
                        <td class="py-3.5 px-5 klinika-muted">Saldo sebelumnya</td>
                        <td class="py-3.5 px-5 klinika-muted">—</td>
                        <td class="py-3.5 px-5 klinika-muted">—</td>
                        <td class="py-3.5 px-5 font-medium" style="color:var(--klinika-text)">12</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 klinika-muted">6 Okt • 10.15</td>
                        <td class="py-3.5 px-5 klinika-muted">RX-20261006-023</td>
                        <td class="py-3.5 px-5 klinika-muted">0</td>
                        <td class="py-3.5 px-5 klinika-muted">5</td>
                        <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">7</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Penerimaan batch baru Card (Always visible as mockup) --}}
    <div id="form-penerimaan" class="bg-white rounded-xl border klinika-border p-6 space-y-4">
        <div>
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Penerimaan batch baru</h3>
            <p class="text-xs klinika-muted mt-0.5">Draf penerimaan • saldo utama belum berubah. Cocokkan obat, dokumen sumber, batch dan kedaluwarsa sebelum menyimpan.</p>
        </div>

        <form method="POST" action="{{ route('apoteker.stok.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Obat *</label>
                    <select name="obat" class="w-full border klinika-border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                        <option selected>Cetirizine 10 mg</option>
                        <option>Paracetamol 500 mg</option>
                        <option>Oralit sachet</option>
                        <option>NaCl Botol</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Satuan</label>
                    <input type="text" value="Tablet" disabled
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm bg-gray-50 klinika-muted cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Dokumen sumber *</label>
                    <input type="text" name="dokumen" value="PO-20261006-004"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Jumlah *</label>
                    <input type="text" name="jumlah" value="100"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Batch *</label>
                    <input type="text" name="batch" value="CTZ-2610-C"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Tanggal kedaluwarsa *</label>
                    <input type="text" name="kadaluwarsa" placeholder="Isi tanggal pada kemasan"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
            </div>

            {{-- Notice Box --}}
            <div class="rounded-xl border border-teal-200 bg-teal-50/70 p-4 flex items-start gap-3 text-sm">
                <span class="text-teal-600 font-bold shrink-0 mt-0.5">ⓘ</span>
                <div>
                    <span class="font-semibold" style="color:var(--klinika-teal)">Tinjau penerimaan</span>
                    <span class="klinika-muted ml-1">— 100 tablet akan masuk ke batch CTZ-2610-C. Saldo obat akan berubah dari 7 menjadi 107 tablet setelah berhasil disimpan; ini bukan saldo batch lama.</span>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan penerimaan
                </button>
                <a href="{{ route('apoteker.stok.index') }}" class="px-5 py-2.5 rounded-lg border klinika-border bg-white text-sm font-semibold klinika-muted hover:bg-gray-50 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
