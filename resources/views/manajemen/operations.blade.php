@extends('layouts.app')

@section('title', 'Pemantauan Operasional Klinik - Role Manajemen')

@section('breadcrumb')
<span class="text-xs font-medium klinika-muted">Manajemen / <span style="color:var(--klinika-text)">Pemantauan Operasional Klinik</span></span>
@endsection

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-semibold tracking-tight" style="color:var(--klinika-text)">Pemantauan Operasional Klinik</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#EEF9F9; color:var(--klinika-primary)">Read-Only</span>
            </div>
            <p class="text-sm klinika-muted mt-0.5">Pantauan kesiapan poli, antrean berjalan, jadwal tenaga kesehatan, dan capaian SLA pelayanan.</p>
        </div>

        <a href="{{ route('manajemen.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md border klinika-border bg-white text-xs font-medium hover:bg-slate-50"
           style="color:var(--klinika-text)">
            ← Kembali ke Laporan Klinik
        </a>
    </div>

    {{-- Filter Bar (Read-Only GET) --}}
    <form method="GET" action="{{ route('manajemen.operations') }}" class="klinika-card p-4 flex flex-wrap items-end gap-4">
        <div>
            <label for="poli" class="block text-xs font-medium klinika-muted mb-1">Poli / Unit</label>
            <select id="poli" name="poli" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($monitoring['selected_poli'] === 'semua')>Semua Poli</option>
                @foreach($poliList as $poliName)
                    <option value="{{ $poliName }}" @selected($monitoring['selected_poli'] === $poliName)>{{ $poliName }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-xs font-medium klinika-muted mb-1">Status Tugas Staf</label>
            <select id="status" name="status" class="rounded-md border klinika-border bg-white px-3 py-2 text-sm" style="color:var(--klinika-text)">
                <option value="semua" @selected($monitoring['selected_status'] === 'semua')>Semua Status</option>
                @foreach(['Bertugas', 'Terjadwal'] as $st)
                    <option value="{{ $st }}" @selected($monitoring['selected_status'] === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="px-4 py-2 rounded-md text-sm font-medium text-white klinika-primary hover:opacity-90">
            Terapkan Filter
        </button>

        @if($monitoring['selected_poli'] !== 'semua' || $monitoring['selected_status'] !== 'semua')
            <a href="{{ route('manajemen.operations') }}" class="px-3 py-2 rounded-md border klinika-border text-sm klinika-muted hover:bg-slate-50">
                Reset
            </a>
        @endif
    </form>

    {{-- 4 KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Poli Beroperasi</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-primary)">{{ $monitoring['kpis']['poli_aktif'] }} Poli</div>
            <div class="text-xs klinika-muted mt-1">Seluruh layanan aktif</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Antrean Menunggu</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $monitoring['kpis']['antrean_menunggu'] }} Pasien</div>
            <div class="text-xs klinika-muted mt-1">{{ $monitoring['kpis']['sedang_diperiksa'] }} pasien sedang diperiksa</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Waktu Tunggu Rata-rata</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $monitoring['kpis']['rata_rata_tunggu_menit'] }} Menit</div>
            <div class="text-xs klinika-muted mt-1">Target maksimal {{ $monitoring['kpis']['target_sla_menit'] }} menit</div>
        </div>

        <div class="klinika-card p-5">
            <span class="text-xs font-medium klinika-muted">Tenaga Kesehatan Bertugas</span>
            <div class="text-2xl font-semibold mt-2" style="color:var(--klinika-text)">{{ $monitoring['kpis']['tenaga_bertugas'] }} Orang</div>
            <div class="text-xs klinika-muted mt-1">Dokter, perawat, & apoteker</div>
        </div>
    </div>

    {{-- Poli Live Operational Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($monitoring['poli_status'] as $poli)
            <div class="klinika-card p-5 space-y-4">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h2 class="text-base font-semibold" style="color:var(--klinika-text)">{{ $poli['poli'] }}</h2>
                        <p class="text-xs klinika-muted">{{ $poli['ruang'] }}</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#dcfce7; color:#166534">{{ $poli['status_poli'] }}</span>
                </div>

                <div class="text-xs space-y-1">
                    <div><span class="klinika-muted">Dokter:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $poli['dokter'] }}</span></div>
                    <div><span class="klinika-muted">Perawat:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $poli['perawat'] }}</span></div>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-2 border-t klinika-border text-center">
                    <div>
                        <div class="text-base font-semibold" style="color:#96611C">{{ $poli['antrean_menunggu'] }}</div>
                        <div class="text-[11px] klinika-muted">Menunggu</div>
                    </div>
                    <div>
                        <div class="text-base font-semibold" style="color:var(--klinika-primary)">{{ $poli['sedang_diperiksa'] }}</div>
                        <div class="text-[11px] klinika-muted">Diperiksa</div>
                    </div>
                    <div>
                        <div class="text-base font-semibold" style="color:var(--klinika-text)">{{ $poli['selesai_hari_ini'] }}</div>
                        <div class="text-[11px] klinika-muted">Selesai</div>
                    </div>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="klinika-muted">Beban Kapasitas</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $poli['kapasitas_persen'] }}% • ~{{ $poli['rata_tunggu_menit'] }} mnt tunggu</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-full rounded-full" style="width:{{ $poli['kapasitas_persen'] }}%; background-color:var(--klinika-primary)"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Staff Schedule & SLA Metrics --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border flex items-center justify-between" style="background-color:#F4F7F8">
                <div>
                    <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Jadwal & Status Tenaga Kesehatan Hari Ini</h2>
                    <p class="text-xs klinika-muted">Pemantauan kehadiran staf medis dan farmasi (read-only).</p>
                </div>
                <span class="text-xs klinika-muted">{{ count($monitoring['staff_schedules']) }} staf</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider klinika-muted border-b klinika-border" style="background-color:#F4F7F8">
                            <th class="px-5 py-3 font-medium">Nama Tenaga</th>
                            <th class="px-5 py-3 font-medium">Peran</th>
                            <th class="px-5 py-3 font-medium">Unit / Poli</th>
                            <th class="px-5 py-3 font-medium">Jadwal Shift</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#DFE7EA]">
                        @forelse($monitoring['staff_schedules'] as $staff)
                            <tr>
                                <td class="px-5 py-3.5 font-medium" style="color:var(--klinika-text)">{{ $staff['nama'] }}</td>
                                <td class="px-5 py-3.5">{{ $staff['peran'] }}</td>
                                <td class="px-5 py-3.5 klinika-muted">{{ $staff['poli'] }}</td>
                                <td class="px-5 py-3.5 text-xs klinika-muted">{{ $staff['shift'] }}</td>
                                <td class="px-5 py-3.5">
                                    @if($staff['status'] === 'Bertugas')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#dcfce7; color:#166534">Bertugas</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color:#e0f2fe; color:#075985">{{ $staff['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm klinika-muted">Tidak ada data tenaga kesehatan yang sesuai dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border" style="background-color:#F4F7F8">
                <h2 class="text-sm font-semibold" style="color:var(--klinika-text)">Capaian SLA Pelayanan</h2>
                <p class="text-xs klinika-muted">Waktu layanan aktual dibanding standar target klinik.</p>
            </div>
            <div class="p-5 space-y-4">
                @foreach($monitoring['sla_metrics'] as $sla)
                    <div class="rounded-lg border klinika-border p-3.5 bg-white space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold" style="color:var(--klinika-text)">{{ $sla['tahap'] }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium" style="background-color:#dcfce7; color:#166534">{{ $sla['status'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs klinika-muted">
                            <span>Capaian rata-rata: <strong style="color:var(--klinika-text)">{{ $sla['capaian_menit'] }} menit</strong></span>
                            <span>Target ≤ {{ $sla['target_menit'] }} mnt</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-5 py-3 border-t klinika-border text-xs klinika-muted flex items-center justify-between" style="background-color:#F4F7F8">
                <span>Data simulasi • Bukan data pasien nyata | Klinika RME</span>
                <span>Akses: Read-Only</span>
            </div>
        </div>
    </div>
</div>
@endsection

