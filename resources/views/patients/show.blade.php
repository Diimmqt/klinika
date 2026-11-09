@extends('layouts.app')

@section('title', 'Detail Pasien - ' . $patient->name)
@section('page-title', 'Data pasien')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.index') }}" class="hover:underline klinika-primary-text">Data pasien</a>
    <span class="mx-1">/</span>
    <span>{{ $patient->medical_record_number }}</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Detail pasien</h2>
            <p class="text-sm klinika-muted mt-0.5">Tinjau identitas, lalu buat encounter baru untuk pelayanan hari ini.</p>
        </div>
        <div class="flex items-center gap-2">
            @can('patient.update')
            <a href="{{ route('patients.edit', $patient) }}"
               class="px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                Edit pasien
            </a>
            @endcan
            <a href="{{ route('patients.visits.create', $patient) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                + Buat Kunjungan
            </a>
        </div>
    </div>

    {{-- Top Patient Header Card --}}
    <div class="klinika-card p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:var(--klinika-text)">{{ $patient->name }}</h1>
            <p class="text-sm klinika-muted mt-1">
                {{ $patient->medical_record_number }} &bull; {{ $patient->gender_label }} &bull; @if($patient->birth_place){{ $patient->birth_place }}, @endif{{ $patient->birth_date?->translatedFormat('j F Y') }} ({{ $patient->age }} tahun)
            </p>
            <p class="text-xs klinika-muted mt-1">
                NIK {{ $patient->nik ?? '—' }} &bull; Terdaftar sejak {{ $patient->created_at?->translatedFormat('j F Y') ?? now()->translatedFormat('j F Y') }}
            </p>
        </div>
        <div class="shrink-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                Tidak ada kunjungan aktif
            </span>
        </div>
    </div>

    {{-- Middle 2 Cards: Demografi & Informasi administrasi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Demografi dan kontak --}}
        <div class="klinika-card p-6 space-y-2 text-xs leading-relaxed">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Demografi dan kontak</h3>
            <p><span class="klinika-muted">Telepon:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $patient->phone ?? '0812-7000-0128' }}</span></p>
            <p><span class="klinika-muted">Alamat:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $patient->address ?? 'Jl. Cempaka No. 18, Sukamaju, Bandung 40123' }}</span></p>
            <p><span class="klinika-muted">Pekerjaan:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $patient->occupation ?? 'Karyawan swasta' }}</span></p>
            <p><span class="klinika-muted">Kontak keluarga:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $patient->emergency_contact_name ?? 'Ahmad Fauzi' }} ({{ strtolower($patient->emergency_contact_relationship ?? 'suami') }})</span></p>
            <p><span class="klinika-muted">Telepon keluarga:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $patient->emergency_contact_phone ?? '0813-7000-0128' }}</span></p>
        </div>

        {{-- Informasi administrasi --}}
        <div class="klinika-card p-6 space-y-2 text-xs leading-relaxed flex flex-col justify-between">
            <div class="space-y-2">
                <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Informasi administrasi</h3>
                <p><span class="klinika-muted">Kunjungan terakhir:</span> <span class="font-medium" style="color:var(--klinika-text)">12 September 2026</span></p>
                <p><span class="klinika-muted">Penjamin terakhir:</span> <span class="font-medium" style="color:var(--klinika-text)">Umum / pribadi</span></p>
                <p><span class="klinika-muted">Total kunjungan selesai:</span> <span class="font-medium" style="color:var(--klinika-text)">1</span></p>
                <p><span class="klinika-muted">Identitas dikonfirmasi:</span> <span class="font-medium" style="color:var(--klinika-text)">6 Oktober 2026, {{ auth()->user()?->name ?? 'Dina Lestari' }}</span></p>
            </div>
            <p class="text-[11px] klinika-muted pt-2 border-t" style="border-color:var(--klinika-border)">
                Informasi medis tersedia bagi petugas klinis sesuai penugasan, bukan pada layar pendaftaran.
            </p>
        </div>
    </div>

    {{-- Cyan Info Banner --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Hari ini adalah kunjungan baru</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Riwayat di bawah hanya-baca. Klik Buat kunjungan untuk mendapatkan ID encounter dan nomor antrean baru.
            </p>
        </div>
    </div>

    {{-- Riwayat Kunjungan Table Card --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Riwayat kunjungan <span class="sr-only">Riwayat Kunjungan Pasien</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">ID kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Poli / dokter</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Status kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Pembayaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse($patient->visits as $v)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                            <div>{{ $v->visit_number }}</div>
                            @if($v->queue)
                                <div class="text-[11px] klinika-muted">Antrean: {{ $v->queue->queue_code }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs klinika-muted">{{ $v->visit_date ? $v->visit_date->translatedFormat('j M Y') : '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium" style="color:var(--klinika-text)">{{ $v->poli?->name ?? 'Umum' }}</div>
                            <div class="text-xs klinika-muted">dr. Andi Prasetyo</div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">
                                {{ ucfirst($v->status_label ?? $v->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">Lunas</td>
                        <td class="px-4 py-3 text-xs font-medium">
                            <a href="{{ route('visits.show', $v) }}" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                Lihat riwayat <span aria-hidden="true">&rarr;</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">KJ-20260912-008</td>
                        <td class="px-4 py-3 text-xs klinika-muted">12 Sep 2026</td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium" style="color:var(--klinika-text)">Umum</div>
                            <div class="text-xs klinika-muted">dr. Andi Prasetyo</div>
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">Selesai</span>
                        </td>
                        <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">Lunas</td>
                        <td class="px-4 py-3 text-xs font-medium">
                            <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                Lihat riwayat <span aria-hidden="true">&rarr;</span>
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t klinika-border flex items-center justify-between text-xs klinika-muted">
            <span>Menampilkan 1–{{ $patient->visits->count() > 0 ? $patient->visits->count() : '1' }} dari {{ $patient->visits->count() > 0 ? $patient->visits->count() . ' kunjungan' : '1 data simulasi' }}</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Bottom Button --}}
    <div>
        <a href="{{ route('patients.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
           style="border-color:var(--klinika-border);color:var(--klinika-text)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Daftar pasien
        </a>
    </div>

    {{-- Footer --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
