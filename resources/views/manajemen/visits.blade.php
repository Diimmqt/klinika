@extends('layouts.app')

@section('title', 'Statistik Kunjungan Pasien - Role Manajemen')

@section('breadcrumb')
<span class="text-xs font-medium klinika-muted">Manajemen / <span style="color:var(--klinika-text)">Statistik Kunjungan Pasien</span></span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-semibold tracking-tight" style="color:var(--klinika-text)">Statistik Kunjungan Pasien</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#EEF9F9; color:var(--klinika-primary)">Read-Only</span>
            </div>
            <p class="text-sm klinika-muted mt-0.5">Analisis volume kunjungan pasien baru, pasien lama, tren harian, dan sebaran poli klinik.</p>
        </div>

        <a href="{{ route('manajemen.index', ['period' => $stats['period']]) }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md border klinika-border bg-white text-xs font-medium hover:bg-slate-50"
           style="color:var(--klinika-text)">
            ← Kembali ke Laporan Klinik
        </a>
    </div>

    {{-- Filter Bar (Read-Only GET) --}}
    <form method="GET" action="{{ route('manajemen.visits') }}" class="klinika-card p-4 flex flex-wrap items-end gap-4">
        <div>
            <label for="period" class="block text-xs font-medium klinika-muted mb-1">Periode</label>
            <select id="period" name="period" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                @foreach($periods as $key => $meta)
                    <option value="{{ $key }}" @selected($stats['period'] === $key)>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="poli" class="block text-xs font-medium klinika-muted mb-1">Poli Tujuan</label>
            <select id="poli" name="poli" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($stats['selected_poli'] === 'semua')>Semua Poli</option>
                @foreach($poliList as $poliName)
                    <option value="{{ $poliName }}" @selected($stats['selected_poli'] === $poliName)>{{ $poliName }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white klinika-primary hover:opacity-90">
            Terapkan Filter
        </button>

        @if($stats['period'] !== '7_hari' || $stats['selected_poli'] !== 'semua')
            <a href="{{ route('manajemen.visits') }}" class="px-3 py-2 rounded-md border klinika-border text-sm klinika-muted hover:bg-slate-50">
                Reset
            </a>
        @endif
    </form>

    {{-- 4 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Total Kunjungan</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($stats['kpis']['total_kunjungan'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">{{ $stats['period_meta']['label'] }}</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Pasien Baru vs Lama</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($stats['kpis']['pasien_baru'], 0, ',', '.') }} / {{ number_format($stats['kpis']['pasien_lama'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">Baru • Lama (kunjungan ulang)</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Rata-rata Harian</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($stats['kpis']['rata_harian'], 1, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">Kunjungan per hari</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Tingkat Penyelesaian</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-primary)">{{ $stats['kpis']['tingkat_selesai_persen'] }}%</div>
            <div class="text-xs klinika-muted mt-1">Kunjungan tuntas hingga selesai</div>
        </div>
    </div>

    {{-- Distribusi Poli + Tren Harian --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Distribusi Kunjungan per Poli</h2>
                <p class="text-xs klinika-muted">Proporsi pasien baru, pasien lama, dan rata-rata durasi konsultasi.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                            <th class="px-5 py-3 font-medium">Poli</th>
                            <th class="px-5 py-3 font-medium">Total</th>
                            <th class="px-5 py-3 font-medium">Baru / Lama</th>
                            <th class="px-5 py-3 font-medium">Durasi</th>
                            <th class="px-5 py-3 font-medium">Porsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DFE7EA]">
                        @foreach($stats['by_poli'] as $row)
                            <tr>
                                <td class="px-5 py-3.5 font-medium" style="color:var(--klinika-text)">{{ $row['poli'] }}</td>
                                <td class="px-5 py-3.5 font-semibold">{{ number_format($row['kunjungan'], 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 klinika-muted">{{ $row['pasien_baru'] }} / {{ $row['pasien_lama'] }}</td>
                                <td class="px-5 py-3.5 klinika-muted">{{ $row['avg_durasi_menit'] }} mnt</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:{{ $row['persentase'] }}%; background-color:var(--klinika-primary)"></div>
                                        </div>
                                        <span class="text-xs klinika-muted">{{ $row['persentase'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Tren Kunjungan Mingguan</h2>
                <p class="text-xs klinika-muted">Rincian volume pasien harian per poli.</p>
            </div>
            <div class="p-5 space-y-3">
                @php
                    $maxTotal = max(1, max(array_column($stats['daily_trend'], 'total')));
                @endphp
                @foreach($stats['daily_trend'] as $day)
                    @php
                        $pct = round(($day['total'] / $maxTotal) * 100);
                    @endphp
                    <div class="grid grid-cols-12 items-center gap-3 text-xs">
                        <div class="col-span-2 font-medium" style="color:var(--klinika-text)">{{ $day['hari'] }}</div>
                        <div class="col-span-7">
                            <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full" style="width:{{ $pct }}%; background-color:var(--klinika-primary)"></div>
                            </div>
                        </div>
                        <div class="col-span-3 text-right klinika-muted font-medium">{{ $day['total'] }} pasien</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Demographic Breakdown --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
            <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Sebaran Kelompok Usia Pasien</h2>
            <p class="text-xs klinika-muted">Agregasi demografi pasien untuk perencanaan kebutuhan layanan klinik.</p>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($stats['demographics'] as $demo)
                <div class="rounded-lg border klinika-border p-4 bg-white">
                    <div class="text-xs font-medium klinika-muted">{{ $demo['kelompok'] }}</div>
                    <div class="text-xl font-semibold mt-1.5" style="color:var(--klinika-text)">{{ number_format($demo['jumlah'], 0, ',', '.') }} pasien</div>
                    <div class="mt-2 w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full" style="width:{{ $demo['persen'] }}%; background-color:var(--klinika-primary)"></div>
                    </div>
                    <div class="text-xs klinika-muted mt-1">{{ $demo['persen'] }}% dari total kunjungan</div>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-3 border-t klinika-border text-xs klinika-muted flex items-center justify-between" style="background-color:#F4F7F8">
            <span>Data simulasi • Bukan data pasien nyata | Klinika RME</span>
            <span>Akses: Read-Only</span>
        </div>
    </div>
</div>
@endsection

