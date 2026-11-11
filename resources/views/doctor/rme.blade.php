@extends('layouts.app')

@section('title', 'Rekam Medis Elektronik - ' . $patient->name)
@section('page-title', 'Rekam medis elektronik')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dokter.index') }}" class="hover:underline klinika-primary-text">Pelayanan medis</a>
    <span class="mx-1">/</span>
    <a href="{{ route('dokter.queue') }}" class="hover:underline klinika-primary-text">Antrean</a>
    <span class="mx-1">/</span>
    <span>RME Pasien</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Rekam medis elektronik</h2>
            <p class="text-sm klinika-muted mt-0.5">{{ $patient->name }}</p>
        </div>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border klinika-border text-sm font-semibold transition-colors hover:bg-slate-50 shrink-0" style="color:var(--klinika-text)">
            &larr; Kembali
        </a>
    </div>

    {{-- Profil Pasien --}}
    <div class="klinika-card p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-2.5 py-1 rounded-md klinika-primary text-white font-mono font-bold text-xs">
                        RM-{{ $patient->medical_record_number }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100" style="color:var(--klinika-text)">
                        {{ $visits->count() }} total kunjungan
                    </span>
                </div>
                <h2 class="text-xl font-bold mt-2" style="color:var(--klinika-text)">{{ $patient->name }}</h2>
                <p class="text-xs klinika-muted font-mono mt-0.5">
                    NIK: {{ $patient->nik ?? '-' }} &bull;
                    {{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }} &bull;
                    {{ $patient->birth_place ?? '-' }}, {{ $patient->birth_date ? $patient->birth_date->translatedFormat('d F Y') : '-' }}
                    ({{ $patient->age ?? '-' }} tahun)
                </p>
            </div>
            <div class="text-xs text-right">
                <span class="klinika-muted block text-[11px]">Terdaftar pertama:</span>
                <span class="font-medium font-mono" style="color:var(--klinika-text)">{{ $patient->created_at ? $patient->created_at->translatedFormat('d M Y') : '-' }}</span>
            </div>
        </div>

        <div class="pt-4 klinika-border border-t grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="klinika-muted block text-[11px]">Golongan darah:</span>
                <span class="font-bold" style="color:var(--klinika-text)">{{ $patient->blood_type ?? '-' }}</span>
            </div>
            <div>
                <span class="klinika-muted block text-[11px]">Nomor telepon:</span>
                <span class="font-semibold" style="color:var(--klinika-text)">{{ $patient->phone ?? '-' }}</span>
            </div>
            <div>
                <span class="klinika-muted block text-[11px]">Alamat:</span>
                <span class="font-medium truncate block" style="color:var(--klinika-text)">{{ $patient->address ?? '-' }}</span>
            </div>
            <div>
                <span class="klinika-muted block text-[11px]">Kontak darurat:</span>
                <span class="font-medium truncate block" style="color:var(--klinika-text)">
                    {{ $patient->emergency_contact_name ?? '-' }} ({{ $patient->emergency_contact_phone ?? '-' }})
                </span>
            </div>
        </div>
    </div>

    {{-- Timeline Riwayat Kunjungan --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold" style="color:var(--klinika-text)">Kronologi rekam medis (encounter history)</h3>
            <span class="text-xs klinika-muted">Diurutkan dari kunjungan terbaru</span>
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
            <div class="klinika-card p-6 space-y-4">
                {{-- Encounter Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 klinika-border border-b">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg klinika-primary text-white flex items-center justify-center font-mono font-bold text-sm">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold font-mono text-sm" style="color:var(--klinika-text)">{{ $visit->visit_number }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ 
                                    $visit->status === 'completed' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' :
                                    ($visit->status === 'in_progress' ? 'bg-purple-50 text-purple-800 border border-purple-200' :
                                    'bg-amber-50 text-amber-800 border border-amber-200') }}">
                                    {{ $visit->status_label }}
                                </span>
                            </div>
                            <p class="text-xs klinika-muted mt-0.5">
                                {{ $visit->visit_date ? $visit->visit_date->translatedFormat('l, j F Y') : '-' }} &bull;
                                Poli: <strong style="color:var(--klinika-text)">{{ $visit->poli->name }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="text-xs klinika-muted font-mono">Dokter: {{ $doctorExam['doctor_name'] ?? 'dr. Andi Prasetyo' }}</div>
                </div>

                {{-- Tanda Vital --}}
                @if ($vitals)
                    <div class="p-3 rounded-lg klinika-border border" style="background:var(--klinika-bg)">
                        <span class="text-[11px] font-semibold klinika-muted uppercase block mb-1.5">Tanda vital (pemeriksaan awal):</span>
                        <div class="flex flex-wrap gap-2 text-xs font-mono">
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">TD: <strong>{{ $vitals['sistolik'] ?? '-' }}/{{ $vitals['diastolik'] ?? '-' }}</strong> mmHg</span>
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">Nadi: <strong>{{ $vitals['nadi'] ?? '-' }}</strong> x/m</span>
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">Suhu: <strong>{{ $vitals['suhu'] ?? '-' }}</strong>°C</span>
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">SpO2: <strong>{{ $vitals['spo2'] ?? '-' }}</strong>%</span>
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">BB: <strong>{{ $vitals['berat_badan'] ?? '-' }}</strong> kg</span>
                            <span class="bg-white px-2.5 py-1 rounded-lg klinika-border border">Kesadaran: <strong>{{ $vitals['kesadaran'] ?? 'Compos mentis' }}</strong></span>
                        </div>
                    </div>
                @endif

                {{-- SOAP --}}
                @if ($soap && (!empty($soap['subjective']) || !empty($soap['assessment'])))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-lg klinika-border border space-y-1" style="background:var(--klinika-bg)">
                            <span class="font-bold klinika-primary-text text-[11px] block">S - Subjective:</span>
                            <p style="color:var(--klinika-text)">{{ $soap['subjective'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-lg klinika-border border space-y-1" style="background:var(--klinika-bg)">
                            <span class="font-bold klinika-primary-text text-[11px] block">O - Objective:</span>
                            <p style="color:var(--klinika-text)">{{ $soap['objective'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-lg klinika-border border space-y-1" style="background:var(--klinika-bg)">
                            <span class="font-bold klinika-primary-text text-[11px] block">A - Assessment:</span>
                            <p style="color:var(--klinika-text)">{{ $soap['assessment'] ?? '-' }}</p>
                        </div>
                        <div class="p-3.5 rounded-lg klinika-border border space-y-1" style="background:var(--klinika-bg)">
                            <span class="font-bold klinika-primary-text text-[11px] block">P - Plan:</span>
                            <p style="color:var(--klinika-text)">{{ $soap['plan'] ?? '-' }}</p>
                        </div>
                    </div>
                @elseif (!empty($visit->complaint))
                    <div class="p-3 rounded-lg klinika-border border text-xs" style="background:var(--klinika-bg)">
                        <span class="text-[11px] klinika-muted block font-semibold">Keluhan tercatat:</span>
                        <p class="mt-0.5" style="color:var(--klinika-text)">{{ is_string($visit->complaint) && !json_decode($visit->complaint) ? $visit->complaint : ($assessment['keluhan_utama'] ?? '-') }}</p>
                    </div>
                @endif

                {{-- Diagnosis & Tindakan --}}
                @if (!empty($diagnoses) || !empty($actions))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs pt-2">
                        <div>
                            <span class="text-[11px] font-semibold klinika-muted uppercase block mb-1">Diagnosis ICD-10:</span>
                            @if (!empty($diagnoses))
                                <div class="space-y-1">
                                    @foreach ($diagnoses as $diag)
                                        <div class="flex items-center gap-2 p-2 rounded-lg" style="background:var(--klinika-primary-bg);border:1px solid var(--klinika-primary-light);color:var(--klinika-text)">
                                            <span class="font-mono font-bold text-xs px-1.5 py-0.5 rounded klinika-primary text-white">{{ $diag['code'] ?? '-' }}</span>
                                            <span class="font-medium">{{ $diag['name'] ?? '-' }}</span>
                                            <span class="text-[10px] klinika-muted capitalize ml-auto">({{ $diag['type'] ?? 'primer' }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="klinika-muted italic">Tidak ada diagnosis spesifik.</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold klinika-muted uppercase block mb-1">Tindakan medis:</span>
                            @if (!empty($actions))
                                <div class="space-y-1">
                                    @foreach ($actions as $act)
                                        <div class="p-2 rounded-lg klinika-border border" style="background:var(--klinika-bg);color:var(--klinika-text)">
                                            <strong>{{ $act['name'] ?? '-' }}</strong>
                                            @if (!empty($act['notes']))
                                                <span class="klinika-muted block text-[11px] mt-0.5">{{ $act['notes'] }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="klinika-muted italic">Tidak ada tindakan khusus.</span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Resep Obat --}}
                @if (!empty($prescriptions))
                    <div class="pt-2 klinika-border border-t text-xs">
                        <span class="text-[11px] font-semibold klinika-muted uppercase block mb-1.5">Resep obat yang diberikan:</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach ($prescriptions as $rx)
                                <div class="p-2.5 rounded-lg klinika-border border space-y-0.5" style="background:var(--klinika-bg)">
                                    <div class="flex items-center justify-between">
                                        <strong style="color:var(--klinika-text)">{{ $rx['medicine_name'] ?? '-' }}</strong>
                                        <span class="font-mono klinika-muted font-semibold">{{ $rx['quantity'] ?? '-' }} pcs</span>
                                    </div>
                                    <p class="text-[11px] klinika-primary-text">{{ $rx['dose'] ?? '' }} &bull; {{ $rx['frequency'] ?? '' }}</p>
                                    <p class="text-[10px] klinika-muted italic">{{ $rx['instruction'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="klinika-card p-12 text-center">
                <h4 class="font-bold text-sm" style="color:var(--klinika-text)">Belum ada rekam medis</h4>
                <p class="text-xs klinika-muted mt-1">Pasien ini belum memiliki catatan riwayat kunjungan medis sebelumnya di klinik.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

