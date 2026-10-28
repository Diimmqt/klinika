@extends('layouts.app')

@section('title', 'Pemeriksaan Awal - ' . $queue->queue_code)
@section('page-title', 'Pemeriksaan awal')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Breadcrumb Sesuai Figma Frame: Perawat • Pemeriksaan awal -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('perawat.index') }}" class="hover:text-teal-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('perawat.queue') }}" class="hover:text-teal-600">Antrean</a>
        <span>/</span>
        <span class="font-mono font-semibold text-slate-700">{{ $queue->queue_code }}</span>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Pemeriksaan awal</span>
    </nav>

    <!-- Header Judul Sesuai Mockup Figma -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pemeriksaan awal</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Ns. Maya Putri • Catat hasil pengukuran untuk encounter ini saja.
            </p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ 
                $queue->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                ($queue->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                'bg-teal-50 text-teal-800 border border-teal-200') }}">
                Status Antrean: {{ $queue->status_label }}
            </span>
        </div>
    </div>

    <!-- Header Identitas Pasien Sesuai Figma Frame 4 -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $queue->visit->patient->name }}</h3>
            <p class="text-xs text-slate-500 font-mono mt-0.5">
                RM-{{ $queue->visit->patient->medical_record_number }} • 
                {{ $queue->visit->patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} • 
                {{ $queue->visit->patient->birth_date ? $queue->visit->patient->birth_date->translatedFormat('d M Y') : '-' }} 
                ({{ $queue->visit->patient->age ?? '-' }} tahun) • 
                NIK {{ $queue->visit->patient->nik ?? '-' }}
            </p>
        </div>

        <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center gap-2 text-xs text-slate-600 font-medium">
            <span class="font-mono text-teal-700 bg-teal-50 px-2 py-0.5 rounded">{{ $queue->visit->visit_number }}</span>
            <span>•</span>
            <span>{{ $queue->visit->visit_date ? $queue->visit->visit_date->translatedFormat('d M Y') : '-' }}</span>
            <span>•</span>
            <span>{{ $queue->created_at ? $queue->created_at->format('H.i') : '-' }} WIB</span>
            <span>|</span>
            <span class="text-slate-800 font-bold">{{ $queue->poli->name }}</span>
            <span>|</span>
            <span>{{ $queue->visit->practitioner->name ?? 'dr. Andi Prasetyo' }}</span>
            <span>|</span>
            <span>Umum / pribadi</span>
            <span>|</span>
            <span class="font-mono font-bold text-teal-800">{{ $queue->queue_code }}</span>
        </div>
    </div>

    <!-- Form Pemeriksaan Awal -->
    <form method="POST" action="{{ route('perawat.examination.store', $queue) }}" class="space-y-6" id="examinationForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Kolom Kiri: Form Utama (2 Span) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Bagian 1: Keluhan dan riwayat singkat -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h4 class="font-bold text-slate-900 text-sm">Keluhan dan riwayat singkat</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Catat keluhan utama yang disampaikan langsung oleh pasien saat kedatangan.</p>
                    </div>

                    <div>
                        <label for="keluhan_utama" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Keluhan utama <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="keluhan_utama" 
                                  name="keluhan_utama" 
                                  rows="3" 
                                  placeholder="Contoh: Batuk dan tenggorokan tidak nyaman sejak dua hari. Tidak ada sesak yang dilaporkan."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed @error('keluhan_utama') border-rose-400 bg-rose-50/30 @enderror">{{ old('keluhan_utama', $assessment['keluhan_utama'] ?? $queue->visit->clean_complaint) }}</textarea>
                        @error('keluhan_utama')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bagian 2: Tanda vital • pengukuran -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h4 class="font-bold text-slate-900 text-sm">Tanda vital • pengukuran {{ now()->format('H.i') }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Isi angka sesuai pengukuran dan satuan yang ditampilkan. Contoh dokumentasi, bukan rekomendasi klinis.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Sistolik -->
                        <div>
                            <label for="sistolik" class="block text-xs font-semibold text-slate-700 mb-1">
                                Sistolik (mmHg) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="sistolik" 
                                   name="sistolik" 
                                   value="{{ old('sistolik', $vitals['sistolik'] ?? '') }}" 
                                   placeholder="118" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('sistolik') border-rose-400 bg-rose-50/30 @enderror">
                            @error('sistolik')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Diastolik -->
                        <div>
                            <label for="diastolik" class="block text-xs font-semibold text-slate-700 mb-1">
                                Diastolik (mmHg) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="diastolik" 
                                   name="diastolik" 
                                   value="{{ old('diastolik', $vitals['diastolik'] ?? '') }}" 
                                   placeholder="76" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('diastolik') border-rose-400 bg-rose-50/30 @enderror">
                            @error('diastolik')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nadi -->
                        <div>
                            <label for="nadi" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nadi (kali/menit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   id="nadi" 
                                   name="nadi" 
                                   value="{{ old('nadi', $vitals['nadi'] ?? '') }}" 
                                   placeholder="82" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('nadi') border-rose-400 bg-rose-50/30 @enderror">
                            @error('nadi')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Suhu -->
                        <div>
                            <label for="suhu" class="block text-xs font-semibold text-slate-700 mb-1">
                                Suhu (°C) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   step="0.1" 
                                   id="suhu" 
                                   name="suhu" 
                                   value="{{ old('suhu', $vitals['suhu'] ?? '') }}" 
                                   placeholder="37.2" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('suhu') border-rose-400 bg-rose-50/30 @enderror">
                            @error('suhu')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SpO2 -->
                        <div>
                            <label for="spo2" class="block text-xs font-semibold text-slate-700 mb-1">
                                SpO₂ (%)
                            </label>
                            <input type="number" 
                                   id="spo2" 
                                   name="spo2" 
                                   value="{{ old('spo2', $vitals['spo2'] ?? '') }}" 
                                   placeholder="98" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('spo2') border-rose-400 bg-rose-50/30 @enderror">
                        </div>

                        <!-- Berat Badan -->
                        <div>
                            <label for="berat_badan" class="block text-xs font-semibold text-slate-700 mb-1">
                                Berat badan (kg) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" 
                                   step="0.1" 
                                   id="berat_badan" 
                                   name="berat_badan" 
                                   value="{{ old('berat_badan', $vitals['berat_badan'] ?? '') }}" 
                                   placeholder="58" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('berat_badan') border-rose-400 bg-rose-50/30 @enderror">
                            @error('berat_badan')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kesadaran -->
                        <div>
                            <label for="kesadaran" class="block text-xs font-semibold text-slate-700 mb-1">
                                Kesadaran <span class="text-rose-500">*</span>
                            </label>
                            <select id="kesadaran" 
                                    name="kesadaran" 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 @error('kesadaran') border-rose-400 bg-rose-50/30 @enderror">
                                @foreach (['Compos mentis', 'Apatis', 'Somnolen', 'Sopor', 'Coma'] as $opt)
                                    <option value="{{ $opt }}" {{ old('kesadaran', $vitals['kesadaran'] ?? 'Compos mentis') === $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kesadaran')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skala Nyeri -->
                        <div>
                            <label for="skala_nyeri" class="block text-xs font-semibold text-slate-700 mb-1">
                                Skala nyeri (0–10)
                            </label>
                            <input type="number" 
                                   id="skala_nyeri" 
                                   name="skala_nyeri" 
                                   min="0" 
                                   max="10" 
                                   value="{{ old('skala_nyeri', $vitals['skala_nyeri'] ?? '0') }}" 
                                   placeholder="2" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono @error('skala_nyeri') border-rose-400 bg-rose-50/30 @enderror">
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Catatan perawat -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3">
                        <h4 class="font-bold text-slate-900 text-sm">Catatan perawat</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Observasi umum terkait kondisi fisik atau keluhan tambahan pasien.</p>
                    </div>

                    <div>
                        <label for="observasi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Observasi
                        </label>
                        <textarea id="observasi" 
                                  name="observasi" 
                                  rows="3" 
                                  placeholder="Pasien datang mandiri. Identitas dikonfirmasi dengan nama dan tanggal lahir. Hasil pemeriksaan awal siap ditinjau dokter."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed">{{ old('observasi', $assessment['catatan_perawat'] ?? '') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Ringkasan Info Pasien & Alergi Sesuai Mockup -->
            <div class="space-y-4">
                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">
                        Informasi Pasien
                    </h4>

                    <div class="space-y-2 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[11px]">Nomor Telepon:</span>
                            <span class="font-medium text-slate-800">{{ $queue->visit->patient->phone ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Tempat, Tanggal Lahir:</span>
                            <span class="font-medium text-slate-800">
                                {{ $queue->visit->patient->birth_place ?? '-' }}, 
                                {{ $queue->visit->patient->birth_date ? $queue->visit->patient->birth_date->translatedFormat('d F Y') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[11px]">Alamat:</span>
                            <span class="font-medium text-slate-800">{{ $queue->visit->patient->address ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Alert Riwayat Alergi -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-2">
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">
                        Riwayat Alergi
                    </h4>
                    <p class="text-xs text-slate-500">
                        Tidak ada riwayat alergi obat atau makanan tercatat. Konfirmasi ulang saat wawancara awal.
                    </p>
                </div>

                <!-- Petugas Pelaksana -->
                <div class="p-5 rounded-2xl bg-teal-50/70 border border-teal-100 text-xs text-teal-900">
                    <span class="font-bold block">Petugas Pemeriksa:</span>
                    <span class="mt-0.5 block">Ns. Maya Putri (Perawat Poli)</span>
                    <span class="text-[11px] text-teal-700 block mt-1">Waktu: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>

        </div>

        <!-- Sticky Bottom Action Bar Sesuai Figma Frame 4 -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sticky bottom-4 z-10">
            <div class="flex items-center gap-2 text-xs">
                <span class="w-2 h-2 rounded-full {{ !empty($assessment['is_draft']) ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
                <span class="text-slate-500 font-medium">
                    {{ !empty($assessment['is_draft']) ? 'Draf tersimpan' : 'Draf belum tersimpan' }}
                </span>
            </div>

            <div class="flex items-center gap-3">
                <!-- Tombol Simpan Draf -->
                <button type="submit" 
                        name="action" 
                        value="draft" 
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                    Simpan draf
                </button>

                <!-- Tombol Kirim ke Dokter -->
                <button type="submit" 
                        name="action" 
                        value="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                    <span>Kirim ke dokter</span>
                    <span>→</span>
                </button>
            </div>
        </div>

    </form>

</div>
@endsection
