@extends('layouts.app')

@section('title', 'Kunjungan Pasien')
@section('page-title', 'Kunjungan')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Kunjungan pasien</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Kunjungan pasien</h2>
            <p class="text-sm klinika-muted mt-0.5">Satu baris adalah satu encounter, bukan satu identitas pasien.</p>
        </div>
        <a href="{{ route('patients.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
            + Daftarkan kunjungan <span class="sr-only">Daftarkan Kunjungan Baru</span>
        </a>
    </div>

    {{-- 3 Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Kunjungan aktif --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Kunjungan aktif</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['aktif'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['aktif_sub'] }}</p>
        </div>

        {{-- Kunjungan selesai --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Kunjungan selesai</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['selesai'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['selesai_sub'] }}</p>
        </div>

        {{-- Dibatalkan --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Dibatalkan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['batal'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['batal_sub'] }}</p>
        </div>
    </div>

    {{-- Table Card: Kunjungan • 6 Oktober 2026 --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-4 border-b klinika-border">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">
                Kunjungan &bull; {{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}
            </h3>

            {{-- Search & Filters --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           placeholder="Cari pasien, RM, atau ID kunjungan"
                           class="w-full pl-9 pr-4 py-2 rounded-lg border klinika-border text-sm focus:outline-none focus:ring-2"
                           style="focus:ring-color:var(--klinika-primary);background:#fff">
                </div>

                <div class="relative">
                    <select class="appearance-none px-3.5 py-2 pr-8 rounded-lg border klinika-border text-sm focus:outline-none bg-white">
                        <option value="">Semua status</option>
                        <option value="terdaftar">Terdaftar</option>
                        <option value="pelayanan">Dalam pelayanan</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Dibatalkan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="relative">
                    <select class="appearance-none px-3.5 py-2 pr-8 rounded-lg border klinika-border text-sm focus:outline-none bg-white">
                        <option value="">{{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">ID / pasien</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Poli / dokter</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Status kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Tahap layanan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Antrean</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @foreach ($visits as $v)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-xs" style="color:var(--klinika-text)">{{ $v['id'] }}</div>
                            <div class="text-xs klinika-muted mt-0.5">{{ $v['patient_name'] }} &bull; {{ $v['rm'] }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium" style="color:var(--klinika-text)">{{ $v['poli'] }}</div>
                            <div class="text-xs klinika-muted">{{ $v['doctor'] }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $badgeStyle = match($v['status']) {
                                    'Terdaftar' => 'background:#EFF6FF;color:#1D4ED8;',
                                    'Dalam pelayanan' => 'background:#EFF6FF;color:#2563EB;',
                                    'Dibatalkan' => 'background:#FFF1F2;color:#BE123C;',
                                    'Selesai' => 'background:#DCFCE7;color:#15803D;',
                                    default => 'background:#F3F4F6;color:#374151;'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium" style="{{ $badgeStyle }}">
                                {{ $v['status'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                            {{ $v['tahap'] }}
                        </td>
                        <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                            {{ $v['antrean'] }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="#" class="inline-flex items-center gap-1 text-xs font-medium klinika-primary-text hover:underline">
                                {{ $v['action_label'] }} <span aria-hidden="true">&rarr;</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="px-4 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2 text-xs klinika-muted">
            <span>Menampilkan 1–5 dari 5 data simulasi</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Bottom Cyan Banner --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Kunjungan selesai tetap tersedia di riwayat</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Gunakan filter Selesai atau buka Detail pasien &rarr; Riwayat. Memilih pasien lama selalu membuat kunjungan baru, bukan mengubah encounter sebelumnya.
            </p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
