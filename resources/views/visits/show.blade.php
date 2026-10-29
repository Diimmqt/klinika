@extends('layouts.app')

@section('title', 'Detail Kunjungan - ' . $visit->visit_number)
@section('page-title', 'Detail Kunjungan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('visits.index') }}" class="text-xs text-slate-500 hover:text-teal-600 transition-colors flex items-center gap-1 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Kunjungan</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Tiket</span>
            </button>

            @if ($visit->status !== 'cancelled' && $visit->status !== 'completed')
                <button type="button" 
                        onclick="document.getElementById('cancelVisitModal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>Batalkan Kunjungan</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Tiket Antrean & Header Kunjungan -->
    <div class="p-6 md:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            
            <!-- Box Nomor Antrean Fisik -->
            <div class="p-6 rounded-2xl bg-teal-50/80 border-2 border-dashed border-teal-300 text-center space-y-2">
                <span class="text-xs font-bold uppercase tracking-wider text-teal-800 block">
                    {{ $visit->poli->name }}
                </span>
                <div class="text-4xl font-extrabold font-mono text-teal-700 tracking-tight">
                    {{ $visit->queue ? $visit->queue->queue_code : '-' }}
                </div>
                <div class="pt-1">
                    <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full {{ 
                        $visit->queue && $visit->queue->status === 'menunggu' ? 'bg-amber-100 text-amber-800' :
                        ($visit->queue && $visit->queue->status === 'dipanggil' ? 'bg-blue-100 text-blue-800' :
                        ($visit->queue && $visit->queue->status === 'sedang_dilayani' ? 'bg-purple-100 text-purple-800' :
                        'bg-emerald-100 text-emerald-800')) }}">
                        Status: {{ $visit->queue ? $visit->queue->status_label : '-' }}
                    </span>
                </div>
                <span class="text-[11px] text-teal-900/60 block pt-1">
                    {{ $visit->visit_date ? $visit->visit_date->translatedFormat('d F Y') : '-' }}
                </span>
            </div>

            <!-- Ringkasan Info Kunjungan & Pasien -->
            <div class="md:col-span-2 space-y-4">
                <div>
                    <span class="text-xs font-mono font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded">
                        {{ $visit->visit_number }}
                    </span>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">
                        {{ $visit->patient->name }}
                    </h2>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">
                        No. RM: {{ $visit->patient->medical_record_number }} • NIK: {{ $visit->patient->nik ?? '-' }} • {{ $visit->patient->gender_label }} ({{ $visit->patient->age ?? '-' }} th)
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs pt-2 border-t border-slate-100">
                    <div>
                        <span class="text-slate-400 block">Tanggal Kunjungan:</span>
                        <span class="font-semibold text-slate-800">{{ $visit->visit_date ? $visit->visit_date->translatedFormat('l, d F Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Status Kunjungan:</span>
                        <span class="font-semibold text-slate-800">{{ $visit->status_label }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Petugas Pendaftaran:</span>
                        <span class="font-semibold text-slate-800">{{ $visit->creator->name ?? 'Petugas Pendaftaran' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Waktu Registrasi:</span>
                        <span class="font-semibold text-slate-800">{{ $visit->created_at ? $visit->created_at->format('d/m/Y H:i') : '-' }} WIB</span>
                    </div>
                </div>

                @if ($visit->complaint)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <span class="font-bold text-slate-700 block mb-0.5">Keluhan Pasien:</span>
                        <p class="text-slate-600 leading-relaxed">{{ $visit->complaint }}</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- Riwayat Status Antrean -->
        @if ($visit->queue && $visit->queue->statusLogs->isNotEmpty())
            <div class="mt-8 pt-6 border-t border-slate-100 space-y-3">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Riwayat Perubahan Status Antrean
                </h4>
                <div class="relative border-l-2 border-slate-200 ml-3 space-y-4 py-1">
                    @foreach ($visit->queue->statusLogs as $log)
                        <div class="relative pl-5">
                            <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full bg-teal-500 border-2 border-white"></div>
                            <div class="text-xs">
                                <span class="font-semibold text-slate-800 capitalize">
                                    {{ str_replace('_', ' ', $log->to_status) }}
                                </span>
                                @if ($log->from_status)
                                    <span class="text-slate-400">(dari {{ str_replace('_', ' ', $log->from_status) }})</span>
                                @endif
                                <span class="text-slate-400 block text-[11px] mt-0.5">
                                    {{ $log->changed_at ? \Carbon\Carbon::parse($log->changed_at)->translatedFormat('d M Y, H:i:s') : '-' }} WIB
                                    @if ($log->user)
                                        • Oleh: {{ $log->user->name }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>

<!-- Modal Konfirmasi Pembatalan Kunjungan -->
<div id="cancelVisitModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 space-y-4">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-base font-bold text-slate-900">Konfirmasi Pembatalan Kunjungan</h3>
            <p class="text-xs text-slate-500 mt-1">
                Apakah Anda yakin ingin membatalkan kunjungan untuk pasien ini?
            </p>
            <p class="text-sm font-semibold text-slate-800 mt-2 p-2 rounded-lg bg-slate-50">
                {{ $visit->patient->name }} • {{ $visit->visit_number }} ({{ $visit->queue ? $visit->queue->queue_code : '-' }})
            </p>
        </div>

        <form method="POST" action="{{ route('visits.destroy', $visit) }}">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" 
                        onclick="document.getElementById('cancelVisitModal').classList.add('hidden')" 
                        class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-colors">
                    Tutup
                </button>
                <button type="submit" 
                        class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium transition-colors shadow-sm shadow-rose-200">
                    Ya, Batalkan Kunjungan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
