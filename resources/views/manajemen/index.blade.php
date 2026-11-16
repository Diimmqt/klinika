@extends('layouts.app')

@section('title', 'Laporan Klinik - Role Manajemen')

@section('breadcrumb')
<span class="text-xs font-medium klinika-muted">Manajemen / <span style="color:var(--klinika-text)">Laporan Klinik</span></span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header + Period Filter (Read-Only GET) --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-semibold tracking-tight" style="color:var(--klinika-text)">Laporan Klinik</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#EEF9F9; color:var(--klinika-primary)">Read-Only</span>
            </div>
            <p class="text-sm klinika-muted mt-0.5">{{ $report['period_meta']['subtitle'] }} • Ringkasan eksekutif pelayanan, keuangan, farmasi, dan operasional klinik.</p>
        </div>

        <form method="GET" action="{{ route('manajemen.index') }}" class="flex items-center gap-2">
            <label for="period" class="text-xs font-medium klinika-muted">Periode:</label>
            <select id="period" name="period" onchange="this.form.submit()"
                    class="rounded-md border klinika-border bg-white px-3 py-2 text-sm focus:outline-none"
                    style="color:var(--klinika-text)">
                @foreach($periods as $key => $meta)
                    <option value="{{ $key }}" @selected($report['period'] === $key)>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Read-only simulation notice --}}
    <div class="rounded-lg border px-4 py-3 flex items-start gap-3 text-sm" style="background-color:#EEF9F9; border-color:#CBEAE9; color:var(--klinika-text)">
        <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:var(--klinika-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <span class="font-semibold">Mode Pemantauan Manajemen (Read-Only):</span>
            Seluruh metrik dan laporan di halaman ini menggunakan data simulasi terpusat dan hanya menampilkan informasi tanpa aksi ubah atau hapus data.
        </div>
    </div>

    {{-- 4 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('manajemen.visits', ['period' => $report['period']]) }}" class="klinika-card p-5 hover:border-teal-600/40 transition-colors block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium klinika-muted">Total Kunjungan Pasien</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full" style="background-color:#dcfce7; color:#166534">{{ $report['period_meta']['label'] }}</span>
            </div>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($report['kpis']['total_kunjungan'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">{{ $report['kpis']['kunjungan_delta'] }}</div>
        </a>

        <a href="{{ route('manajemen.transactions', ['period' => $report['period']]) }}" class="klinika-card p-5 hover:border-teal-600/40 transition-colors block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium klinika-muted">Total Pendapatan</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full" style="background-color:#e0f2fe; color:#075985">Transaksi</span>
            </div>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">Rp {{ number_format($report['kpis']['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">{{ $report['kpis']['pendapatan_delta'] }}</div>
        </a>

        <a href="{{ route('manajemen.pharmacy') }}" class="klinika-card p-5 hover:border-teal-600/40 transition-colors block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium klinika-muted">Resep Farmasi Diproses</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full" style="background-color:#EEF9F9; color:var(--klinika-primary)">{{ $report['kpis']['tingkat_tebus_persen'] }}% Tebus</span>
            </div>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($report['kpis']['total_resep'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">Resep pasien rawat jalan</div>
        </a>

        <a href="{{ route('manajemen.operations') }}" class="klinika-card p-5 hover:border-teal-600/40 transition-colors block">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium klinika-muted">Rata-rata Waktu Tunggu</span>
                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full" style="background-color:#fef9c3; color:#854d0e">Okupansi {{ $report['kpis']['okupansi_poli_persen'] }}%</span>
            </div>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $report['kpis']['rata_tunggu_menit'] }} Menit</div>
            <div class="text-xs klinika-muted mt-1">Target SLA klinik: maks 20 menit</div>
        </a>
    </div>

    {{-- Operational Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($report['alerts'] as $alert)
            @php
                $isWarn = $alert['severity'] === 'warning';
            @endphp
            <div class="rounded-lg border p-4 flex items-start justify-between gap-4"
                 style="{{ $isWarn ? 'background-color:#FFF8ED; border-color:#F3DFB8;' : 'background-color:#ffffff; border-color:var(--klinika-border);' }}">
                <div class="space-y-1">
                    <div class="text-sm font-semibold" style="color:{{ $isWarn ? '#96611C' : 'var(--klinika-text)' }}">{{ $alert['title'] }}</div>
                    <p class="text-xs klinika-muted">{{ $alert['detail'] }}</p>
                </div>
                <a href="{{ route($alert['route']) }}" class="text-xs font-semibold shrink-0 hover:underline" style="color:var(--klinika-primary)">Lihat Detail →</a>
            </div>
        @endforeach
    </div>

    {{-- Poli Performance + Weekly Trend --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border flex items-center justify-between" style="background-color:#F4F7F8">
                <div>
                    <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Ringkasan Kinerja per Poli</h2>
                    <p class="text-xs klinika-muted">Perbandingan volume kunjungan, penyelesaian layanan, dan kontribusi pendapatan.</p>
                </div>
                <a href="{{ route('manajemen.visits', ['period' => $report['period']]) }}" class="text-xs font-medium hover:underline" style="color:var(--klinika-primary)">Statistik Lengkap →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                            <th class="px-5 py-3 font-medium">Poli Tujuan</th>
                            <th class="px-5 py-3 font-medium">Kunjungan</th>
                            <th class="px-5 py-3 font-medium">Selesai</th>
                            <th class="px-5 py-3 font-medium">Porsi</th>
                            <th class="px-5 py-3 font-medium text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DFE7EA]">
                        @foreach($report['poli_summary'] as $row)
                            <tr>
                                <td class="px-5 py-3.5 font-medium" style="color:var(--klinika-text)">{{ $row['poli'] }}</td>
                                <td class="px-5 py-3.5">{{ number_format($row['kunjungan'], 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">{{ number_format($row['selesai'], 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full" style="width:{{ $row['persentase_kunjungan'] }}%; background-color:var(--klinika-primary)"></div>
                                        </div>
                                        <span class="text-xs klinika-muted">{{ $row['persentase_kunjungan'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium" style="color:var(--klinika-text)">Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Tren Kunjungan Harian</h2>
                <p class="text-xs klinika-muted">Distribusi volume pasien per hari.</p>
            </div>
            <div class="p-5 space-y-3">
                @php
                    $maxDaily = max(1, max(array_column($report['weekly_highlights'], 'total')));
                @endphp
                @foreach($report['weekly_highlights'] as $day)
                    @php
                        $barWidth = round(($day['total'] / $maxDaily) * 100);
                    @endphp
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $day['hari'] }}</span>
                            <span class="klinika-muted">{{ $day['total'] }} kunjungan</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $barWidth }}%; background-color:var(--klinika-primary)"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Catalog of Read-Only Management Reports --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border flex items-center justify-between" style="background-color:#F4F7F8">
            <div>
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Direktori Laporan Klinik</h2>
                <p class="text-xs klinika-muted">Akses cepat ke seluruh modul laporan dan pemantauan Role Manajemen.</p>
            </div>
            <span class="text-xs klinika-muted">4 Laporan Aktif</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                        <th class="px-5 py-3 font-medium">Kode</th>
                        <th class="px-5 py-3 font-medium">Nama Laporan</th>
                        <th class="px-5 py-3 font-medium">Kategori</th>
                        <th class="px-5 py-3 font-medium">Ringkasan Data</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Navigasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE7EA]">
                    @foreach($report['recent_reports'] as $item)
                        <tr>
                            <td class="px-5 py-3.5 font-mono text-xs klinika-muted">{{ $item['kode'] }}</td>
                            <td class="px-5 py-3.5 font-medium" style="color:var(--klinika-text)">{{ $item['nama'] }}</td>
                            <td class="px-5 py-3.5">{{ $item['kategori'] }}</td>
                            <td class="px-5 py-3.5 klinika-muted">{{ $item['ringkasan'] }}</td>
                            <td class="px-5 py-3.5">
                                @if($item['status'] === 'Perlu Atensi')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fef9c3; color:#854d0e">{{ $item['status'] }}</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#dcfce7; color:#166534">{{ $item['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route($item['route']) }}" class="inline-flex items-center px-3 py-1.5 rounded-md border klinika-border text-xs font-medium hover:bg-slate-50" style="color:var(--klinika-primary)">
                                    Buka Laporan →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t klinika-border text-xs klinika-muted flex items-center justify-between" style="background-color:#F4F7F8">
            <span>Data simulasi • Bukan data pasien nyata | Klinika RME</span>
            <span>Akses: Read-Only</span>
        </div>
    </div>
</div>
@endsection

