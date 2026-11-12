@extends('layouts.app')

@section('title', 'Master Poli')
@section('page-title', 'Master poli')

@section('breadcrumb')
    <a href="{{ route('admin.operasional') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span class="klinika-muted">Master data</span>
    <span class="mx-1">/</span>
    <span>Poli</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Master poli</h2>
            <p class="text-sm klinika-muted mt-0.5">Unit layanan dan kapasitas pendaftaran Klinik Sehat Sentosa.</p>
        </div>
        <a href="#edit-section"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah poli
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card: Poli Klinik --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Poli klinik</h3>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.master.poli') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3 top-3 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari kode atau nama poli"
                           class="w-full pl-9 pr-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none focus:ring-1" style="color:var(--klinika-text)">
                </div>

                <div class="w-full sm:w-48">
                    <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="">Semua status</option>
                        <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ $status === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="w-full sm:w-48">
                    <select name="clinic" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option selected>Klinik KSS-01</option>
                        <option>Klinik KBS-02</option>
                        <option>Klinik KMU-03</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Kode / nama</th>
                        <th class="py-3 px-5 text-left">Lokasi</th>
                        <th class="py-3 px-5 text-left">Kode antrean</th>
                        <th class="py-3 px-5 text-left">Kuota / hari</th>
                        <th class="py-3 px-5 text-left">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($poliList as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ isset($editItem['id']) && $editItem['id'] == $p['id'] ? 'bg-emerald-50/30' : '' }}">
                        <td class="py-3 px-5 font-semibold text-xs" style="color:var(--klinika-text)">
                            {{ $p['code'] }} • {{ $p['name'] }}
                        </td>
                        <td class="py-3 px-5 text-xs klinika-muted">{{ $p['location'] }}</td>
                        <td class="py-3 px-5 text-xs font-mono" style="color:var(--klinika-text)">{{ $p['prefix'] }}</td>
                        <td class="py-3 px-5 text-xs font-mono" style="color:var(--klinika-text)">{{ $p['quota'] }}</td>
                        <td class="py-3 px-5 text-xs">
                            <span class="text-emerald-700 font-semibold">{{ $p['status'] }}</span>
                        </td>
                        <td class="py-3 px-5 text-right text-xs">
                            <a href="{{ route('admin.master.poli', ['edit_id' => $p['id']]) }}#edit-section"
                               class="font-semibold klinika-primary-text hover:underline inline-flex items-center gap-1">
                                <span>Edit</span> &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-xs klinika-muted">Tidak ada data poli ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2 text-xs klinika-muted">
            <span>Menampilkan 1–{{ count($poliList) }} dari {{ count($poliList) }} data simulasi</span>
            <div class="flex items-center gap-2">
                <span class="hover:underline cursor-pointer">Sebelumnya</span>
                <span class="px-2 py-0.5 rounded font-semibold text-white klinika-primary">1</span>
                <span class="hover:underline cursor-pointer">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Edit Form Card --}}
    <div id="edit-section" class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-4" style="color:var(--klinika-text)">Edit poli • {{ $editItem['code'] ?? 'POL-01' }}</h3>

        <form method="POST" action="{{ route('admin.master.poli.update') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Nama poli *</label>
                    <input type="text"
                           name="name"
                           value="{{ $editItem['name'] ?? 'Poli Umum' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Lokasi layanan *</label>
                    <input type="text"
                           name="location"
                           value="{{ $editItem['location'] ?? 'Ruang 01' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Prefix antrean *</label>
                    <input type="text"
                           name="prefix"
                           value="{{ $editItem['prefix'] ?? 'A' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Kuota harian *</label>
                    <input type="number"
                           name="quota"
                           value="{{ $editItem['quota'] ?? 40 }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                    <p class="text-[11px] klinika-muted mt-1">Dibatasi kembali oleh kuota dokter.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Status *</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="Aktif" {{ ($editItem['status'] ?? '') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ ($editItem['status'] ?? '') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity">
                    Simpan poli
                </button>
                <a href="{{ route('admin.master.poli') }}"
                   class="px-5 py-2.5 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
