@extends('layouts.app')

@section('title', 'Operasional Klinik Sehat Sentosa')
@section('page-title', 'Operasional Klinik Sehat Sentosa')

@section('breadcrumb')
    <a href="{{ route('admin.operasional') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Operasional Klinik Sehat Sentosa</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Operasional Klinik Sehat Sentosa</h2>
            <p class="text-sm klinika-muted mt-0.5">Gambaran layanan hari ini dan kesiapan sumber daya klinik.</p>
        </div>
        <a href="{{ route('admin.master.jadwal-dokter') }}"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            Kelola jadwal
        </a>
    </div>

    {{-- Stats Cards (4) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Kunjungan hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['kunjungan_hari_ini'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['aktif_kunjungan'] }} aktif &bull; {{ $stats['selesai_kunjungan'] }} selesai</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Dokter bertugas</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['dokter_bertugas'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['poli_melayani'] }} poli melayani</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Rata-rata tunggu</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['rata_tunggu'] }}</p>
            <p class="text-xs klinika-muted mt-1">Dari daftar ke pemeriksaan</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Pengguna klinik</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['pengguna_klinik'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['akun_aktif'] }} akun aktif hari ini</p>
        </div>
    </div>

    {{-- Middle Section: Kunjungan tujuh hari terakhir & Perlu perhatian --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Bar Chart (2 cols) --}}
        <div class="lg:col-span-2 klinika-card p-5 flex flex-col justify-between">
            <h3 class="font-semibold text-sm mb-6" style="color:var(--klinika-text)">Kunjungan tujuh hari terakhir</h3>

            {{-- Pure CSS Bars --}}
            <div class="h-48 flex items-end justify-between gap-2 px-2 sm:px-6 pt-4 border-b klinika-border">
                @foreach($chartData as $bar)
                <div class="flex-1 flex flex-col items-center h-full justify-end group">
                    <span class="text-xs font-medium text-slate-500 mb-1.5">{{ $bar['count'] }}</span>
                    <div class="w-full max-w-[48px] rounded-t-sm transition-all duration-300 {{ !empty($bar['active']) ? 'klinika-primary' : 'bg-[#BCDCD8] hover:bg-[#A6D0CB]' }}"
                         style="height: {{ $bar['height'] }}; min-height: 12px;"></div>
                    <span class="text-[11px] klinika-muted mt-2">{{ $bar['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Perlu Perhatian (1 col) --}}
        <div class="klinika-card p-5 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold text-sm mb-4" style="color:var(--klinika-text)">Perlu perhatian</h3>
                <div class="space-y-4 text-xs leading-relaxed" style="color:var(--klinika-text)">
                    @foreach($perhatian as $item)
                    <div class="flex items-start gap-2">
                        <p class="leading-normal">{{ $item }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="pt-6">
                <a href="{{ route('admin.master.poli') }}"
                   class="inline-block px-4 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                    Lihat master data
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Kesiapan Layanan Table --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Kesiapan layanan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Poli</th>
                        <th class="py-3 px-5 text-left">Dokter bertugas</th>
                        <th class="py-3 px-5 text-left">Jam praktik</th>
                        <th class="py-3 px-5 text-left">Kunjungan</th>
                        <th class="py-3 px-5 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @foreach($kesiapanLayanan as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-3 px-5 font-semibold text-xs" style="color:var(--klinika-text)">{{ $row['poli'] }}</td>
                        <td class="py-3 px-5 text-xs" style="color:var(--klinika-text)">{{ $row['dokter_bertugas'] }}</td>
                        <td class="py-3 px-5 text-xs klinika-muted">{{ $row['jam_praktik'] }}</td>
                        <td class="py-3 px-5 text-xs font-medium" style="color:var(--klinika-text)">{{ $row['kunjungan'] }}</td>
                        <td class="py-3 px-5 text-xs">
                            <span class="text-emerald-700 font-semibold">{{ $row['status'] }}</span>
                        </td>
                    </tr>
                    @endforeach
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

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
