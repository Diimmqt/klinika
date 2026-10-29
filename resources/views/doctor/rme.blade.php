@extends('layouts.app')

@section('title', 'Rekam Medis Elektronik (RME) - ' . $patient->name)
@section('page-title', 'Rekam Medis Elektronik (RME)')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Breadcrumb & Back Action -->
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('dokter.index') }}" class="hover:text-teal-600">Beranda Dokter</a>
            <span>/</span>
            <a href="{{ route('dokter.queue') }}" class="hover:text-teal-600">Antrean</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">RME Pasien</span>
        </nav>
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs transition-colors">
            <span>&larr;</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Header Profil Pasien Lengkap -->
    <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md bg-teal-50 text-teal-800 font-mono font-bold text-xs border border-teal-200">
                        RM-{{ $patient->medical_record_number }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                        {{ $visits->count() }} Total Kunjungan
                    </span>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mt-2">{{ $patient->name }}</h2>
                <p class="text-xs text-slate-500 font-mono mt-0.5">
                    NIK: {{ $patient->nik ?? '-' }} &bull; 
                    {{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} &bull; 
                    {{ $patient->birth_place ?? '-' }}, {{ $patient->birth_date ? $patient->birth_date->translatedFormat('d F Y') : '-' }} 
                    ({{ $patient->age ?? '-' }} tahun)
                </p>
            </div>
            <div class="text-xs text-right space-y-1">
                <span class="text-slate-400 block text-[11px]">Terdaftar pertama:</span>
                <span class="font-medium text-slate-700 font-mono">{{ $patient->created_at ? $patient->created_at->translatedFormat('d M Y') : '-' }}</span>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-400 block text-[11px]">Golongan Darah:</span>
                <span class="font-bold text-slate-800">{{ $patient->blood_type ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Nomor Telepon:</span>
                <span class="font-semibold text-slate-800">{{ $patient->phone ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Alamat:</span>
                <span class="font-medium text-slate-800 truncate block">{{ $patient->address ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Kontak Darurat:</span>
                <span class="font-medium text-slate-800 truncate block">
                    {{ $patient->emergency_contact_name ?? '-' }} ({{ $patient->emergency_contact_phone ?? '-' }})
                </span>
            </div>
        </div>
    </div>

    <!-- Timeline Riwayat Kunjungan & RME -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Kronologi Rekam Medis (Encounter History)</h3>
            <span class="text-xs text-slate-500">Diurutkan dari kunjungan terbaru</span>
        </div>

        @forelse ($visits as $visit)
            @php
                $assessment = $visit->assessment_data;
                $vitals = $assessment['vital_signs'] ?? null;
                $doctorExam = $visit->doctor_examination;
                $soap = $doctorExam['soap'] ?? null;
                $diagnoses = $doctorExam['diagnosis'] ?? [];
                $actions = $doctorExam['actions'] ?? [];
                $prescriptions = $doctorExam['prescriptions'] ?? [];
            @endphp
            <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4 hover:border-slate-300 transition-colors">
                <!-- Encounter Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 flex items-center justify-center font-mono font-bold text-sm">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 font-mono text-sm">{{ $visit->visit_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $visit->status === 'completed' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' :
                                    ($visit->status === 'in_progress' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                    {{ $visit->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $visit->visit_date ? $visit->visit_date->translatedFormat('l, j F Y') : '-' }} &bull; 
                                Poli: <strong class="text-slate-800">{{ $visit->poli->name }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 font-mono">
                        Dokter: {{ $doctorExam['doctor_name'] ?? 'dr. Andi Prasetyo' }}
                    </div>
                </div>

                <!-- Tanda Vital Perawat (Jika ada) -->
                @if ($vitals)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase block mb-1.5">Tanda Vital (Pemeriksaan Awal):</span>
                        <div class="flex flex-wrap gap-2 text-xs font-mono">
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                TD: <strong>{{ $vitals['sistolik'] ?? '-' }}/{{ $vitals['diastolik'] ?? '-' }}</strong> mmHg
                            </span>
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                Nadi: <strong>{{ $vitals['nadi'] ?? '-' }}</strong> x/m
                            </span>
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                Suhu: <strong>{{ $vitals['suhu'] ?? '-' }}</strong>°C
                            </span>
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                SpO2: <strong>{{ $vitals['spo2'] ?? '-' }}</strong>%
                            </span>
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                BB: <strong>{{ $vitals['berat_badan'] ?? '-' }}</strong> kg
                            </span>
                            <span class="bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                                Kesadaran: <strong>{{ $vitals['kesadaran'] ?? 'Compos mentis' }}</strong>
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Catatan Medis SOAP -->
                @if ($soap && (!empty($soap['subjective']) || !empty($soap['assessment'])))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-slate-50/60 border border-slate-200 space-y-1">
                            <span class="font-bold text-teal-800 text-[11px] block">S - Subjective:</span>
                            <p class="text-slate-700 leading-relaxed">{{ $soap['subjective'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50/60 border border-slate-200 space-y-1">
                            <span class="font-bold text-teal-800 text-[11px] block">O - Objective:</span>
                            <p class="text-slate-700 leading-relaxed">{{ $soap['objective'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50/60 border border-slate-200 space-y-1">
                            <span class="font-bold text-teal-800 text-[11px] block">A - Assessment:</span>
                            <p class="text-slate-700 leading-relaxed">{{ $soap['assessment'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50/60 border border-slate-200 space-y-1">
                            <span class="font-bold text-teal-800 text-[11px] block">P - Plan:</span>
                            <p class="text-slate-700 leading-relaxed">{{ $soap['plan'] ?? '-' }}</p>
                        </div>
                    </div>
                @elseif (!empty($visit->complaint))
                    <div class="p-3 rounded-xl bg-slate-50 text-xs">
                        <span class="text-[11px] text-slate-400 block font-semibold">Keluhan Tercatat:</span>
                        <p class="text-slate-700 mt-0.5">{{ is_string($visit->complaint) && !json_decode($visit->complaint) ? $visit->complaint : ($assessment['keluhan_utama'] ?? '-') }}</p>
                    </div>
                @endif

                <!-- Diagnosis & Tindakan -->
                @if (!empty($diagnoses) || !empty($actions))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs pt-2">
                        <!-- Diagnosis ICD-10 -->
                        <div>
                            <span class="text-[11px] font-semibold text-slate-500 uppercase block mb-1">Diagnosis ICD-10:</span>
                            @if (!empty($diagnoses))
                                <div class="space-y-1">
                                    @foreach ($diagnoses as $diag)
                                        <div class="flex items-center gap-2 p-2 rounded-lg bg-teal-50/80 border border-teal-100 text-teal-900">
                                            <span class="font-mono font-bold text-xs bg-teal-200/60 px-1.5 py-0.5 rounded">{{ $diag['code'] ?? '-' }}</span>
                                            <span class="font-medium">{{ $diag['name'] ?? '-' }}</span>
                                            <span class="text-[10px] text-teal-700 capitalize ml-auto">({{ $diag['type'] ?? 'primer' }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic">Tidak ada diagnosis spesifik.</span>
                            @endif
                        </div>

                        <!-- Tindakan Medis -->
                        <div>
                            <span class="text-[11px] font-semibold text-slate-500 uppercase block mb-1">Tindakan Medis:</span>
                            @if (!empty($actions))
                                <div class="space-y-1">
                                    @foreach ($actions as $act)
                                        <div class="p-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-800">
                                            <strong>{{ $act['name'] ?? '-' }}</strong>
                                            @if (!empty($act['notes']))
                                                <span class="text-slate-500 block text-[11px] mt-0.5">{{ $act['notes'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic">Tidak ada tindakan khusus.</span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Resep Obat -->
                @if (!empty($prescriptions))
                    <div class="pt-2 border-t border-slate-100 text-xs">
                        <span class="text-[11px] font-semibold text-slate-500 uppercase block mb-1.5">Resep Obat yang Diberikan:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach ($prescriptions as $rx)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <strong class="text-slate-900">{{ $rx['medicine_name'] ?? '-' }}</strong>
                                        <span class="font-mono text-slate-600 font-semibold">{{ $rx['quantity'] ?? '-' }} pcs</span>
                                    </div>
                                    <p class="text-[11px] text-teal-700">
                                        {{ $rx['dose'] ?? '' }} &bull; {{ $rx['frequency'] ?? '' }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 italic">{{ $rx['instruction'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        @empty
            <div class="p-12 text-center rounded-2xl bg-white border border-slate-200 text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <h4 class="font-bold text-slate-700 text-sm">Belum Ada Rekam Medis</h4>
                <p class="text-xs mt-1">Pasien ini belum memiliki catatan riwayat kunjungan medis sebelumnya di klinik.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

