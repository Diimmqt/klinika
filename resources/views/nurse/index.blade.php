@extends('layouts.app')

@section('title', 'Pemeriksaan Awal Hari Ini - Role Perawat')
@section('page-title', 'Pemeriksaan awal hari ini')

@section('content')
<div class="space-y-6">

    <!-- Header & Subtitle Sesuai Figma Frame: Dashboard • Perawat -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pemeriksaan awal hari ini</h2>
            <p class="text-xs text-slate-500 mt-1">
                Antrean sesuai penugasan • {{ $polis->firstWhere('id', $poliId)?->name ?? 'Poli Umum' }} • Ns. Maya Putri
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('perawat.queue') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buka antrean awal</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards Sesuai Mockup Figma -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Menunggu awal -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Menunggu awal</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $menungguCount }}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 pt-2 border-t border-slate-100">Urutan berdasarkan pendaftaran</p>
        </div>

        <!-- Card 2: Sedang diperiksa -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Sedang diperiksa</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $sedangDiperiksaCount }}</div>
            </div>
            <p class="text-[11px] text-teal-700 font-medium truncate mt-3 pt-2 border-t border-slate-100">
                @if ($activePatient)
                    {{ $activePatient->queue_code }} • {{ $activePatient->visit->patient->name }}
                @else
                    Belum ada pasien aktif
                @endif
            </p>
        </div>

        <!-- Card 3: Selesai pemeriksaan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Selesai pemeriksaan</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $selesaiCount }}</div>
            </div>
            <p class="text-[11px] text-slate-400 mt-3 pt-2 border-t border-slate-100">Dikirim ke antrean dokter</p>
        </div>

        <!-- Card 4: Draf belum dikirim -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <span class="text-xs font-medium text-slate-500">Draf belum dikirim</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $drafCount }}</div>
            </div>
            <p class="text-[11px] text-amber-700 font-medium mt-3 pt-2 border-t border-slate-100">Perlu ditinjau petugas</p>
        </div>
    </div>

    <!-- Banner Peringatan Dua Identitas Sesuai Figma -->
    <div class="p-4 rounded-xl bg-teal-50/70 border border-teal-200 text-teal-900 flex items-start gap-3">
        <svg class="w-5 h-5 text-teal-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs">
            <span class="font-bold text-teal-900 block">Periksa dua identitas sebelum mencatat</span>
            <span class="text-teal-700">Cocokkan nama dan tanggal lahir dengan RM serta ID kunjungan yang tampil.</span>
        </div>
    </div>

    <!-- Tabel: Berikutnya di antrean awal -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">Berikutnya di antrean awal</h3>
            <span class="text-xs text-slate-400">Total menunggu: {{ $menungguCount }} pasien</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-5">Nomor / Waktu</th>
                        <th class="py-3.5 px-5">Pasien / RM</th>
                        <th class="py-3.5 px-5">Kunjungan</th>
                        <th class="py-3.5 px-5">Status antrean</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($waitingQueues as $queue)
                        <tr class="hover:bg-slate-50/75 transition-colors">
                            <td class="py-3.5 px-5 font-medium text-slate-900">
                                <span class="font-bold font-mono">{{ $queue->queue_code }}</span>
                                <span class="text-slate-400 ml-1.5">• {{ $queue->created_at ? $queue->created_at->format('H.i') : '-' }}</span>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="font-semibold text-slate-900 block">{{ $queue->visit->patient->name }}</span>
                                <span class="text-[11px] text-slate-400 font-mono">{{ $queue->visit->patient->medical_record_number }}</span>
                            </td>
                            <td class="py-3.5 px-5 font-mono text-slate-600 text-[11px]">
                                {{ $queue->visit->visit_number }}
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                    {{ $queue->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-right">
                                <a href="{{ route('perawat.examination', $queue) }}" 
                                   class="inline-flex items-center gap-1 font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                                    <span>Panggil & periksa</span>
                                    <span>→</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400">
                                Tidak ada antrean pasien yang menunggu pemeriksaan awal saat ini.
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

    <!-- Bottom Cards: Draf pemeriksaan & Penyerahan ke dokter Sesuai Figma -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Card Draf Pemeriksaan -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col justify-between">
            <div>
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Draf pemeriksaan</h4>
                @if ($draftQueues->isNotEmpty())
                    @foreach ($draftQueues->take(2) as $draft)
                        <div class="py-2 border-b border-slate-100 last:border-0 text-xs">
                            <span class="font-semibold text-slate-800">{{ $draft->visit->patient->name }}</span>
                            <span class="text-slate-400 font-mono text-[11px] ml-1">• RM-{{ $draft->visit->patient->medical_record_number }} • {{ $draft->visit->visit_number }}</span>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-slate-400 text-[11px]">Sedang dalam pemeriksaan • Belum dikirim ke dokter</span>
                                <a href="{{ route('perawat.examination', $draft) }}" class="text-teal-600 font-semibold hover:underline text-[11px]">
                                    Lanjutkan draf →
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-xs text-slate-400">Tidak ada draf pemeriksaan yang tertunda.</p>
                @endif
            </div>
        </div>

        <!-- Card Penyerahan ke dokter -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Penyerahan ke dokter</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                Isi keluhan dan tanda vital sesuai pengukuran. Setelah dikirim, dokter melihat hasil dalam konteks kunjungan yang sama. Catatan sebelumnya hanya-baca.
            </p>
        </div>
    </div>

</div>
@endsection
