@extends('layouts.app')

@section('title', 'Detail Pasien - ' . $patient->name)
@section('page-title', 'Detail Pasien')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('patients.index') }}" class="text-xs text-slate-500 hover:text-teal-600 transition-colors flex items-center gap-1 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Pasien</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('patients.edit', $patient) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition-colors shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Pasien</span>
            </a>

            <button type="button" 
                    onclick="openDeleteModal('{{ $patient->id }}', '{{ addslashes($patient->name) }}', '{{ $patient->medical_record_number }}')"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Hapus</span>
            </button>
        </div>
    </div>

    <!-- Kartu Profil Pasien -->
    <div class="p-6 md:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm relative overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl {{ $patient->gender === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }} flex items-center justify-center font-bold text-2xl shrink-0 shadow-inner">
                    {{ strtoupper(substr($patient->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $patient->name }}</h2>
                        @if ($patient->gender === 'L')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                Laki-laki
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-50 text-pink-700 border border-pink-200">
                                Perempuan
                            </span>
                        @endif
                        @if ($patient->blood_type)
                            <span class="px-2 py-0.5 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Gol. {{ $patient->blood_type }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                        <span class="flex items-center gap-1 font-mono font-bold text-teal-700 bg-teal-50 px-2 py-1 rounded-md">
                            No. RM: {{ $patient->medical_record_number }}
                        </span>
                        <span>•</span>
                        <span>NIK: <strong class="font-mono text-slate-700">{{ $patient->nik ?? '-' }}</strong></span>
                        @if ($patient->age !== null)
                            <span>•</span>
                            <span>Usia: <strong class="text-slate-800">{{ $patient->age }} Tahun</strong></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
            <!-- Informasi Pribadi -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Identitas Pribadi</h4>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Nomor Rekam Medis</span>
                        <span class="font-mono font-bold text-teal-700">{{ $patient->medical_record_number }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Nomor Induk Kependudukan (NIK)</span>
                        <span class="font-mono font-medium text-slate-900">{{ $patient->nik ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Tempat Lahir</span>
                        <span class="font-medium text-slate-900">{{ $patient->birth_place }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Tanggal Lahir</span>
                        <span class="font-medium text-slate-900">
                            {{ $patient->birth_date ? $patient->birth_date->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Jenis Kelamin</span>
                        <span class="font-medium text-slate-900">{{ $patient->gender_label }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Golongan Darah</span>
                        <span class="font-medium text-slate-900">{{ $patient->blood_type ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Kontak & Catatan Sistem -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kontak & Integrasi</h4>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">No. Telepon / WhatsApp</span>
                        <span class="font-medium text-slate-900">{{ $patient->phone ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">IHS SATUSEHAT</span>
                        <span class="font-mono font-medium text-slate-900">{{ $patient->satusehat_ihs_number ?? '-' }}</span>
                    </div>

                    <div class="py-1 border-b border-slate-50">
                        <span class="text-slate-500 block mb-1">Alamat Domisili</span>
                        <span class="font-medium text-slate-900 block leading-relaxed">{{ $patient->address ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Didaftarkan Oleh</span>
                        <span class="font-medium text-slate-700">{{ $patient->creator?->name ?? 'Petugas Pendaftaran' }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Tanggal Pendaftaran</span>
                        <span class="text-slate-700 text-xs">{{ $patient->created_at ? $patient->created_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                    </div>

                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Terakhir Diperbarui</span>
                        <span class="text-slate-700 text-xs">{{ $patient->updated_at ? $patient->updated_at->translatedFormat('d M Y, H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Hapus Pasien -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 space-y-4">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Pasien</h3>
            <p class="text-xs text-slate-500 mt-1">
                Apakah Anda yakin ingin menghapus data pasien:
            </p>
            <p class="text-sm font-semibold text-slate-800 mt-2 p-2 rounded-lg bg-slate-50" id="deletePatientName">
                -
            </p>
            <p class="text-[11px] text-slate-400 mt-2">
                Data akan dihapus secara soft-delete dari sistem.
            </p>
        </div>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" 
                        onclick="closeDeleteModal()" 
                        class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium transition-colors shadow-sm shadow-rose-200">
                    Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(patientId, patientName, mrn) {
        const form = document.getElementById('deleteForm');
        form.action = `/patients/${patientId}`;
        document.getElementById('deletePatientName').textContent = `${patientName} (${mrn})`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
