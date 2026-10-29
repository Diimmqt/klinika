@extends('layouts.app')

@section('title', 'Pemeriksaan Medis - ' . $queue->queue_code . ' (' . $patient->name . ')')
@section('page-title', 'Pemeriksaan Medis Dokter')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('dokter.index') }}" class="hover:text-teal-600">Beranda Dokter</a>
        <span>/</span>
        <a href="{{ route('dokter.queue') }}" class="hover:text-teal-600">Antrean</a>
        <span>/</span>
        <span class="font-mono font-semibold text-slate-700">{{ $queue->queue_code }}</span>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Pemeriksaan Medis & RME</span>
    </nav>

    <!-- Header Identitas Pasien & Encounter Aktif -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-1 rounded-md bg-teal-50 text-teal-800 font-mono font-bold text-xs border border-teal-200">
                        {{ $queue->queue_code }}
                    </span>
                    <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                        {{ $queue->visit->visit_number }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                        $queue->status === 'menunggu' ? 'bg-amber-50 text-amber-800 border border-amber-200' :
                        ($queue->status === 'sedang_dilayani' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                        'bg-emerald-50 text-emerald-800 border border-emerald-200') }}">
                        Status: {{ $queue->status_label }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200">
                        Encounter Aktif
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mt-2">{{ $patient->name }}</h2>
                <p class="text-xs text-slate-500 font-mono mt-0.5">
                    No. RM: <strong class="text-slate-800 font-mono">RM-{{ $patient->medical_record_number }}</strong> &bull; 
                    NIK: {{ $patient->nik ?? '-' }} &bull; 
                    {{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                    {{ $patient->birth_date ? $patient->birth_date->translatedFormat('d F Y') : '-' }} ({{ $patient->age ?? '-' }} thn) &bull; 
                    Gol. Darah: <strong class="text-slate-700">{{ $patient->blood_type ?? '-' }}</strong>
                </p>
            </div>

            <!-- Quick Action: Akses RME Lengkap -->
            <div class="flex items-center gap-2">
                <a href="{{ route('dokter.rme', $patient) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Buka RME di Tab Baru</span>
                </a>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-slate-400 block text-[11px]">Poli Tujuan:</span>
                <span class="font-semibold text-slate-800">{{ $queue->poli->name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Tanggal Kunjungan:</span>
                <span class="font-semibold text-slate-800">{{ $queue->visit->visit_date ? $queue->visit->visit_date->translatedFormat('d M Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Waktu Registrasi:</span>
                <span class="font-semibold text-slate-800 font-mono">{{ $queue->created_at ? $queue->created_at->format('H.i') : '-' }} WIB</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Dokter Pemeriksa:</span>
                <span class="font-semibold text-teal-800">{{ auth()->user()?->name ?? 'dr. Andi Prasetyo' }}</span>
            </div>
        </div>
    </div>

    <!-- Alert / Kartu Pemeriksaan Awal Perawat -->
    <div class="p-5 rounded-2xl bg-gradient-to-r from-teal-50/70 to-emerald-50/40 border border-teal-200/80 shadow-xs space-y-3">
        <div class="flex items-center justify-between border-b border-teal-100 pb-2.5">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-600 animate-pulse"></span>
                <h3 class="font-bold text-teal-900 text-sm">Hasil Pemeriksaan Awal & Tanda Vital (Perawat)</h3>
            </div>
            <span class="text-xs text-teal-700 font-medium">
                Pemeriksa: {{ $assessment['recorded_by'] ?? 'Perawat Poli' }} 
                @if (!empty($assessment['recorded_at']))
                    &bull; {{ \Carbon\Carbon::parse($assessment['recorded_at'])->format('H.i') }} WIB
                @endif
            </span>
        </div>

        @if (!empty($vitals))
            <!-- Grid Tanda Vital -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2.5 pt-1">
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Tekanan Darah</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">
                        {{ $vitals['sistolik'] ?? '-' }}/{{ $vitals['diastolik'] ?? '-' }}
                    </span>
                    <span class="text-[9px] text-slate-400 block">mmHg</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Nadi</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">{{ $vitals['nadi'] ?? '-' }}</span>
                    <span class="text-[9px] text-slate-400 block">x/menit</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Suhu Tubuh</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">{{ $vitals['suhu'] ?? '-' }}</span>
                    <span class="text-[9px] text-slate-400 block">°C</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">SpO2</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">{{ $vitals['spo2'] ?? '-' }}</span>
                    <span class="text-[9px] text-slate-400 block">%</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Berat Badan</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">{{ $vitals['berat_badan'] ?? '-' }}</span>
                    <span class="text-[9px] text-slate-400 block">kg</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Kesadaran</span>
                    <span class="text-xs font-bold text-slate-800 block truncate mt-0.5">{{ $vitals['kesadaran'] ?? 'Compos mentis' }}</span>
                </div>
                <div class="p-2.5 rounded-xl bg-white/90 border border-teal-100 text-center shadow-2xs">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Skala Nyeri</span>
                    <span class="text-sm font-bold text-slate-800 font-mono">{{ $vitals['skala_nyeri'] ?? '0' }}/10</span>
                </div>
            </div>

            <!-- Keluhan Awal & Catatan Perawat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                <div class="p-3 rounded-xl bg-white/80 border border-teal-100">
                    <span class="font-bold text-teal-900 block text-[11px] mb-1">Keluhan Pasien saat Pendaftaran/Awal:</span>
                    <p class="text-slate-700 italic">"{{ $assessment['keluhan_utama'] ?? '-' }}"</p>
                </div>
                <div class="p-3 rounded-xl bg-white/80 border border-teal-100">
                    <span class="font-bold text-teal-900 block text-[11px] mb-1">Observasi / Catatan Khusus Perawat:</span>
                    <p class="text-slate-700">{{ $assessment['catatan_perawat'] ?? 'Tidak ada catatan observasi khusus.' }}</p>
                </div>
            </div>
        @else
            <div class="p-3 text-xs text-amber-800 bg-amber-50 rounded-xl border border-amber-200">
                Pemeriksaan tanda vital awal oleh perawat belum tersedia untuk kunjungan ini. Dokter dapat langsung mencatat tanda vital pada bagian Objective (O).
            </div>
        @endif
    </div>

    <!-- Layout Dua Kolom: Form Pemeriksaan Dokter (Kiri) vs RME Riwayat Kunjungan Terdahulu (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Kolom Kiri: Form Utama Pemeriksaan Medis (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">

            <form method="POST" action="{{ route('dokter.examination.store', $queue) }}" id="doctorExamForm" class="space-y-6">
                @csrf

                <!-- Section 1: Catatan SOAP -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Catatan Medis SOAP (Subjective, Objective, Assessment, Plan)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Dokumentasi standar medis elektronik encounter aktif</p>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-600">
                            Wajib Diisi
                        </span>
                    </div>

                    <!-- S: Subjective -->
                    <div>
                        <label for="subjective" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span><strong class="text-teal-700 font-bold">S</strong> - Subjective (Keluhan Utama & Riwayat Penyakit Sekarang) <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-slate-400 font-normal">Informasi langsung dari pasien</span>
                        </label>
                        <textarea id="subjective" 
                                  name="subjective" 
                                  rows="3" 
                                  required
                                  placeholder="Contoh: Pasien mengeluhkan pusing berputar sejak 2 hari yang lalu, mual (+), muntah (-). Keluhan bertambah berat saat menoleh tiba-tiba..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed">{{ old('subjective', $soap['subjective'] ?? '') }}</textarea>
                    </div>

                    <!-- O: Objective -->
                    <div>
                        <label for="objective" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span><strong class="text-teal-700 font-bold">O</strong> - Objective (Pemeriksaan Fisik & Temuan Klinis Objektif) <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-slate-400 font-normal">Hasil inspeksi, palpasi, perkusi, auskultasi</span>
                        </label>
                        <textarea id="objective" 
                                  name="objective" 
                                  rows="3" 
                                  required
                                  placeholder="Contoh: Keadaan umum tampak lemas, kesadaran compos mentis. Mata: anemis (-), ikterik (-). Thoraks: cor/pulmo DBN. Nistagmus horizontal (+)..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed">{{ old('objective', $soap['objective'] ?? '') }}</textarea>
                    </div>

                    <!-- A: Assessment -->
                    <div>
                        <label for="assessment" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span><strong class="text-teal-700 font-bold">A</strong> - Assessment (Penilaian Klinis / Kesimpulan Dokter) <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-slate-400 font-normal">Diagnosis kerja / banding</span>
                        </label>
                        <textarea id="assessment" 
                                  name="assessment" 
                                  rows="2" 
                                  required
                                  placeholder="Contoh: Benign Paroxysmal Positional Vertigo (BPPV) dd/ Meniere Disease..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed">{{ old('assessment', $soap['assessment'] ?? '') }}</textarea>
                    </div>

                    <!-- P: Plan -->
                    <div>
                        <label for="plan" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                            <span><strong class="text-teal-700 font-bold">P</strong> - Plan (Rencana Penanganan, Edukasi, & Rujukan) <span class="text-rose-500">*</span></span>
                            <span class="text-[11px] text-slate-400 font-normal">Instruksi medikamentosa dan non-farmakologis</span>
                        </label>
                        <textarea id="plan" 
                                  name="plan" 
                                  rows="2" 
                                  required
                                  placeholder="Contoh: Manuver Epley di klinik, istirahat cukup, hindari perubahan posisi kepala mendadak, resep obat oral..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all leading-relaxed">{{ old('plan', $soap['plan'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Section 2: Diagnosis ICD-10 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Diagnosis Medis (ICD-10)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Klasifikasi diagnosis penyakit standar ICD-10 (minimal satu diagnosis primer)</p>
                        </div>
                        <button type="button" onclick="addDiagnosisRow()" class="inline-flex items-center gap-1 text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                            <span>+ Tambah Diagnosis</span>
                        </button>
                    </div>

                    <!-- Quick Picks ICD-10 Umum -->
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-1.5">Pilihan Cepat Diagnosis Umum:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($commonIcd10 as $icd)
                                <button type="button" 
                                        onclick="pickQuickIcd('{{ $icd['code'] }}', '{{ $icd['name'] }}')"
                                        class="px-2 py-1 rounded-md text-[11px] bg-slate-100 hover:bg-teal-50 hover:text-teal-800 hover:border-teal-300 border border-slate-200 text-slate-700 transition-all cursor-pointer">
                                    <strong class="font-mono">{{ $icd['code'] }}</strong> - {{ Str::limit($icd['name'], 22) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Container Tabel Diagnosis -->
                    <div class="space-y-2.5" id="diagnosisRowsContainer">
                        @if (!empty($diagnoses))
                            @foreach ($diagnoses as $i => $diag)
                                <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center diagnosis-row">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Kode ICD-10</label>
                                        <input type="text" name="diagnosis_code[]" value="{{ $diag['code'] ?? '' }}" required placeholder="I10" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white font-mono font-bold text-teal-800">
                                    </div>
                                    <div class="col-span-6 sm:col-span-5">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Diagnosis</label>
                                        <input type="text" name="diagnosis_name[]" value="{{ $diag['name'] ?? '' }}" required placeholder="Nama penyakit" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                    </div>
                                    <div class="col-span-3 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Tipe</label>
                                        <select name="diagnosis_type[]" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                            <option value="primary" {{ ($diag['type'] ?? '') === 'primary' ? 'selected' : '' }}>Primer</option>
                                            <option value="secondary" {{ ($diag['type'] ?? '') === 'secondary' ? 'selected' : '' }}>Sekunder</option>
                                        </select>
                                    </div>
                                    <div class="col-span-11 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Catatan</label>
                                        <input type="text" name="diagnosis_notes[]" value="{{ $diag['notes'] ?? '' }}" placeholder="Opsional" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                    </div>
                                    <div class="col-span-1 text-right">
                                        <button type="button" onclick="this.closest('.diagnosis-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold" title="Hapus baris">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default 1 Row Primer -->
                            <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center diagnosis-row">
                                <div class="col-span-3 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Kode ICD-10 <span class="text-rose-500">*</span></label>
                                    <input type="text" name="diagnosis_code[]" required placeholder="Contoh: I10" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white font-mono font-bold text-teal-800">
                                </div>
                                <div class="col-span-6 sm:col-span-5">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Diagnosis <span class="text-rose-500">*</span></label>
                                    <input type="text" name="diagnosis_name[]" required placeholder="Nama penyakit / deskripsi" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                </div>
                                <div class="col-span-3 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Tipe</label>
                                    <select name="diagnosis_type[]" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                        <option value="primary">Primer</option>
                                        <option value="secondary">Sekunder</option>
                                    </select>
                                </div>
                                <div class="col-span-11 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Catatan</label>
                                    <input type="text" name="diagnosis_notes[]" placeholder="Opsional" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                </div>
                                <div class="col-span-1 text-right">
                                    <button type="button" onclick="this.closest('.diagnosis-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold" title="Hapus baris">&times;</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section 3: Tindakan Medis -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Tindakan Medis & Prosedur Pelayanan</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Catat tindakan diagnostik atau terapeutik yang diberikan dokter</p>
                        </div>
                        <button type="button" onclick="addActionRow()" class="inline-flex items-center gap-1 text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                            <span>+ Tambah Tindakan</span>
                        </button>
                    </div>

                    <!-- Quick Picks Tindakan -->
                    <div>
                        <span class="text-[11px] font-semibold text-slate-500 block mb-1.5">Pilihan Tindakan Cepat:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach (['Konsultasi Medis & KIE', 'Pemeriksaan Fisik Lanjutan', 'Nebulisasi', 'Perawatan Luka Sederhana', 'Injeksi Intramuskular', 'Pemeriksaan GDS Point of Care', 'Ekstraksi Cerumen'] as $actName)
                                <button type="button" 
                                        onclick="pickQuickAction('{{ $actName }}')"
                                        class="px-2 py-1 rounded-md text-[11px] bg-slate-100 hover:bg-teal-50 hover:text-teal-800 border border-slate-200 text-slate-700 transition-all cursor-pointer">
                                    + {{ $actName }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2.5" id="actionRowsContainer">
                        @if (!empty($actions))
                            @foreach ($actions as $act)
                                <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center action-row">
                                    <div class="col-span-6">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Tindakan</label>
                                        <input type="text" name="actions[][name]" value="{{ $act['name'] ?? '' }}" placeholder="Nama tindakan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                    </div>
                                    <div class="col-span-5">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Keterangan / Hasil Tindakan</label>
                                        <input type="text" name="actions[][notes]" value="{{ $act['notes'] ?? '' }}" placeholder="Keterangan singkat" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                    </div>
                                    <div class="col-span-1 text-right">
                                        <button type="button" onclick="this.closest('.action-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center action-row">
                                <div class="col-span-6">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Tindakan</label>
                                    <input type="text" name="actions[][name]" value="Konsultasi Medis & KIE" placeholder="Nama tindakan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                </div>
                                <div class="col-span-5">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Keterangan / Catatan Tindakan</label>
                                    <input type="text" name="actions[][notes]" placeholder="Edukasi istirahat dan hidrasi" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                </div>
                                <div class="col-span-1 text-right">
                                    <button type="button" onclick="this.closest('.action-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section 4: Resep Obat Elektronik -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Resep Obat Medis (E-Prescription)</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Terintegrasi otomatis ke antrean penyerahan obat Apoteker</p>
                        </div>
                        <button type="button" onclick="addPrescriptionRow()" class="inline-flex items-center gap-1 text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors">
                            <span>+ Tambah Obat</span>
                        </button>
                    </div>

                    <div class="space-y-2.5" id="prescriptionRowsContainer">
                        @if (!empty($prescriptions))
                            @foreach ($prescriptions as $rx)
                                <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center prescription-row">
                                    <div class="col-span-12 sm:col-span-4">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Obat & Kekuatan</label>
                                        <input type="text" name="prescriptions[][medicine_name]" value="{{ $rx['medicine_name'] ?? '' }}" placeholder="Contoh: Paracetamol 500mg" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                    </div>
                                    <div class="col-span-4 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Dosis</label>
                                        <input type="text" name="prescriptions[][dose]" value="{{ $rx['dose'] ?? '' }}" placeholder="1 tablet" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                    </div>
                                    <div class="col-span-4 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Frekuensi</label>
                                        <input type="text" name="prescriptions[][frequency]" value="{{ $rx['frequency'] ?? '' }}" placeholder="3x1 hari" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                    </div>
                                    <div class="col-span-4 sm:col-span-2">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Aturan Pakai</label>
                                        <input type="text" name="prescriptions[][instruction]" value="{{ $rx['instruction'] ?? '' }}" placeholder="Sesudah makan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                    </div>
                                    <div class="col-span-3 sm:col-span-1">
                                        <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Jumlah</label>
                                        <input type="number" name="prescriptions[][quantity]" value="{{ $rx['quantity'] ?? '10' }}" min="1" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-center font-mono font-bold text-slate-800">
                                    </div>
                                    <div class="col-span-1 text-right">
                                        <button type="button" onclick="this.closest('.prescription-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center prescription-row">
                                <div class="col-span-12 sm:col-span-4">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Obat & Kekuatan</label>
                                    <input type="text" name="prescriptions[][medicine_name]" placeholder="Contoh: Paracetamol 500mg tablet" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
                                </div>
                                <div class="col-span-4 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Dosis</label>
                                    <input type="text" name="prescriptions[][dose]" placeholder="1 tab" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                </div>
                                <div class="col-span-4 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Frekuensi</label>
                                    <input type="text" name="prescriptions[][frequency]" placeholder="3x1 hari" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                </div>
                                <div class="col-span-4 sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Aturan Pakai</label>
                                    <input type="text" name="prescriptions[][instruction]" placeholder="Bila demam / sesudah makan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                                </div>
                                <div class="col-span-3 sm:col-span-1">
                                    <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Jumlah</label>
                                    <input type="number" name="prescriptions[][quantity]" value="10" min="1" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-center font-mono font-bold text-slate-800">
                                </div>
                                <div class="col-span-1 text-right">
                                    <button type="button" onclick="this.closest('.prescription-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sticky Action Bar Bawah -->
                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sticky bottom-4 z-10">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="w-2.5 h-2.5 rounded-full {{ !empty($doctorExam['is_draft']) ? 'bg-amber-500' : ($queue->status === 'selesai' ? 'bg-emerald-500' : 'bg-slate-300') }}"></span>
                        <span class="text-slate-600 font-medium">
                            @if ($queue->status === 'selesai')
                                Status: Pemeriksaan Medis Selesai &bull; Kunjungan Selesai
                            @elseif (!empty($doctorExam['is_draft']))
                                Status: Draf Tersimpan Sementara
                            @else
                                Status: Pemeriksaan Medis Aktif
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('dokter.queue') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                            Kembali ke Antrean
                        </a>

                        <button type="submit" 
                                name="action" 
                                value="draft" 
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-xs transition-colors">
                            Simpan Draf
                        </button>

                        <button type="submit" 
                                name="action" 
                                value="submit" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold text-xs shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                            <span>Selesaikan Pemeriksaan</span>
                            <span>&rarr;</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>

        <!-- Kolom Kanan: Panel RME Pasien (Riwayat Kunjungan Sebelumnya) (4 Kolom) -->
        <div class="lg:col-span-4 space-y-4">

            <!-- Card Header Riwayat RME -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider">
                            Riwayat Kunjungan (RME)
                        </h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Catatan medis encounter terdahulu</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                        {{ $pastVisits->count() }} Kunjungan Lama
                    </span>
                </div>

                @if ($pastVisits->isNotEmpty())
                    <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                        @foreach ($pastVisits as $past)
                            @php
                                $pastDoctorExam = $past->doctor_examination;
                                $pastSoap = $pastDoctorExam['soap'] ?? null;
                                $pastVitals = $past->assessment_data['vital_signs'] ?? null;
                                $pastDiag = $pastDoctorExam['diagnosis'] ?? [];
                            @endphp
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900 font-mono">{{ $past->visit_number }}</span>
                                    <span class="text-[11px] text-slate-500">
                                        {{ $past->visit_date ? $past->visit_date->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-500 flex items-center justify-between">
                                    <span>Poli: <strong>{{ $past->poli->name }}</strong></span>
                                    <span>Status: <span class="capitalize text-slate-700 font-medium">{{ $past->status_label }}</span></span>
                                </div>

                                <!-- Tanda Vital Terdahulu -->
                                @if ($pastVitals)
                                    <div class="pt-1.5 border-t border-slate-200/60 text-[11px] text-slate-600 flex flex-wrap gap-2">
                                        <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono">
                                            TD: {{ $pastVitals['sistolik'] ?? '-' }}/{{ $pastVitals['diastolik'] ?? '-' }}
                                        </span>
                                        <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono">
                                            Nadi: {{ $pastVitals['nadi'] ?? '-' }}
                                        </span>
                                        <span class="bg-white px-2 py-0.5 rounded border border-slate-200 font-mono">
                                            Suhu: {{ $pastVitals['suhu'] ?? '-' }}°C
                                        </span>
                                    </div>
                                @endif

                                <!-- Diagnosis Terdahulu -->
                                @if (!empty($pastDiag))
                                    <div class="pt-1 text-[11px]">
                                        <span class="text-slate-400 block text-[10px] font-semibold uppercase">Diagnosis:</span>
                                        @foreach ($pastDiag as $d)
                                            <span class="inline-block px-1.5 py-0.5 rounded bg-teal-50 text-teal-800 font-mono text-[10px] border border-teal-200 mr-1 mt-0.5">
                                                {{ $d['code'] ?? '' }} - {{ $d['name'] ?? '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- SOAP Terdahulu Singkat -->
                                @if ($pastSoap)
                                    <div class="pt-1 text-[11px] text-slate-600">
                                        <span class="text-slate-400 block text-[10px] font-semibold uppercase">Assessment / Terapi:</span>
                                        <p class="truncate text-slate-700">{{ $pastSoap['assessment'] ?? $pastSoap['plan'] ?? '-' }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Pasien ini baru pertama kali berkunjung ke klinik (belum ada riwayat kunjungan terdahulu).</span>
                    </div>
                @endif
            </div>

            <!-- Informasi Pasien Ringkas -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-3 text-xs">
                <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider pb-2 border-b border-slate-100">
                    Kontak & Alamat Pasien
                </h4>
                <div class="space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Telepon:</span>
                        <span class="font-medium text-slate-800">{{ $patient->phone ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Alamat Domisili:</span>
                        <span class="font-medium text-slate-800">{{ $patient->address ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Kontak Darurat:</span>
                        <span class="font-medium text-slate-800">
                            {{ $patient->emergency_contact_name ?? '-' }}
                            @if ($patient->emergency_contact_relationship)
                                ({{ $patient->emergency_contact_relationship }})
                            @endif
                            - {{ $patient->emergency_contact_phone ?? '' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JavaScript Interaktif untuk Dynamic Repeater Diagnosis, Tindakan, dan Resep -->
<script>
function pickQuickIcd(code, name) {
    const firstRow = document.querySelector('.diagnosis-row');
    if (firstRow) {
        const codeInput = firstRow.querySelector('input[name="diagnosis_code[]"]');
        const nameInput = firstRow.querySelector('input[name="diagnosis_name[]"]');
        if (codeInput && nameInput && (!codeInput.value || codeInput.value === '')) {
            codeInput.value = code;
            nameInput.value = name;
            return;
        }
    }
    // Jika baris pertama sudah terisi, tambahkan baris baru
    addDiagnosisRow(code, name, 'secondary');
}

function addDiagnosisRow(code = '', name = '', type = 'secondary') {
    const container = document.getElementById('diagnosisRowsContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center diagnosis-row';
    row.innerHTML = `
        <div class="col-span-3 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Kode ICD-10 <span class="text-rose-500">*</span></label>
            <input type="text" name="diagnosis_code[]" value="${code}" required placeholder="Kode" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white font-mono font-bold text-teal-800">
        </div>
        <div class="col-span-6 sm:col-span-5">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Diagnosis <span class="text-rose-500">*</span></label>
            <input type="text" name="diagnosis_name[]" value="${name}" required placeholder="Nama penyakit" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
        </div>
        <div class="col-span-3 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Tipe</label>
            <select name="diagnosis_type[]" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
                <option value="primary" ${type === 'primary' ? 'selected' : ''}>Primer</option>
                <option value="secondary" ${type === 'secondary' ? 'selected' : ''}>Sekunder</option>
            </select>
        </div>
        <div class="col-span-11 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Catatan</label>
            <input type="text" name="diagnosis_notes[]" placeholder="Opsional" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
        </div>
        <div class="col-span-1 text-right">
            <button type="button" onclick="this.closest('.diagnosis-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold" title="Hapus baris">&times;</button>
        </div>
    `;
    container.appendChild(row);
}

function pickQuickAction(actionName) {
    addActionRow(actionName);
}

function addActionRow(actionName = '') {
    const container = document.getElementById('actionRowsContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center action-row';
    row.innerHTML = `
        <div class="col-span-6">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Tindakan</label>
            <input type="text" name="actions[][name]" value="${actionName}" placeholder="Nama tindakan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
        </div>
        <div class="col-span-5">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Keterangan / Catatan</label>
            <input type="text" name="actions[][notes]" placeholder="Keterangan singkat" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
        </div>
        <div class="col-span-1 text-right">
            <button type="button" onclick="this.closest('.action-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
        </div>
    `;
    container.appendChild(row);
}

function addPrescriptionRow() {
    const container = document.getElementById('prescriptionRowsContainer');
    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 p-3 rounded-xl bg-slate-50/70 border border-slate-200 items-center prescription-row';
    row.innerHTML = `
        <div class="col-span-12 sm:col-span-4">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Nama Obat & Kekuatan</label>
            <input type="text" name="prescriptions[][medicine_name]" placeholder="Nama obat" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-800">
        </div>
        <div class="col-span-4 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Dosis</label>
            <input type="text" name="prescriptions[][dose]" placeholder="1 tab" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
        </div>
        <div class="col-span-4 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Frekuensi</label>
            <input type="text" name="prescriptions[][frequency]" placeholder="3x1 hari" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
        </div>
        <div class="col-span-4 sm:col-span-2">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Aturan Pakai</label>
            <input type="text" name="prescriptions[][instruction]" placeholder="Sesudah makan" class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-700">
        </div>
        <div class="col-span-3 sm:col-span-1">
            <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-0.5">Jumlah</label>
            <input type="number" name="prescriptions[][quantity]" value="10" min="1" class="w-full px-2 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-center font-mono font-bold text-slate-800">
        </div>
        <div class="col-span-1 text-right">
            <button type="button" onclick="this.closest('.prescription-row').remove()" class="text-rose-500 hover:text-rose-700 p-1 text-sm font-bold">&times;</button>
        </div>
    `;
    container.appendChild(row);
}
</script>
@endsection

