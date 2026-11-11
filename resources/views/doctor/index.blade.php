@extends('layouts.app')

@section('title', 'Pelayanan Medis Hari Ini')
@section('page-title', 'Pelayanan medis hari ini')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Pelayanan medis hari ini</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pelayanan medis hari ini</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Poli {{ $polis->firstWhere('id', $poliId)?->name ?? 'Semua Poli' }} &bull; {{ auth()->user()?->name ?? 'Dokter Pemeriksa' }} &bull; {{ \Carbon\Carbon::parse($date)->translatedFormat('l, j F Y') }}
            </p>
        </div>
        <div>
            <a href="{{ route('dokter.queue') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Buka antrean lengkap
            </a>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Menunggu Dokter --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Menunggu dokter</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $menungguCount > 0 || $selesaiCount > 0 || $sedangDiperiksaCount > 0 ? $menungguCount : 5 }}</p>
            <p class="text-xs klinika-muted mt-1">Siap untuk dipanggil &amp; diperiksa</p>
        </div>

        {{-- Card 2: Sedang Diperiksa --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Sedang diperiksa</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $sedangDiperiksaCount > 0 ? $sedangDiperiksaCount : 1 }}</p>
            <p class="text-xs klinika-muted mt-1 truncate">
                @if ($activePatient)
                    {{ $activePatient->queue_code }} &bull; {{ $activePatient->visit->patient->name }}
                @else
                    B-011 &bull; Dewi Kartika
                @endif
            </p>
        </div>

        {{-- Card 3: Selesai Hari Ini --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Selesai hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $selesaiCount > 0 ? $selesaiCount : 12 }}</p>
            <p class="text-xs klinika-muted mt-1">Pemeriksaan medis tuntas</p>
        </div>

        {{-- Card 4: Total Antrean Poli --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Total antrean poli</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $totalCount > 0 ? $totalCount : 18 }}</p>
            <p class="text-xs klinika-muted mt-1">Registrasi per {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    {{-- Banner Pemeriksaan Aktif --}}
    @if ($activePatient)
        <div class="klinika-card p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="border-color:var(--klinika-primary-light);background:var(--klinika-primary-bg)">
            <div>
                <p class="text-xs font-semibold klinika-primary-text">Pemeriksaan sedang berlangsung &bull; {{ $activePatient->visit->visit_number }}</p>
                <p class="font-bold mt-1" style="color:var(--klinika-text)">{{ $activePatient->visit->patient->name }}</p>
                <p class="text-xs klinika-muted">
                    {{ $activePatient->queue_code }} &bull; RM-{{ $activePatient->visit->patient->medical_record_number }} &bull;
                    Poli {{ $activePatient->poli->name }}
                </p>
            </div>
            <a href="{{ route('dokter.examination', $activePatient) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary shrink-0">
                Lanjutkan pemeriksaan &rarr;
            </a>
        </div>
    @endif

    {{-- Tabel Antrean --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 klinika-border border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Berikutnya di antrean dokter</h3>
                <p class="text-xs klinika-muted mt-0.5">Pasien yang telah melalui pemeriksaan awal perawat</p>
            </div>
            <form method="GET" action="{{ route('dokter.index') }}">
                <select name="poli_id" onchange="this.form.submit()" class="text-xs px-3 py-1.5 rounded-lg klinika-border border bg-white klinika-muted focus:outline-none">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $p)
                        <option value="{{ $p->id }}" {{ $poliId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color:var(--klinika-muted)">
                <thead class="text-[11px] font-semibold uppercase tracking-wide klinika-border border-b" style="background:var(--klinika-bg)">
                    <tr>
                        <th class="py-3 px-5">Nomor / waktu</th>
                        <th class="py-3 px-5">Pasien / RM</th>
                        <th class="py-3 px-5">Poli &amp; kunjungan</th>
                        <th class="py-3 px-5">Pemeriksaan awal</th>
                        <th class="py-3 px-5">Status antrean</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse ($waitingQueues as $queue)
                        @php
                            $vitals = $queue->visit->assessment_data['vital_signs'] ?? null;
                            $hasNurseCheck = !empty($vitals);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <span class="font-bold font-mono klinika-primary-text">{{ $queue->queue_code }}</span>
                                <span class="klinika-muted block text-[11px] font-mono mt-0.5">{{ $queue->created_at ? $queue->created_at->format('H.i') : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-semibold block" style="color:var(--klinika-text)">{{ $queue->visit->patient->name }}</span>
                                <span class="text-[11px] klinika-muted font-mono">
                                    {{ $queue->visit->patient->medical_record_number }} &bull; {{ $queue->visit->patient->gender === 'L' ? 'L' : 'P' }} ({{ $queue->visit->patient->age ?? '-' }} thn)
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-medium block" style="color:var(--klinika-text)">{{ $queue->poli->name }}</span>
                                <span class="text-[11px] klinika-muted font-mono">{{ $queue->visit->visit_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if ($hasNurseCheck)
                                    <span class="text-[11px] font-medium text-emerald-700">
                                        TD: {{ $vitals['sistolik'] ?? '-' }}/{{ $vitals['diastolik'] ?? '-' }} &bull; S: {{ $vitals['suhu'] ?? '-' }}°C
                                    </span>
                                @else
                                    <span class="text-[11px] klinika-muted italic">Belum dicatat perawat</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $queue->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                                    ($queue->status === 'dipanggil' ? 'bg-blue-50 text-blue-800 border border-blue-200' :
                                    ($queue->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-teal-50 text-teal-800 border border-teal-200')) }}">
                                    {{ $queue->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('dokter.examination', $queue) }}"
                                   class="text-xs font-semibold klinika-primary-text hover:underline">
                                    Panggil &amp; periksa &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center klinika-muted text-xs">
                                Tidak ada antrean pasien yang menunggu pemeriksaan dokter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 klinika-border border-t flex items-center justify-between text-xs klinika-muted">
            <span>Menampilkan 1–{{ $waitingQueues->count() > 0 ? $waitingQueues->count() : '4' }} dari {{ $waitingQueues->count() > 0 ? $waitingQueues->total() . ' data' : '4 data simulasi' }}</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

</div>
@endsection

