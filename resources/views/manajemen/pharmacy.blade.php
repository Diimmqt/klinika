@extends('layouts.app')

@section('title', 'Laporan Farmasi - Role Manajemen')

@section('breadcrumb')
<span class="text-xs font-medium klinika-muted">Manajemen / <span style="color:var(--klinika-text)">Laporan Farmasi</span></span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-semibold tracking-tight" style="color:var(--klinika-text)">Laporan Farmasi</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#EEF9F9; color:var(--klinika-primary)">Read-Only</span>
            </div>
            <p class="text-sm klinika-muted mt-0.5">Pemantauan ketersediaan stok obat, pemakaian resep mingguan, dan peringatan batas aman kadaluarsa.</p>
        </div>

        <a href="{{ route('manajemen.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md border klinika-border bg-white text-xs font-medium hover:bg-slate-50"
           style="color:var(--klinika-text)">
            ← Kembali ke Laporan Klinik
        </a>
    </div>

    {{-- Warning Banner for Low Stock & Near Expiry --}}
    @if($report['kpis']['stok_menipis'] > 0 || $report['kpis']['mendekati_ed'] > 0)
        <div class="rounded-lg border px-4 py-3 flex items-start gap-3 text-sm" style="background-color:#FFF8ED; border-color:#F3DFB8; color:#96611C">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <span class="font-semibold">Perhatian Persediaan Farmasi:</span>
                Terdapat {{ $report['kpis']['stok_menipis'] }} item obat dengan stok menipis dan {{ $report['kpis']['mendekati_ed'] }} item mendekati tanggal kedaluwarsa. Koordinasikan pengadaan dengan unit Farmasi / Admin Klinik.
            </div>
        </div>
    @endif

    {{-- 4 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Resep Minggu Ini</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($report['kpis']['total_resep_mingguan'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">{{ $report['kpis']['resep_terlayani_persen'] }}% telah ditebus</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Item Obat Aktif</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $report['kpis']['item_obat_aktif'] }}</div>
            <div class="text-xs klinika-muted mt-1">Katalog formularium klinik</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Stok Menipis</span>
            <div class="text-2xl font-semibold mt-2" style="color:#96611C">{{ $report['kpis']['stok_menipis'] }} Item</div>
            <div class="text-xs klinika-muted mt-1">Di bawah batas minimum stok</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Mendekati Kadaluarsa</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $report['kpis']['mendekati_ed'] }} Item</div>
            <div class="text-xs klinika-muted mt-1">Estimasi kurang dari 4 bulan</div>
        </div>
    </div>

    {{-- Filter Bar (Read-Only GET) --}}
    <form method="GET" action="{{ route('manajemen.pharmacy') }}" class="klinika-card p-4 flex flex-wrap items-end gap-4">
        <div>
            <label for="kategori" class="block text-xs font-medium klinika-muted mb-1">Kategori Obat</label>
            <select id="kategori" name="kategori" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($report['selected_category'] === 'semua')>Semua Kategori</option>
                @foreach($report['categories'] as $cat)
                    <option value="{{ $cat }}" @selected($report['selected_category'] === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-xs font-medium klinika-muted mb-1">Status Stok</label>
            <select id="status" name="status" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($report['selected_status'] === 'semua')>Semua Status</option>
                @foreach(['Aman', 'Menipis', 'Dekat ED'] as $st)
                    <option value="{{ $st }}" @selected($report['selected_status'] === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[200px]">
            <label for="q" class="block text-xs font-medium klinika-muted mb-1">Cari Obat</label>
            <input id="q" type="text" name="q" value="{{ $report['search'] }}" placeholder="Kode atau nama obat..."
                   class="w-full rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
        </div>

        <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white klinika-primary hover:opacity-90">
            Cari / Filter
        </button>

        @if($report['selected_category'] !== 'semua' || $report['selected_status'] !== 'semua' || $report['search'] !== '')
            <a href="{{ route('manajemen.pharmacy') }}" class="px-3 py-2 rounded-md border klinika-border text-sm klinika-muted hover:bg-slate-50">
                Reset
            </a>
        @endif
    </form>

    {{-- Top 5 Most Prescribed + Inventory Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Obat Paling Sering Diresepkan</h2>
                <p class="text-xs klinika-muted">Pemakaian tertinggi 7 hari terakhir.</p>
            </div>
            <div class="p-5 space-y-3.5">
                @php
                    $maxOut = max(1, max(array_column($report['top_prescribed'], 'keluar_7h')));
                @endphp
                @foreach($report['top_prescribed'] as $top)
                    @php
                        $pct = round(($top['keluar_7h'] / $maxOut) * 100);
                    @endphp
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium truncate pr-2" style="color:var(--klinika-text)">{{ $top['nama'] }}</span>
                            <span class="font-semibold shrink-0" style="color:var(--klinika-primary)">{{ $top['keluar_7h'] }} unit</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $pct }}%; background-color:var(--klinika-primary)"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-2 klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border flex items-center justify-between" style="background-color:#F4F7F8">
                <div>
                    <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Pemantauan Stok & Pergerakan Obat</h2>
                    <p class="text-xs klinika-muted">Laporan read-only inventaris farmasi klinik.</p>
                </div>
                <span class="text-xs klinika-muted">{{ count($report['items']) }} item</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                            <th class="px-5 py-3 font-medium">Kode</th>
                            <th class="px-5 py-3 font-medium">Nama Obat</th>
                            <th class="px-5 py-3 font-medium">Stok / Min</th>
                            <th class="px-5 py-3 font-medium">Keluar 7H</th>
                            <th class="px-5 py-3 font-medium">Kadaluarsa</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DFE7EA]">
                        @forelse($report['items'] as $item)
                            <tr>
                                <td class="px-5 py-3.5 font-mono text-xs klinika-muted">{{ $item['kode'] }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium" style="color:var(--klinika-text)">{{ $item['nama'] }}</div>
                                    <div class="text-xs klinika-muted">{{ $item['kategori'] }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold" style="color:var(--klinika-text)">{{ number_format($item['stok'], 0, ',', '.') }}</span>
                                    <span class="text-xs klinika-muted">/ {{ $item['min_stok'] }}</span>
                                </td>
                                <td class="px-5 py-3.5">{{ $item['keluar_7h'] }}</td>
                                <td class="px-5 py-3.5 text-xs klinika-muted">{{ $item['kadaluarsa'] }}</td>
                                <td class="px-5 py-3.5">
                                    @if($item['status'] === 'Aman')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#dcfce7; color:#166534">Aman</span>
                                    @elseif($item['status'] === 'Menipis')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fee2e2; color:#991b1b">Menipis</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fef9c3; color:#854d0e">{{ $item['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-sm klinika-muted">Tidak ada item obat yang sesuai dengan filter pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 border-t klinika-border text-xs klinika-muted flex items-center justify-between" style="background-color:#F4F7F8">
                <span>Data simulasi • Bukan data pasien nyata | Klinika RME</span>
                <span>Akses: Read-Only</span>
            </div>
        </div>
    </div>
</div>
@endsection

