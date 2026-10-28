@extends('layouts.app')

@section('title', 'Antrean Pasien - Role Perawat')
@section('page-title', 'Antrean pasien')

@section('content')
<div class="space-y-6">

    <!-- Header & Subtitle Sesuai Figma Frame: Antrean • Pemeriksaan awal dan dokter -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean pasien</h2>
            <p class="text-xs text-slate-500 mt-1">
                {{ $polis->firstWhere('id', $poliId)?->name ?? 'Poli Umum' }} • Nomor antrean tetap sepanjang kunjungan.
            </p>
        </div>
        <div>
            <a href="{{ route('perawat.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                <span>← Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Counters Ringkas Sesuai Figma -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs">
            <span class="text-xs font-medium text-slate-500">Menunggu awal</span>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['menunggu'] }}</div>
            <span class="text-[10px] text-slate-400 block mt-1">Petugas: Ns. Maya Putri</span>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-2xs">
            <span class="text-xs font-medium text-slate-500">Dipanggil</span>
            <div class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['dipanggil'] }}</div>
            <span class="text-[10px] text-slate-400 block mt-1">
                @if ($activeCall)
                    {{ $activeCall->queue_code }} • panggilan {{ $activeCall->called_at ? $activeCall->called_at->format('H.i') : '-' }}
                @else
                    Tidak ada panggilan
                @endif
            </span>
        </div>
    </div>

    <!-- Panggilan Aktif Card Sesuai Mockup Figma -->
    @if ($activeCall)
        <div class="p-5 rounded-2xl bg-teal-50 border border-teal-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-teal-800">Panggilan aktif</span>
                <div class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="font-mono text-teal-700 bg-white px-2 py-0.5 rounded border border-teal-200">{{ $activeCall->queue_code }}</span>
                    <span>{{ $activeCall->visit->patient->name }}</span>
                    <span class="text-xs text-slate-500 font-mono">RM-{{ $activeCall->visit->patient->medical_record_number }}</span>
                </div>
                <p class="text-xs text-teal-700">
                    {{ $activeCall->visit->visit_number }} • Dipanggil pukul {{ $activeCall->called_at ? $activeCall->called_at->format('H.i') : now()->format('H.i') }} WIB
                </p>
            </div>
            <div>
                <a href="{{ route('perawat.examination', $activeCall) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-xs shadow-sm shadow-teal-200 transition-colors">
                    <span>Mulai Pemeriksaan Awal</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Filter & Pencarian Sesuai Figma -->
    <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('perawat.queue') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <div class="sm:col-span-5">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nomor antrean, nama, atau RM" 
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
            </div>

            <div class="sm:col-span-3">
                <select name="poli_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">Semua Poli</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" {{ $poliId == $poli->id ? 'selected' : '' }}>
                            {{ $poli->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu awal</option>
                    <option value="dipanggil" {{ $status === 'dipanggil' ? 'selected' : '' }}>Dipanggil</option>
                    <option value="sedang_dilayani" {{ $status === 'sedang_dilayani' ? 'selected' : '' }}>Sedang diperiksa</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai / Dikirim ke dokter</option>
                </select>
            </div>

            <div class="sm:col-span-1 flex gap-1">
                <button type="submit" class="w-full py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs rounded-xl transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Antrean Awal / Empty State Sesuai Frame Figma: Antrean • Keadaan kosong -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-sm">Daftar antrean awal</h3>
            <span class="text-xs text-slate-400">Total data: {{ $queues->total() }}</span>
        </div>

        @if ($queues->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-5">Nomor / Daftar</th>
                            <th class="py-3 px-5">Pasien / RM</th>
                            <th class="py-3 px-5">Kunjungan</th>
                            <th class="py-3 px-5">Status antrean</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($queues as $q)
                            <tr class="hover:bg-slate-50/75 transition-colors">
                                <td class="py-3.5 px-5 font-medium text-slate-900">
                                    <span class="font-bold font-mono text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded border border-teal-200">{{ $q->queue_code }}</span>
                                    <span class="text-slate-400 ml-1.5">• {{ $q->created_at ? $q->created_at->format('H.i') : '-' }}</span>
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="font-semibold text-slate-900 block">{{ $q->visit->patient->name }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $q->visit->patient->medical_record_number }}</span>
                                </td>
                                <td class="py-3.5 px-5 font-mono text-slate-600 text-[11px]">
                                    {{ $q->visit->visit_number }}
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ 
                                        $q->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                                        ($q->status === 'dipanggil' ? 'bg-blue-50 text-blue-800 border border-blue-200' :
                                        ($q->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                        'bg-emerald-50 text-emerald-800 border border-emerald-200')) }}">
                                        {{ $q->status_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <a href="{{ route('perawat.examination', $q) }}" 
                                       class="inline-flex items-center gap-1 font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                                        <span>Periksa</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($queues->hasPages())
                <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    {{ $queues->links() }}
                </div>
            @endif
        @else
            <!-- Empty State Sesuai Figma Frame 3: Antrean • Keadaan kosong setelah filter -->
            <div class="py-16 px-6 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-900 text-sm">Tidak ada antrean yang sesuai filter</h4>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                    {{ $search ? "Pencarian '{$search}' tidak ditemukan." : 'Tidak ada pasien pada filter ini.' }} Hapus filter untuk melihat antrean sesuai penugasan Anda.
                </p>
                <div class="mt-4">
                    <a href="{{ route('perawat.queue') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                        Reset pencarian & filter
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Alur Interaksi Footer Sesuai Figma Frame 2 -->
    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500">
        <span class="font-semibold text-slate-700">Alur status:</span>
        <span class="ml-1">Panggil → Dipanggil → Mulai pemeriksaan → Dalam pemeriksaan. Kirim hasil perawat → dapat dimulai oleh dokter yang ditugaskan.</span>
    </div>

</div>
@endsection
