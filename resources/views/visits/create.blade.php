@extends('layouts.app')

@section('title', 'Daftarkan Kunjungan Baru')
@section('page-title', 'Buat Kunjungan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('visits.index') }}" class="text-xs text-slate-400 hover:text-teal-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Daftar Kunjungan</span>
                </a>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">Registrasi Kunjungan Pasien</h2>
            <p class="text-sm text-slate-500">Pilih pasien dan unit poli tujuan untuk penerbitan tiket nomor antrean.</p>
        </div>
    </div>

    <!-- Informasi Penomoran Antrean Otomatis -->
    <div class="p-4 rounded-2xl bg-teal-50/70 border border-teal-200/60 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h4 class="text-xs font-bold text-teal-900">Alur Penerbitan Antrean Otomatis</h4>
            <p class="text-xs text-teal-800/80 mt-0.5">
                Nomor antrean dan nomor kunjungan akan langsung di-generate otomatis per unit poli tujuan dengan aman tanpa duplikasi.
            </p>
        </div>
    </div>

    <!-- Form Registrasi Kunjungan -->
    <form method="POST" action="{{ route('visits.store') }}" class="p-6 md:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-6">
        @csrf

        <!-- Bagian Pasien -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                    1. Data Pasien
                </h3>
                <a href="{{ route('patients.create') }}" class="text-xs font-semibold text-teal-600 hover:underline">
                    + Pasien Baru Belum Terdaftar?
                </a>
            </div>

            @if ($selectedPatient)
                <!-- Pasien Sudah Dipilih Sebelumnya (misal dari halaman detail pasien) -->
                <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($selectedPatient->name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">{{ $selectedPatient->name }}</span>
                            <span class="text-xs text-slate-500 font-mono">
                                No. RM: {{ $selectedPatient->medical_record_number }}
                                @if($selectedPatient->nik) • NIK: {{ $selectedPatient->nik }} @endif
                                • {{ $selectedPatient->gender_label }} ({{ $selectedPatient->age ?? '-' }} th)
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('visits.create') }}" class="text-xs text-slate-400 hover:text-slate-600 underline">
                        Ganti Pasien
                    </a>
                </div>
            @else
                <!-- Dropdown Pemilihan Pasien -->
                <div>
                    <label for="patient_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Pilih Pasien Terdaftar <span class="text-rose-500">*</span>
                    </label>
                    <select id="patient_id" 
                            name="patient_id" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('patient_id') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        <option value="">-- Pilih Pasien (Nama / No. RM) --</option>
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->medical_record_number }} - {{ $p->name }} {{ $p->nik ? '(NIK: ' . $p->nik . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-[11px] text-slate-400">Pilih pasien dari database yang telah memiliki nomor rekam medis.</p>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Bagian Pelayanan & Poli -->
        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                2. Tujuan Pelayanan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Poli Tujuan -->
                <div>
                    <label for="poli_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Poli Tujuan Pelayanan <span class="text-rose-500">*</span>
                    </label>
                    <select id="poli_id" 
                            name="poli_id" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('poli_id') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->name }} (Prefix: {{ $poli->queue_prefix }})
                            </option>
                        @endforeach
                    </select>
                    @error('poli_id')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Kunjungan -->
                <div>
                    <label for="visit_date" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal Kunjungan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           id="visit_date" 
                           name="visit_date" 
                           value="{{ old('visit_date', date('Y-m-d')) }}" 
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('visit_date') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    @error('visit_date')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Keluhan Awal Pasien -->
            <div>
                <label for="complaint" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Keluhan Utama / Alasan Kunjungan
                </label>
                <textarea id="complaint" 
                          name="complaint" 
                          rows="3" 
                          placeholder="Tuliskan keluhan atau alasan kedatangan pasien..."
                          class="w-full px-3.5 py-2.5 rounded-xl border @error('complaint') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">{{ old('complaint') }}</textarea>
                @error('complaint')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('visits.index') }}" 
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm transition-all shadow-sm shadow-teal-200 hover:shadow-md">
                Daftarkan & Terbitkan Antrean
            </button>
        </div>
    </form>
</div>
@endsection
