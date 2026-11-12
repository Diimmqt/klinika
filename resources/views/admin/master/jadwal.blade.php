@extends('layouts.app')

@section('title', 'Jadwal Dokter')
@section('page-title', 'Jadwal dokter')

@section('breadcrumb')
    <a href="{{ route('admin.operasional') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.master.poli') }}" class="hover:underline klinika-primary-text">Master data</a>
    <span class="mx-1">/</span>
    <span>Jadwal dokter</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Jadwal dokter</h2>
            <p class="text-sm klinika-muted mt-0.5">Jadwal aktif menjadi sumber pilihan dokter pada form kunjungan.</p>
        </div>
        <a href="#edit-section"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah jadwal
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card: Jadwal Selasa --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Jadwal Selasa</h3>

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('admin.master.jadwal-dokter') }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3 top-3 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama dokter"
                           class="w-full pl-9 pr-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none focus:ring-1" style="color:var(--klinika-text)">
                </div>

                <div class="w-full sm:w-48">
                    <select name="poli" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="">Semua poli</option>
                        <option value="Poli Umum" {{ $selectedPoli === 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Anak" {{ $selectedPoli === 'Poli Anak' ? 'selected' : '' }}>Poli Anak</option>
                        <option value="Poli Gigi" {{ $selectedPoli === 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    </select>
                </div>

                <div class="w-full sm:w-48">
                    <select name="date" onchange="this.form.submit()" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option selected>6 Oktober 2026</option>
                        <option>7 Oktober 2026</option>
                        <option>8 Oktober 2026</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Dokter / poli</th>
                        <th class="py-3 px-5 text-left">Hari / jam</th>
                        <th class="py-3 px-5 text-left">Ruang</th>
                        <th class="py-3 px-5 text-left">Terdaftar / kuota</th>
                        <th class="py-3 px-5 text-left">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($jadwalList as $j)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ isset($editItem['id']) && $editItem['id'] == $j['id'] ? 'bg-emerald-50/30' : '' }}">
                        <td class="py-3 px-5">
                            <div class="font-semibold text-xs" style="color:var(--klinika-text)">{{ $j['dokter'] }}</div>
                            <div class="text-[11px] klinika-muted mt-0.5">{{ $j['poli'] }}</div>
                        </td>
                        <td class="py-3 px-5 text-xs klinika-muted">{{ $j['hari_jam'] }}</td>
                        <td class="py-3 px-5 text-xs" style="color:var(--klinika-text)">{{ $j['ruang'] }}</td>
                        <td class="py-3 px-5 text-xs font-mono" style="color:var(--klinika-text)">{{ $j['terdaftar'] }}</td>
                        <td class="py-3 px-5 text-xs">
                            <span class="text-emerald-700 font-semibold">{{ $j['status'] }}</span>
                        </td>
                        <td class="py-3 px-5 text-right text-xs">
                            <a href="{{ route('admin.master.jadwal-dokter', ['edit_id' => $j['id']]) }}#edit-section"
                               class="font-semibold klinika-primary-text hover:underline inline-flex items-center gap-1">
                                <span>Edit</span> &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-xs klinika-muted">Tidak ada jadwal dokter ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2 text-xs klinika-muted">
            <span>Menampilkan 1–{{ count($jadwalList) }} dari {{ count($jadwalList) }} data simulasi</span>
            <div class="flex items-center gap-2">
                <span class="hover:underline cursor-pointer">Sebelumnya</span>
                <span class="px-2 py-0.5 rounded font-semibold text-white klinika-primary">1</span>
                <span class="hover:underline cursor-pointer">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Edit Form Card --}}
    <div id="edit-section" class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-4" style="color:var(--klinika-text)">Edit jadwal • {{ $editItem['dokter'] ?? 'dr. Andi Prasetyo' }}</h3>

        <form method="POST" action="{{ route('admin.master.jadwal-dokter.update') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Dokter *</label>
                    <select name="dokter" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="dr. Andi Prasetyo" {{ ($editItem['dokter'] ?? '') === 'dr. Andi Prasetyo' ? 'selected' : '' }}>dr. Andi Prasetyo</option>
                        <option value="dr. Laila Nuraini, Sp.A" {{ ($editItem['dokter'] ?? '') === 'dr. Laila Nuraini, Sp.A' ? 'selected' : '' }}>dr. Laila Nuraini, Sp.A</option>
                        <option value="drg. Nabila Sari" {{ ($editItem['dokter'] ?? '') === 'drg. Nabila Sari' ? 'selected' : '' }}>drg. Nabila Sari</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Poli *</label>
                    <select name="poli" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="Poli Umum" {{ ($editItem['poli'] ?? '') === 'Poli Umum' ? 'selected' : '' }}>Poli Umum</option>
                        <option value="Poli Anak" {{ ($editItem['poli'] ?? '') === 'Poli Anak' ? 'selected' : '' }}>Poli Anak</option>
                        <option value="Poli Gigi" {{ ($editItem['poli'] ?? '') === 'Poli Gigi' ? 'selected' : '' }}>Poli Gigi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Hari *</label>
                    <select name="hari" class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        <option value="Senin">Senin</option>
                        <option value="Selasa" selected>Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Mulai *</label>
                    <input type="text"
                           name="mulai"
                           value="{{ $editItem['mulai'] ?? '08.00' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Selesai *</label>
                    <input type="text"
                           name="selesai"
                           value="{{ $editItem['selesai'] ?? '12.00' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Kuota *</label>
                    <input type="number"
                           name="kuota"
                           value="{{ $editItem['kuota'] ?? 40 }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Ruang *</label>
                    <input type="text"
                           name="ruang"
                           value="{{ $editItem['ruang'] ?? '01' }}"
                           class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Periode berlaku *</label>
                <input type="text"
                       name="periode"
                       value="{{ $editItem['periode'] ?? '1 Oktober–31 Desember 2026' }}"
                       class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity">
                    Simpan jadwal
                </button>
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
