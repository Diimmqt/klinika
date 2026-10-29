@extends('layouts.app')

@section('title', 'Dashboard Dokter - Pelayanan Medis')
@section('page-title', 'Pelayanan Medis Dokter')

@section('content')
<div class="space-y-6">

    <!-- Header & Greetings -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pelayanan Medis Hari Ini</h2>
            <p class="text-xs text-slate-500 mt-1">
                Poli {{ $polis->firstWhere('id', $poliId)?->name ?? 'Semua Poli' }} &bull; {{ auth()->user()?->name ?? 'Dokter Pemeriksa' }} &bull; {{ \Carbon\Carbon::parse($date)->translatedFormat('l, j F Y') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('dokter.queue') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Buka Antrean Lengkap</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Menunggu Dokter -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Menunggu Dokter</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $menungguCount }}</div>
            </div>
            <p class="text-[11px] text-amber-700 font-medium mt-3 pt-2 border-t border-slate-100 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                Siap untuk dipanggil & diperiksa
            </p>
        </div>

        <!-- Card 2: Sedang Diperiksa -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Sedang Diperiksa</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $sedangDiperiksaCount }}</div>
            </div>
            <p class="text-[11px] text-teal-700 font-medium truncate mt-3 pt-2 border-t border-slate-100">
                @if ($activePatient)
                    {{ $activePatient->queue_code }} &bull; {{ $activePatient->visit->patient->name }}
                @else
                    Belum ada pasien aktif
                @endif
            </p>
        </div>

        <!-- Card 3: Selesai Hari Ini -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Selesai Hari Ini</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $selesaiCount }}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 pt-2 border-t border-slate-100 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                Pemeriksaan medis tuntas
            </p>
        </div>

        <!-- Card 4: Total Kunjungan Poli -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Total Antrean Poli</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalCount }}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 pt-2 border-t border-slate-100">Registrasi per {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    <!-- Active Call Banner (jika ada yang sedang dilayani) -->
    @if ($activePatient)
        <div class="p-5 rounded-2xl bg-gradient-to-r from-teal-50 to-white border border-teal-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-teal-600 text-white flex items-center justify-center font-mono font-bold text-lg shadow-sm">
                    {{ $activePatient->queue_code }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800">
                            Pemeriksaan Sedang Berlangsung
                        </span>
                        <span class="text-xs text-slate-400 font-mono">{{ $activePatient->visit->visit_number }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $activePatient->visit->patient->name }}</h3>
                    <p class="text-xs text-slate-500">
                        RM-{{ $activePatient->visit->patient->medical_record_number }} &bull; 
                        {{ $activePatient->visit->patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $activePatient->visit->patient->age ?? '-' }} thn &bull; 
                        Poli {{ $activePatient->poli->name }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 self-end sm:self-center">
                <a href="{{ route('dokter.examination', $activePatient) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-sm transition-all hover:shadow-md">
                    <span>Lanjutkan Pemeriksaan</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Tabel: Antrean Pasien Berikutnya -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Daftar Antrean Menunggu Pemeriksaan</h3>
                <p class="text-xs text-slate-400 mt-0.5">Pasien yang telah melalui pendaftaran dan pemeriksaan awal perawat</p>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('dokter.index') }}" class="flex items-center gap-2">
                    <select name="poli_id" onchange="this.form.submit()" class="text-xs px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 focus:outline-none focus:ring-1 focus:ring-teal-500">
                        <option value="">Semua Poli</option>
                        @foreach ($polis as $p)
                            <option value="{{ $p->id }}" {{ $poliId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[11px] border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-5">No. Antrean</th>
                        <th class="py-3.5 px-5">Identitas Pasien</th>
                        <th class="py-3.5 px-5">Poli & Waktu</th>
                        <th class="py-3.5 px-5">Pemeriksaan Awal (Perawat)</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($waitingQueues as $queue)
                        @php
                            $vitals = $queue->visit->assessment_data['vital_signs'] ?? null;
                            $hasNurseCheck = !empty($vitals);
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-5 font-medium text-slate-900">
                                <span class="font-bold font-mono text-sm text-teal-800">{{ $queue->queue_code }}</span>
                                <span class="text-slate-400 block text-[11px] font-mono mt-0.5">#{{ $queue->queue_number }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-semibold text-slate-900 block">{{ $queue->visit->patient->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">
                                    RM-{{ $queue->visit->patient->medical_record_number }} &bull; {{ $queue->visit->patient->gender === 'L' ? 'L' : 'P' }} ({{ $queue->visit->patient->age ?? '-' }} thn)
                                </span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-medium text-slate-800 block">{{ $queue->poli->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $queue->created_at ? $queue->created_at->format('H.i') : '-' }} WIB</span>
                            </td>
                            <td class="py-3.5 px-5">
                                @if ($hasNurseCheck)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        TD: {{ $vitals['sistolik'] ?? '-' }}/{{ $vitals['diastolik'] ?? '-' }} &bull; S: {{ $vitals['suhu'] ?? '-' }}°C
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Belum dicatat perawat</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $queue->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                                    ($queue->status === 'dipanggil' ? 'bg-blue-50 text-blue-800 border border-blue-200' :
                                    ($queue->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-teal-50 text-teal-800 border border-teal-200')) }}">
                                    {{ $queue->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('dokter.examination', $queue) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 font-semibold text-xs transition-colors">
                                    <span>Periksa Pasien</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Tidak ada antrean pasien yang menunggu pemeriksaan dokter saat ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($waitingQueues->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs">
                {{ $waitingQueues->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

