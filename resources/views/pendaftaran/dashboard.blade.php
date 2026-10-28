@extends('layouts.app')

@section('title', 'Dashboard Pendaftaran')

@section('breadcrumb')
    <span>Dashboard</span>
@endsection

@section('content')
{{-- Header greeting --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">
            Selamat pagi, {{ explode(' ', auth()->user()?->name ?? 'Petugas')[0] }} 👋
        </h2>
        <p class="text-sm klinika-muted mt-0.5">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }} &bull; Klinik Sehat Sentosa</p>
    </div>
    <a href="{{ route('patients.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white klinika-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Cari / daftar pasien
    </a>
</div>

{{-- 4 Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Terdaftar hari ini</p>
        <p class="text-3xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['terdaftar_hari_ini'] }}</p>
        <p class="text-xs klinika-muted mt-1">{{ $stats['pasien_lama'] }} pasien lama &bull; {{ $stats['pasien_baru'] }} baru</p>
    </div>
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Menunggu awal</p>
        <p class="text-3xl font-bold mt-1" style="color:#d97706">{{ $stats['menunggu'] }}</p>
        <p class="text-xs klinika-muted mt-1">Belum dipanggil</p>
    </div>
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Dalam pelayanan</p>
        <p class="text-3xl font-bold mt-1" style="color:#2563eb">{{ $stats['dalam_pelayanan'] }}</p>
        <p class="text-xs klinika-muted mt-1">Sedang ditangani</p>
    </div>
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Selesai</p>
        <p class="text-3xl font-bold mt-1" style="color:#16a34a">{{ $stats['selesai'] }}</p>
        <p class="text-xs klinika-muted mt-1">Kunjungan tuntas</p>
    </div>
</div>

{{-- Info Banner --}}
<div class="rounded-lg border p-3 flex items-start gap-3" style="background:#eff6ff;border-color:#bfdbfe">
    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color:#2563eb" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-xs" style="color:#1e40af">
        <span class="font-semibold">Pasien lama, kunjungan baru</span> — Cari nomor RM terlebih dahulu sebelum mendaftarkan kunjungan baru. Gunakan tombol <strong>Cari / daftar pasien</strong> di atas.
    </p>
</div>

{{-- Kunjungan Terbaru --}}
<div class="klinika-card overflow-hidden">
    <div class="px-5 py-4 border-b klinika-border flex items-center justify-between">
        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Kunjungan terbaru</h3>
        <a href="#" class="text-xs font-medium klinika-primary-text hover:underline">Lihat semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:#F4F7F8">
                    <th class="px-4 py-2.5 text-left text-xs font-semibold klinika-muted">Pasien / RM</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold klinika-muted">Kunjungan</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold klinika-muted">Poli / Antrean</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold klinika-muted">Tahap layanan</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold klinika-muted">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color:var(--klinika-border)">
                @foreach ($recentVisits as $v)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-medium text-sm" style="color:var(--klinika-text)">{{ $v['patient'] }}</div>
                        <div class="text-xs klinika-muted">{{ $v['rm'] }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs klinika-muted">{{ $v['visit_id'] }}</td>
                    <td class="px-4 py-3">
                        <div class="text-xs font-medium" style="color:var(--klinika-text)">{{ $v['poli'] }}</div>
                        <div class="text-xs klinika-muted">{{ $v['antrian'] }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $badge = match($v['status']) {
                                'active' => ['bg:#dcfce7', 'text:#15803d', $v['tahap']],
                                'wait'   => ['bg:#fef9c3', 'text:#854d0e', $v['tahap']],
                                'pay'    => ['bg:#e0f2fe', 'text:#0369a1', $v['tahap']],
                                default  => ['bg:#f3f4f6', 'text:#4b5563', $v['tahap']],
                            };
                        @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium"
                              style="background:{{ $badge[0] }};color:{{ $badge[1] }}">
                            {{ $badge[2] }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if ($v['status'] !== 'done')
                            <a href="#" class="text-xs font-medium klinika-primary-text hover:underline">Buka kunjungan</a>
                        @else
                            <a href="#" class="text-xs klinika-muted hover:underline">Lihat status</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Bottom 2 cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- Jadwal hari ini --}}
    <div class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Jadwal hari ini</h3>
        <div class="space-y-2.5">
            @foreach ($jadwal as $j)
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-sm font-medium" style="color:var(--klinika-text)">{{ $j['poli'] }}</p>
                    <p class="text-xs klinika-muted">{{ $j['dokter'] }} &bull; {{ $j['jam'] }}</p>
                </div>
                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium shrink-0"
                      style="background:#dcfce7;color:#15803d">{{ $j['status'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Aksi pendaftaran --}}
    <div class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Aksi pendaftaran</h3>
        <div class="space-y-2">
            <a href="{{ route('patients.create') }}"
               class="flex items-center gap-3 p-3 rounded-lg border klinika-border hover:bg-gray-50 transition-colors group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#ecfdf5">
                    <svg class="w-4 h-4" style="color:#16a34a" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color:var(--klinika-text)">Daftar pasien baru</p>
                    <p class="text-xs klinika-muted">Buat rekam medis baru</p>
                </div>
            </a>
            <a href="{{ route('patients.index') }}"
               class="flex items-center gap-3 p-3 rounded-lg border klinika-border hover:bg-gray-50 transition-colors group">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#eff6ff">
                    <svg class="w-4 h-4" style="color:#2563eb" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color:var(--klinika-text)">Cari pasien lama</p>
                    <p class="text-xs klinika-muted">Daftarkan kunjungan ulang</p>
                </div>
            </a>
            <a href="#"
               class="flex items-center gap-3 p-3 rounded-lg border klinika-border hover:bg-gray-50 transition-colors group opacity-50 cursor-not-allowed">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#fef9c3">
                    <svg class="w-4 h-4" style="color:#854d0e" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium" style="color:var(--klinika-text)">Kelola antrean</p>
                    <p class="text-xs klinika-muted">Segera hadir</p>
                </div>
            </a>
        </div>
    </div>
</div>

<p class="text-center text-xs klinika-muted pt-2">Data simulasi &bull; Bukan data pasien nyata | Klinika RME &bull; Akses tercatat</p>
@endsection
