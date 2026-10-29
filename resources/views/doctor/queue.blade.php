@extends('layouts.app')

@section('title', 'Antrean Pasien Dokter - Pelayanan Medis')
@section('page-title', 'Antrean Pasien Dokter')

@section('content')
<div class="space-y-6">

    <!-- Header & Subtitle -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean Pasien Dokter</h2>
            <p class="text-xs text-slate-500 mt-1">
                Monitoring antrean konsultasi medis, pemanggilan pasien, dan riwayat pemeriksaan poli.
            </p>
        </div>
        <div>
            <a href="{{ route('dokter.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs transition-colors">
                <span>&larr;</span>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Ringkas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-slate-400 text-xs font-medium">Total Pasien</span>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-amber-600 text-xs font-medium">Menunggu</span>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['menunggu'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-purple-600 text-xs font-medium">Sedang Dilayani</span>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['sedang_dilayani'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-emerald-600 text-xs font-medium">Selesai</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['selesai'] }}</div>
        </div>
    </div>

    <!-- Active Call Banner (jika ada) -->
    @if ($activeCall)
        <div class="p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-900 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 rounded-md bg-teal-600 text-white font-mono font-bold text-xs">
                    PANGGILAN AKTIF: {{ $activeCall->queue_code }}
                </span>
                <span class="text-xs font-semibold text-slate-900">{{ $activeCall->visit->patient->name }}</span>
                <span class="text-xs text-slate-500 font-mono">RM-{{ $activeCall->visit->patient->medical_record_number }}</span>
            </div>
            <div>
                <a href="{{ route('dokter.examination', $activeCall) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-xs transition-colors">
                    <span>Buka Lembar Pemeriksaan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('dokter.queue') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari Pasien / No. Antrean</label>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Nama, RM, NIK, No. Antrean..." 
                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>

            <!-- Filter Poli -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Poli</label>
                <select name="poli_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $p)
                        <option value="{{ $p->id }}" {{ $poliId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Antrean</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs bg-slate-50 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ $status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="sedang_dilayani" {{ $status === 'sedang_dilayani' ? 'selected' : '' }}>Sedang Dilayani</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-xs transition-colors">
                    Terapkan Filter
                </button>
                @if ($search || $poliId || $status)
                    <a href="{{ route('dokter.queue') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs transition-colors" title="Reset filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of Queues -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">Kode Antrean</th>
                        <th class="py-3.5 px-5">Pasien</th>
                        <th class="py-3.5 px-5">Poli & Kunjungan</th>
                        <th class="py-3.5 px-5">Status Pemeriksaan</th>
                        <th class="py-3.5 px-5">Waktu Panggilan</th>
                        <th class="py-3.5 px-5 text-right">Aksi Medis</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($queues as $q)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-5 font-medium text-slate-900">
                                <span class="font-bold font-mono text-sm text-teal-800">{{ $q->queue_code }}</span>
                                <span class="text-slate-400 block text-[11px] font-mono">No. {{ $q->queue_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-semibold text-slate-900 block">{{ $q->visit->patient->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">
                                    RM-{{ $q->visit->patient->medical_record_number }} &bull; {{ $q->visit->patient->gender === 'L' ? 'L' : 'P' }} ({{ $q->visit->patient->age ?? '-' }} thn)
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-medium text-slate-800 block">{{ $q->poli->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $q->visit->visit_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $q->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                                    ($q->status === 'dipanggil' ? 'bg-blue-50 text-blue-800 border border-blue-200' :
                                    ($q->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-emerald-50 text-emerald-800 border border-emerald-200')) }}">
                                    {{ $q->status_label }}
                                </span>
                                @if (!empty($q->visit->assessment_data['vital_signs']))
                                    <span class="block text-[10px] text-emerald-600 mt-1 font-medium">
                                        &bull; Tanda vital perawat ada
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 text-[11px] font-mono">
                                <div>Panggil: {{ $q->called_at ? $q->called_at->format('H.i') : '-' }}</div>
                                <div>Mulai: {{ $q->started_at ? $q->started_at->format('H.i') : '-' }}</div>
                            </td>
                            <td class="py-3.5 px-5 text-right space-x-1">
                                <a href="{{ route('dokter.rme', $q->visit->patient) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-700 font-medium text-xs transition-colors" 
                                   title="Buka Riwayat Medis Lengkap">
                                    RME
                                </a>
                                <a href="{{ route('dokter.examination', $q) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-xs transition-colors">
                                    <span>{{ $q->status === 'selesai' ? 'Lihat Medis' : 'Periksa' }}</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <span>Tidak ada data antrean yang sesuai dengan filter pencarian.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($queues->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs">
                {{ $queues->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

