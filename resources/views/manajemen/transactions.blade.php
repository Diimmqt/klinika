@extends('layouts.app')

@section('title', 'Laporan Transaksi - Role Manajemen')

@section('breadcrumb')
<span class="text-xs font-medium klinika-muted">Manajemen / <span style="color:var(--klinika-text)">Laporan Transaksi</span></span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-semibold tracking-tight" style="color:var(--klinika-text)">Laporan Transaksi</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#EEF9F9; color:var(--klinika-primary)">Read-Only</span>
            </div>
            <p class="text-sm klinika-muted mt-0.5">Rekap pendapatan klinik, rincian metode pembayaran, kategori layanan, dan riwayat transaksi pasien.</p>
        </div>

        <a href="{{ route('manajemen.index', ['period' => $report['period']]) }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md border klinika-border bg-white text-xs font-medium hover:bg-slate-50"
           style="color:var(--klinika-text)">
            ← Kembali ke Laporan Klinik
        </a>
    </div>

    {{-- Filter Bar (Read-Only GET) --}}
    <form method="GET" action="{{ route('manajemen.transactions') }}" class="klinika-card p-4 flex flex-wrap items-end gap-4">
        <div>
            <label for="period" class="block text-xs font-medium klinika-muted mb-1">Periode</label>
            <select id="period" name="period" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                @foreach($periods as $key => $meta)
                    <option value="{{ $key }}" @selected($report['period'] === $key)>{{ $meta['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="metode" class="block text-xs font-medium klinika-muted mb-1">Metode Pembayaran</label>
            <select id="metode" name="metode" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($report['selected_method'] === 'semua')>Semua Metode</option>
                @foreach(['QRIS', 'Tunai', 'Transfer/Debit', 'BPJS'] as $m)
                    <option value="{{ $m }}" @selected($report['selected_method'] === $m)>{{ $m }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-xs font-medium klinika-muted mb-1">Status Transaksi</label>
            <select id="status" name="status" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($report['selected_status'] === 'semua')>Semua Status</option>
                @foreach(['Lunas', 'Terverifikasi', 'Menunggu'] as $st)
                    <option value="{{ $st }}" @selected($report['selected_status'] === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white klinika-primary hover:opacity-90">
            Terapkan Filter
        </button>

        @if($report['period'] !== '7_hari' || $report['selected_method'] !== 'semua' || $report['selected_status'] !== 'semua')
            <a href="{{ route('manajemen.transactions') }}" class="px-3 py-2 rounded-md border klinika-border text-sm klinika-muted hover:bg-slate-50">
                Reset
            </a>
        @endif
    </form>

    {{-- 4 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Total Pendapatan</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">Rp {{ number_format($report['kpis']['total_pendapatan'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">{{ $report['period_meta']['label'] }}</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Jumlah Transaksi</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ number_format($report['kpis']['jumlah_transaksi'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">Transaksi tercatat</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Rata-rata per Transaksi</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">Rp {{ number_format($report['kpis']['rata_transaksi'], 0, ',', '.') }}</div>
            <div class="text-xs klinika-muted mt-1">Nilai keranjang kunjungan</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Status Penyelesaian</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-primary)">{{ number_format($report['kpis']['transaksi_lunas'], 0, ',', '.') }} Lunas</div>
            <div class="text-xs klinika-muted mt-1">{{ $report['kpis']['transaksi_tertunda'] }} transaksi menunggu verifikasi</div>
        </div>
    </div>

    {{-- Breakdown by Payment Method & Service Category --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Rekap per Metode Pembayaran</h2>
                <p class="text-xs klinika-muted">Komposisi penerimaan tunai dan non-tunai.</p>
            </div>
            <div class="p-5 space-y-4">
                @foreach($report['by_method'] as $method)
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $method['metode'] }} <span class="text-xs klinika-muted">({{ $method['jumlah'] }} trx)</span></span>
                            <span class="font-semibold" style="color:var(--klinika-text)">Rp {{ number_format($method['nominal'], 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $method['persentase'] }}%; background-color:var(--klinika-primary)"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Pendapatan per Kategori Layanan</h2>
                <p class="text-xs klinika-muted">Kontribusi konsultasi dokter, tindakan medis, dan farmasi.</p>
            </div>
            <div class="p-5 space-y-4">
                @foreach($report['by_category'] as $cat)
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $cat['kategori'] }}</span>
                            <span class="font-semibold" style="color:var(--klinika-text)">Rp {{ number_format($cat['nominal'], 0, ',', '.') }} <span class="text-xs klinika-muted">({{ $cat['persen'] }}%)</span></span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full" style="width:{{ $cat['persen'] }}%; background-color:#0ea5e9"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Read-only Transaction Table --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border flex items-center justify-between" style="background-color:#F4F7F8">
            <div>
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Daftar Transaksi Terbaru</h2>
                <p class="text-xs klinika-muted">Tampilan read-only catatan pembayaran dari modul kasir.</p>
            </div>
            <span class="text-xs klinika-muted">{{ count($report['transactions']) }} data ditampilkan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                        <th class="px-5 py-3 font-medium">No. Transaksi</th>
                        <th class="px-5 py-3 font-medium">Waktu</th>
                        <th class="px-5 py-3 font-medium">Pasien & Poli</th>
                        <th class="px-5 py-3 font-medium">Rincian Layanan</th>
                        <th class="px-5 py-3 font-medium">Metode</th>
                        <th class="px-5 py-3 font-medium text-right">Nominal</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#DFE7EA]">
                    @forelse($report['transactions'] as $trx)
                        <tr>
                            <td class="px-5 py-3.5 font-mono text-xs" style="color:var(--klinika-primary)">{{ $trx['kode'] }}</td>
                            <td class="px-5 py-3.5 text-xs klinika-muted">{{ $trx['tanggal'] }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium" style="color:var(--klinika-text)">{{ $trx['pasien'] }}</div>
                                <div class="text-xs klinika-muted">{{ $trx['poli'] }}</div>
                            </td>
                            <td class="px-5 py-3.5">{{ $trx['layanan'] }}</td>
                            <td class="px-5 py-3.5">{{ $trx['metode'] }}</td>
                            <td class="px-5 py-3.5 text-right font-semibold" style="color:var(--klinika-text)">Rp {{ number_format($trx['nominal'], 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5">
                                @if($trx['status'] === 'Lunas')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#dcfce7; color:#166534">Lunas</span>
                                @elseif($trx['status'] === 'Terverifikasi')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#e0f2fe; color:#075985">Terverifikasi</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#fef9c3; color:#854d0e">{{ $trx['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-sm klinika-muted">Tidak ada transaksi yang sesuai dengan filter yang dipilih.</td>
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
@endsection

