@extends('layouts.app')

@section('title', 'Buat Kunjungan Baru')
@section('page-title', 'Kunjungan')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.index') }}" class="hover:underline klinika-primary-text">Data pasien</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.show', $selectedPatient) }}" class="hover:underline klinika-primary-text">{{ $selectedPatient->medical_record_number }}</a>
    <span class="mx-1">/</span>
    <span>Buat kunjungan</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Buat kunjungan baru</h2>
            <p class="text-sm klinika-muted mt-0.5">Langkah 1 dari 2 &bull; Pilih tujuan pelayanan dan penjamin.</p>
        </div>
        <a href="{{ route('patients.show', $selectedPatient) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold border klinika-border text-slate-700 bg-white hover:bg-gray-50 transition-colors shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Detail pasien
        </a>
    </div>

    {{-- Patient Info Card --}}
    <div class="klinika-card p-5 space-y-2">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">
                {{ $selectedPatient->name }} &bull; {{ $selectedPatient->medical_record_number }}
            </h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold self-start sm:self-auto"
                  style="background:#E6F7F7;color:#087F83">
                Pasien lama &bull; encounter baru
            </span>
        </div>
        <p class="text-xs klinika-muted">
            {{ $selectedPatient->gender_label }} &bull; {{ $selectedPatient->birth_date?->translatedFormat('j F Y') }} ({{ $selectedPatient->age }} tahun) &bull; NIK {{ $selectedPatient->nik ?? '3273015405880002' }} &bull; {{ $selectedPatient->phone ?? '0812-7000-0128' }}
        </p>
    </div>

    <form method="POST" action="{{ route('visits.confirm') }}" id="form-create-visit" class="space-y-5">
        @csrf

        <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- LEFT: Form Card --}}
            <div class="w-full lg:w-[65%] space-y-4">
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Tujuan kunjungan</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tanggal kunjungan --}}
                        <div>
                            <label for="visit_date" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Tanggal kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="visit_date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>

                        {{-- Jenis kunjungan --}}
                        <div>
                            <label for="visit_type" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Jenis kunjungan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="visit_type" name="visit_type" required
                                        class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                        style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)">
                                    <option value="Rawat jalan / kunjungan ulang" selected>Rawat jalan &bull; kunjungan ulang</option>
                                    <option value="Rawat jalan / pasien baru">Rawat jalan &bull; pasien baru</option>
                                    <option value="Gawat darurat">Gawat darurat</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Poli tujuan --}}
                        <div>
                            <label for="poli_id" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Poli tujuan <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="poli_id" name="poli_id" required
                                        class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                        style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)">
                                    @foreach ($polis as $poli)
                                        <option value="{{ $poli->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $poli->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Dokter --}}
                        <div>
                            <label for="doctor_name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Dokter <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="doctor_name" name="doctor_name" required
                                        class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                        style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)">
                                    <option value="dr. Andi Prasetyo" selected>dr. Andi Prasetyo</option>
                                    <option value="drg. Nabila Sari">drg. Nabila Sari</option>
                                    <option value="dr. Laila Nuraini">dr. Laila Nuraini</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <p class="text-[11px] klinika-muted mt-1">08.00–12.00 &bull; Tersedia 8 slot</p>
                        </div>
                    </div>

                    {{-- Penjamin --}}
                    <div>
                        <label for="insurance" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Penjamin <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="insurance" name="insurance" required
                                    class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                    style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)">
                                <option value="Umum / pribadi" selected>Umum / pribadi</option>
                                <option value="BPJS Kesehatan">BPJS Kesehatan</option>
                                <option value="Asuransi Swasta">Asuransi Swasta</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-[11px] klinika-muted mt-1">BPJS/asuransi memerlukan nomor peserta dan verifikasi kepesertaan.</p>
                    </div>

                    {{-- Keluhan singkat --}}
                    <div>
                        <label for="complaint" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Keluhan singkat untuk pendaftaran
                        </label>
                        <textarea id="complaint" name="complaint" rows="3"
                                  class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors resize-none"
                                  style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                                  placeholder="Batuk dan tenggorokan tidak nyaman sejak dua hari.">{{ old('complaint', 'Batuk dan tenggorokan tidak nyaman sejak dua hari.') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Ringkasan Card --}}
            <div class="w-full lg:w-[35%] space-y-4">
                <div class="klinika-card p-5 space-y-3">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ringkasan pendaftaran</h3>

                    <div class="space-y-1.5 text-xs">
                        <p><span class="klinika-muted">Pasien:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $selectedPatient->name }}</span></p>
                        <p><span class="klinika-muted">RM:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $selectedPatient->medical_record_number }}</span></p>
                        <p><span class="klinika-muted">Kunjungan:</span> <span class="font-medium" style="color:var(--klinika-text)">belum dibuat</span></p>
                        <p><span class="klinika-muted">Poli:</span> <span class="font-medium" id="summary-poli" style="color:var(--klinika-text)">Umum</span></p>
                        <p><span class="klinika-muted">Dokter:</span> <span class="font-medium" id="summary-doctor" style="color:var(--klinika-text)">dr. Andi Prasetyo</span></p>
                        <p><span class="klinika-muted">Jenis:</span> <span class="font-medium" id="summary-type" style="color:var(--klinika-text)">kunjungan ulang</span></p>
                        <p><span class="klinika-muted">Penjamin:</span> <span class="font-medium" id="summary-insurance" style="color:var(--klinika-text)">Umum / pribadi</span></p>
                    </div>

                    <p class="text-[11px] klinika-muted pt-3 border-t klinika-border leading-relaxed">
                        ID kunjungan dan nomor antrean diterbitkan setelah konfirmasi berhasil. Jadwal dan kuota dicek ulang di server.
                    </p>
                </div>
            </div>

        </div>

        {{-- Cyan Info Banner --}}
        <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
            </svg>
            <div>
                <p class="text-xs font-bold" style="color:#087F83">Tidak ada kunjungan aktif untuk pasien ini</p>
                <p class="text-xs mt-0.5" style="color:#087F83">
                    Riwayat terakhir: KJ-20260912-008, selesai. Encounter lama tidak diubah.
                </p>
            </div>
        </div>

        {{-- Bottom Action Buttons --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                <span>Tinjau &amp; konfirmasi</span>
                <span aria-hidden="true">&rarr;</span>
            </button>
            <a href="{{ route('patients.show', $selectedPatient) }}"
               class="px-5 py-2.5 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                Batal
            </a>
        </div>

    </form>

    {{-- Footer --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const poliSelect = document.getElementById('poli_id');
    const doctorSelect = document.getElementById('doctor_name');
    const typeSelect = document.getElementById('visit_type');
    const insuranceSelect = document.getElementById('insurance');

    const summaryPoli = document.getElementById('summary-poli');
    const summaryDoctor = document.getElementById('summary-doctor');
    const summaryType = document.getElementById('summary-type');
    const summaryInsurance = document.getElementById('summary-insurance');

    function updateSummary() {
        if (poliSelect && summaryPoli) {
            summaryPoli.textContent = poliSelect.options[poliSelect.selectedIndex]?.text.replace('Poli ', '') || 'Umum';
        }
        if (doctorSelect && summaryDoctor) {
            summaryDoctor.textContent = doctorSelect.value || 'dr. Andi Prasetyo';
        }
        if (typeSelect && summaryType) {
            summaryType.textContent = typeSelect.value.toLowerCase().includes('ulang') ? 'kunjungan ulang' : typeSelect.value;
        }
        if (insuranceSelect && summaryInsurance) {
            summaryInsurance.textContent = insuranceSelect.value || 'Umum / pribadi';
        }
    }

    poliSelect?.addEventListener('change', updateSummary);
    doctorSelect?.addEventListener('change', updateSummary);
    typeSelect?.addEventListener('change', updateSummary);
    insuranceSelect?.addEventListener('change', updateSummary);
    updateSummary();
});
</script>
@endpush
