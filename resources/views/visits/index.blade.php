@extends('layouts.app')

@section('title', 'Daftar Kunjungan Pasien')
@section('page-title', 'Kunjungan')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Kunjungan Pasien</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola registrasi kunjungan dan penerbitan nomor antrean per poli.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('visits.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Daftarkan Kunjungan Baru</span>
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Total Kunjungan Hari Ini</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_today']) }}</span>
                <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md">Hari Ini</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Menunggu Pelayanan</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['registered_today']) }}</span>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Terdaftar</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Sedang Dilayani</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['in_progress_today']) }}</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">Di Ruang Poli</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Kunjungan Selesai</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['completed_today']) }}</span>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Selesai</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('visits.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Tanggal -->
            <div class="md:col-span-3">
                <input type="date" 
                       name="date" 
                       value="{{ $date }}" 
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
            </div>

            <!-- Poli -->
            <div class="md:col-span-3">
                <select name="poli_id" 
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" {{ $poliId == $poli->id ? 'selected' : '' }}>
                            {{ $poli->name }} ({{ $poli->queue_prefix }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <select name="status" 
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="registered" {{ $status === 'registered' ? 'selected' : '' }}>Terdaftar</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Sedang Dilayani</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="md:col-span-3 relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari pasien / no. antrean..." 
                       class="w-full pl-3.5 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
            </div>

            <!-- Tombol Filter -->
            <div class="md:col-span-1 flex items-center gap-1">
                <button type="submit" 
                        class="w-full py-2.5 px-3 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-xl transition-colors text-center">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Kunjungan -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">No. Kunjungan</th>
                        <th class="py-3.5 px-5">Pasien</th>
                        <th class="py-3.5 px-5">Poli Tujuan</th>
                        <th class="py-3.5 px-5">No. Antrean</th>
                        <th class="py-3.5 px-5">Status Kunjungan</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($visits as $visit)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- No Kunjungan & Tanggal -->
                            <td class="py-4 px-5">
                                <a href="{{ route('visits.show', $visit) }}" class="font-bold text-teal-700 font-mono text-xs hover:underline">
                                    {{ $visit->visit_number }}
                                </a>
                                <span class="text-xs text-slate-400 block mt-0.5">
                                    {{ $visit->visit_date ? $visit->visit_date->format('d/m/Y') : '-' }} • {{ $visit->created_at ? $visit->created_at->format('H:i') : '' }} WIB
                                </span>
                            </td>

                            <!-- Pasien -->
                            <td class="py-4 px-5">
                                <a href="{{ route('patients.show', $visit->patient) }}" class="font-semibold text-slate-900 hover:text-teal-600 transition-colors block">
                                    {{ $visit->patient->name }}
                                </a>
                                <span class="text-xs text-slate-500 font-mono">
                                    {{ $visit->patient->medical_record_number }}
                                    @if($visit->patient->nik)
                                        • NIK: {{ $visit->patient->nik }}
                                    @endif
                                </span>
                            </td>

                            <!-- Poli -->
                            <td class="py-4 px-5">
                                <span class="font-medium text-slate-800">
                                    {{ $visit->poli->name ?? '-' }}
                                </span>
                                @if($visit->complaint)
                                    <p class="text-xs text-slate-500 truncate max-w-xs mt-0.5" title="{{ $visit->complaint }}">
                                        Keluhan: {{ $visit->complaint }}
                                    </p>
                                @endif
                            </td>

                            <!-- No Antrean -->
                            <td class="py-4 px-5">
                                @if ($visit->queue)
                                    <div class="inline-flex items-center gap-2">
                                        <span class="font-mono font-bold text-sm bg-teal-50 text-teal-800 border border-teal-200/80 px-2.5 py-1 rounded-lg">
                                            {{ $visit->queue->queue_code }}
                                        </span>
                                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ 
                                            $visit->queue->status === 'menunggu' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                            ($visit->queue->status === 'dipanggil' ? 'bg-blue-50 text-blue-700 border border-blue-200 animate-pulse' :
                                            ($visit->queue->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-700 border border-purple-200' :
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200')) }}">
                                            {{ $visit->queue->status_label }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Status Kunjungan -->
                            <td class="py-4 px-5">
                                @if ($visit->status === 'registered')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/60">
                                        Terdaftar
                                    </span>
                                @elseif ($visit->status === 'in_progress')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/60">
                                        Sedang Dilayani
                                    </span>
                                @elseif ($visit->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                        Dibatalkan
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('visits.show', $visit) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 text-xs font-semibold transition-colors">
                                    <span>Detail & Tiket</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-sm mx-auto text-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-slate-800 text-sm">Tidak ada data kunjungan</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Belum ada kunjungan yang tercatat untuk filter ini. Silakan buat kunjungan baru.
                                    </p>
                                    <a href="{{ route('visits.create') }}" class="inline-block mt-4 text-xs font-semibold text-teal-600 hover:underline">
                                        + Daftarkan Kunjungan Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($visits->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                {{ $visits->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
