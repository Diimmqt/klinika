@extends('layouts.app')

@section('title', 'Monitoring Antrean Pasien')
@section('page-title', 'Antrean Poli')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean Pasien</h2>
            <p class="text-sm text-slate-500 mt-1">Monitoring dan pengelolaan status pemanggilan antrean per poli klinik.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('visits.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Buat Kunjungan & Antrean</span>
            </a>
        </div>
    </div>

    <!-- Papan Monitor Antrean Aktif Per Poli (Live Call Board) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($polis as $p)
            @php
                $active = $activePerPoli[$p->id] ?? null;
                $current = $active['current'] ?? null;
                $next = $active['next'] ?? null;
            @endphp
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ $p->name }}</span>
                        <span class="text-[10px] font-mono font-bold bg-teal-100 text-teal-800 px-1.5 py-0.5 rounded">Prefix {{ $p->queue_prefix }}</span>
                    </div>

                    <div class="my-4 text-center">
                        <span class="text-[11px] font-medium text-slate-400 block">Sedang Dilayani / Dipanggil</span>
                        @if ($current)
                            <div class="text-3xl font-extrabold font-mono text-teal-700 mt-1">
                                {{ $current->queue_code }}
                            </div>
                            <span class="text-xs font-semibold text-slate-800 block truncate mt-0.5">
                                {{ $current->visit->patient->name }}
                            </span>
                            <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $current->status === 'dipanggil' ? 'bg-blue-50 text-blue-700 border border-blue-200 animate-pulse' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                {{ $current->status_label }}
                            </span>
                        @else
                            <div class="text-2xl font-bold font-mono text-slate-300 mt-1">--</div>
                            <span class="text-xs text-slate-400 block mt-0.5">Belum ada antrean aktif</span>
                        @endif
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Berikutnya:</span>
                    <span class="font-mono font-bold text-slate-700">
                        {{ $next ? $next->queue_code . ' (' . $next->visit->patient->name . ')' : 'Tidak ada' }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Metric Cards Status -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/60 shadow-2xs">
            <span class="text-xs font-medium text-amber-700">Menunggu</span>
            <div class="text-2xl font-bold text-amber-900 mt-1">{{ $stats['menunggu'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200/60 shadow-2xs">
            <span class="text-xs font-medium text-blue-700">Dipanggil</span>
            <div class="text-2xl font-bold text-blue-900 mt-1">{{ $stats['dipanggil'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-purple-50/70 border border-purple-200/60 shadow-2xs">
            <span class="text-xs font-medium text-purple-700">Sedang Dilayani</span>
            <div class="text-2xl font-bold text-purple-900 mt-1">{{ $stats['sedang_dilayani'] }}</div>
        </div>
        <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/60 shadow-2xs">
            <span class="text-xs font-medium text-emerald-700">Selesai</span>
            <div class="text-2xl font-bold text-emerald-900 mt-1">{{ $stats['selesai'] }}</div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('queues.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Tanggal -->
            <div class="md:col-span-4">
                <input type="date" 
                       name="date" 
                       value="{{ $date }}" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
            </div>

            <!-- Poli -->
            <div class="md:col-span-4">
                <select name="poli_id" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" {{ $poliId == $poli->id ? 'selected' : '' }}>
                            {{ $poli->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-3">
                <select name="status" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="dipanggil" {{ $status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="sedang_dilayani" {{ $status === 'sedang_dilayani' ? 'selected' : '' }}>Sedang Dilayani</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="submit" 
                        class="w-full py-2.5 px-3 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-xl transition-colors text-center">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Antrean Pasien -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">Nomor Antrean</th>
                        <th class="py-3.5 px-5">Pasien</th>
                        <th class="py-3.5 px-5">Poli Tujuan</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Kelola Status Antrean</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($queues as $q)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Nomor Antrean -->
                            <td class="py-4 px-5">
                                <span class="font-mono font-extrabold text-base text-teal-700 bg-teal-50 px-3 py-1 rounded-lg border border-teal-200/80 inline-block">
                                    {{ $q->queue_code }}
                                </span>
                                <span class="text-xs text-slate-400 block mt-1">
                                    Urutan: #{{ $q->queue_number }}
                                </span>
                            </td>

                            <!-- Pasien -->
                            <td class="py-4 px-5">
                                <a href="{{ route('patients.show', $q->visit->patient) }}" class="font-semibold text-slate-900 hover:text-teal-600 transition-colors block">
                                    {{ $q->visit->patient->name }}
                                </a>
                                <span class="text-xs text-slate-500 font-mono">
                                    {{ $q->visit->patient->medical_record_number }}
                                </span>
                            </td>

                            <!-- Poli -->
                            <td class="py-4 px-5">
                                <span class="font-medium text-slate-800">
                                    {{ $q->poli->name }}
                                </span>
                                <span class="text-xs text-slate-400 block mt-0.5">
                                    {{ $q->visit->visit_number }}
                                </span>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ 
                                    $q->status === 'menunggu' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                    ($q->status === 'dipanggil' ? 'bg-blue-50 text-blue-700 border border-blue-200' :
                                    ($q->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-700 border border-purple-200' :
                                    'bg-emerald-50 text-emerald-700 border border-emerald-200')) }}">
                                    @if ($q->status === 'dipanggil')
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping"></span>
                                    @endif
                                    {{ $q->status_label }}
                                </span>
                            </td>

                            <!-- Tombol Aksi Status -->
                            <td class="py-4 px-5 text-right space-x-1">
                                @if ($q->status === 'menunggu')
                                    <form method="POST" action="{{ route('queues.update-status', $q) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="dipanggil">
                                        <button type="submit" 
                                                class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-colors shadow-2xs">
                                            Panggil Pasien
                                        </button>
                                    </form>
                                @elseif ($q->status === 'dipanggil')
                                    <form method="POST" action="{{ route('queues.update-status', $q) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="sedang_dilayani">
                                        <button type="submit" 
                                                class="px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs transition-colors shadow-2xs">
                                            Mulai Layani
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('queues.update-status', $q) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="dipanggil">
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs transition-colors"
                                                title="Panggil ulang pasien">
                                            Panggil Ulang
                                        </button>
                                    </form>
                                @elseif ($q->status === 'sedang_dilayani')
                                    <form method="POST" action="{{ route('queues.update-status', $q) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" 
                                                class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-2xs">
                                            Selesaikan Antrean
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 font-medium italic">Selesai Dilayani</span>
                                @endif

                                <a href="{{ route('visits.show', $q->visit) }}" 
                                   class="inline-block px-2 py-1.5 text-xs text-slate-400 hover:text-teal-600"
                                   title="Lihat Detail Kunjungan">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="max-w-sm mx-auto text-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-slate-800 text-sm">Tidak ada antrean pada tanggal ini</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Belum ada antrean yang terdaftar untuk filter yang dipilih.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($queues->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                {{ $queues->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
