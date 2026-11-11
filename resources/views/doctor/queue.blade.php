@extends('layouts.app')

@section('title', 'Antrean pasien dokter')
@section('page-title', 'Antrean pasien dokter')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dokter.index') }}" class="hover:underline klinika-primary-text">Pelayanan medis</a>
    <span class="mx-1">/</span>
    <span>Antrean</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Antrean pasien dokter</h2>
            <p class="text-sm klinika-muted mt-0.5">Monitoring antrean konsultasi medis, pemanggilan pasien, dan riwayat pemeriksaan poli.</p>
        </div>
        <a href="{{ route('dokter.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border klinika-border text-sm font-semibold transition-colors hover:bg-slate-50 shrink-0" style="color:var(--klinika-text)">
            &larr; Kembali ke dashboard
        </a>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Total pasien</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['total'] }}</p>
        </div>
        <div class="klinika-card p-4">
            <p class="text-xs font-medium text-amber-600">Menunggu</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['menunggu'] }}</p>
        </div>
        <div class="klinika-card p-4">
            <p class="text-xs font-medium text-purple-600">Sedang dilayani</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['sedang_dilayani'] }}</p>
        </div>
        <div class="klinika-card p-4">
            <p class="text-xs font-medium text-emerald-600">Selesai</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    {{-- Active Call Banner --}}
    @if ($activeCall)
        <div class="klinika-card p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="border-color:var(--klinika-primary-light);background:var(--klinika-primary-bg)">
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 rounded-md klinika-primary text-white font-mono font-bold text-xs">
                    PANGGILAN AKTIF: {{ $activeCall->queue_code }}
                </span>
                <span class="text-xs font-semibold" style="color:var(--klinika-text)">{{ $activeCall->visit->patient->name }}</span>
                <span class="text-xs klinika-muted font-mono">RM-{{ $activeCall->visit->patient->medical_record_number }}</span>
            </div>
            <a href="{{ route('dokter.examination', $activeCall) }}"
               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg klinika-primary text-white font-semibold text-xs shrink-0">
                Buka lembar pemeriksaan &rarr;
            </a>
        </div>
    @endif

    {{-- Filter Toolbar --}}
    <div class="klinika-card p-4">
        <form method="GET" action="{{ route('dokter.queue') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Cari pasien / no. antrean</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama, RM, NIK..."
                       class="w-full px-3 py-2 rounded-lg klinika-border border text-xs bg-white focus:outline-none focus:ring-1" style="focus:ring-color:var(--klinika-primary)">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Poli</label>
                <select name="poli_id" class="w-full px-3 py-2 rounded-lg klinika-border border text-xs bg-white focus:outline-none">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $p)
                        <option value="{{ $p->id }}" {{ $poliId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Status antrean</label>
                <select name="status" class="w-full px-3 py-2 rounded-lg klinika-border border text-xs bg-white focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ $status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="sedang_dilayani" {{ $status === 'sedang_dilayani' ? 'selected' : '' }}>Sedang Dilayani</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-lg klinika-primary text-white font-semibold text-xs">
                    Terapkan filter
                </button>
                @if ($search || $poliId || $status)
                    <a href="{{ route('dokter.queue') }}" class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 klinika-muted text-xs">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel Antrean --}}
    <div class="klinika-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" style="color:var(--klinika-muted)">
                <thead class="text-[11px] font-semibold uppercase tracking-wide klinika-border border-b" style="background:var(--klinika-bg)">
                    <tr>
                        <th class="py-3 px-5">Kode antrean</th>
                        <th class="py-3 px-5">Pasien</th>
                        <th class="py-3 px-5">Poli &amp; kunjungan</th>
                        <th class="py-3 px-5">Status pemeriksaan</th>
                        <th class="py-3 px-5">Waktu panggilan</th>
                        <th class="py-3 px-5 text-right">Aksi medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse ($queues as $q)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <span class="font-bold font-mono klinika-primary-text">{{ $q->queue_code }}</span>
                                <span class="klinika-muted block text-[11px] font-mono">No. {{ $q->queue_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-semibold block" style="color:var(--klinika-text)">{{ $q->visit->patient->name }}</span>
                                <span class="text-[11px] klinika-muted font-mono">
                                    RM-{{ $q->visit->patient->medical_record_number }} &bull; {{ $q->visit->patient->gender === 'L' ? 'L' : 'P' }} ({{ $q->visit->patient->age ?? '-' }} thn)
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-medium block" style="color:var(--klinika-text)">{{ $q->poli->name }}</span>
                                <span class="text-[11px] klinika-muted font-mono">{{ $q->visit->visit_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $q->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                                    ($q->status === 'dipanggil' ? 'bg-blue-50 text-blue-800 border border-blue-200' :
                                    ($q->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-emerald-50 text-emerald-800 border border-emerald-200')) }}">
                                    {{ $q->status_label }}
                                </span>
                                @if (!empty($q->visit->assessment_data['vital_signs']))
                                    <span class="block text-[10px] text-emerald-600 mt-1 font-medium">&bull; Tanda vital perawat ada</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 klinika-muted text-[11px] font-mono">
                                <div>Panggil: {{ $q->called_at ? $q->called_at->format('H.i') : '-' }}</div>
                                <div>Mulai: {{ $q->started_at ? $q->started_at->format('H.i') : '-' }}</div>
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-1">
                                <a href="{{ route('dokter.rme', $q->visit->patient) }}"
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg border klinika-border hover:bg-slate-50 font-medium text-xs transition-colors" style="color:var(--klinika-text)">
                                    RME
                                </a>
                                <a href="{{ route('dokter.examination', $q) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg klinika-primary text-white font-semibold text-xs">
                                    {{ $q->status === 'selesai' ? 'Lihat medis' : 'Periksa' }} &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center klinika-muted text-xs">
                                Tidak ada data antrean yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($queues->hasPages())
            <div class="px-5 py-3 klinika-border border-t flex items-center justify-between text-xs klinika-muted">
                {{ $queues->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

