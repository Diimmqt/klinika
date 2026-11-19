@extends('layouts.app')

@section('title', 'Data Obat')
@section('page-title', 'Data obat')

@section('breadcrumb')
    <span>Beranda</span>
    <span class="mx-1">/</span>
    <span>Data obat</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:var(--klinika-text)">Data obat</h2>
            <p class="text-sm klinika-muted mt-0.5">Katalog obat, satuan, batas stok, dan tarif penjualan.</p>
        </div>
        <a href="{{ route('apoteker.obat.index') }}"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah obat
        </a>
    </div>

    {{-- Katalog Obat Card --}}
    <div class="bg-white rounded-xl border klinika-border overflow-hidden">
        <div class="p-5 border-b klinika-border space-y-4">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Katalog obat</h3>

            {{-- Search & Filters --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute left-3.5 top-3 klinika-muted">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" placeholder="Cari kode atau nama obat"
                        class="w-full border klinika-border rounded-lg pl-10 pr-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div class="flex gap-3">
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                    <select class="border klinika-border rounded-lg px-3 py-2 text-sm klinika-muted bg-white focus:outline-none focus:border-teal-600">
                        <option>Semua bentuk</option>
                        <option>Tablet</option>
                        <option>Sachet</option>
                        <option>Botol</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b klinika-border text-left">
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kode / nama</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Bentuk / satuan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Kekuatan</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Harga jual</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Status</th>
                        <th class="py-3 px-5 text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">OBT-001 • Paracetamol</td>
                        <td class="py-3.5 px-5 klinika-muted">Tablet</td>
                        <td class="py-3.5 px-5 klinika-muted">500 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp2.000</td>
                        <td class="py-3.5 px-5 klinika-muted">Aktif</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.obat.index', ['edit' => 'OBT-001']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Edit →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">OBT-002 • Cetirizine</td>
                        <td class="py-3.5 px-5 klinika-muted">Tablet</td>
                        <td class="py-3.5 px-5 klinika-muted">10 mg</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp5.000</td>
                        <td class="py-3.5 px-5 klinika-muted">Aktif</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.obat.index', ['edit' => 'OBT-002']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Edit →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">OBT-003 • Oralit</td>
                        <td class="py-3.5 px-5 klinika-muted">Sachet</td>
                        <td class="py-3.5 px-5 klinika-muted">200 mL larutan</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp3.000</td>
                        <td class="py-3.5 px-5 klinika-muted">Aktif</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.obat.index', ['edit' => 'OBT-003']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Edit →
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-5 font-bold" style="color:var(--klinika-text)">OBT-004 • NaCl</td>
                        <td class="py-3.5 px-5 klinika-muted">Botol</td>
                        <td class="py-3.5 px-5 klinika-muted">0,9% • 100 mL</td>
                        <td class="py-3.5 px-5 klinika-muted">Rp15.000</td>
                        <td class="py-3.5 px-5 klinika-muted">Aktif</td>
                        <td class="py-3.5 px-5">
                            <a href="{{ route('apoteker.obat.index', ['edit' => 'OBT-004']) }}" class="font-semibold klinika-primary-text hover:underline">
                                Edit →
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

    {{-- Form Edit Obat Card --}}
    <div class="bg-white rounded-xl border klinika-border p-6 space-y-4">
        <div>
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Edit obat • OBT-002</h3>
            <p class="text-xs klinika-muted mt-0.5">Contoh formulir inline; perubahan tidak menimpa harga pada invoice yang sudah terbit.</p>
        </div>

        <form method="POST" action="{{ route('apoteker.obat.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Nama generik *</label>
                    <input type="text" name="nama" value="Cetirizine"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Kekuatan *</label>
                    <input type="text" name="kekuatan" value="10 mg"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Bentuk / satuan *</label>
                    <select name="bentuk" class="w-full border klinika-border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                        <option selected>Tablet</option>
                        <option>Sachet</option>
                        <option>Botol</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Harga jual (Rp) *</label>
                    <input type="text" name="harga" value="5.000"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Stok minimum *</label>
                    <input type="text" name="min" value="20"
                        class="w-full border klinika-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                </div>
                <div>
                    <label class="block text-xs font-semibold klinika-muted mb-1.5">Status katalog *</label>
                    <select name="status" class="w-full border klinika-border rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-teal-600" style="color:var(--klinika-text)">
                        <option selected>Aktif</option>
                        <option>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan obat
                </button>
                <a href="{{ route('apoteker.obat.index') }}" class="px-5 py-2.5 rounded-lg border klinika-border bg-white text-sm font-semibold klinika-muted hover:bg-gray-50 transition-colors">
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
