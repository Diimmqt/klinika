@extends('layouts.app')

@section('title', 'Konfirmasi Kunjungan')
@section('page-title', 'Kunjungan')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.show', $data['patient']) }}" class="hover:underline klinika-primary-text">{{ $data['patient']->medical_record_number }}</a>
    <span class="mx-1">/</span>
    <span>Kunjungan baru</span>
    <span class="mx-1">/</span>
    <span>Konfirmasi</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Konfirmasi kunjungan</h2>
        <p class="text-sm klinika-muted mt-0.5">Langkah 2 dari 2 &bull; Pastikan pasien dan tujuan pelayanan sudah benar.</p>
    </div>

    {{-- Cyan Info Banner --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Anda akan membuat encounter baru untuk {{ $data['patient']->name }}</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Belum ada pelayanan atau nomor antrean yang dibuat. Klik Daftarkan kunjungan untuk melanjutkan.
            </p>
        </div>
    </div>

    {{-- 2 Cards: Identitas & Rencana pelayanan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Left: Identitas yang dikonfirmasi --}}
        <div class="klinika-card p-6 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Identitas yang dikonfirmasi</h3>
                <div class="space-y-1 text-xs">
                    <p class="font-bold text-sm" style="color:var(--klinika-text)">{{ $data['patient']->name }}</p>
                    <p class="klinika-muted">{{ $data['patient']->medical_record_number }}</p>
                    <p class="klinika-muted">NIK {{ $data['patient']->nik ?? '3273015405880002' }}</p>
                    <p class="klinika-muted">
                        {{ $data['patient']->gender_label }} &bull; {{ $data['patient']->birth_date?->translatedFormat('j F Y') }} ({{ $data['patient']->age }} tahun)
                    </p>
                    <p class="klinika-muted">Telepon {{ $data['patient']->phone ?? '0812-7000-0128' }}</p>
                </div>
            </div>

            <p class="text-[11px] klinika-muted pt-3 border-t klinika-border leading-relaxed">
                Pasien lama &bull; Riwayat terakhir 12 September 2026. Tidak ada encounter aktif sebelum pendaftaran ini.
            </p>
        </div>

        {{-- Right: Rencana pelayanan --}}
        <div class="klinika-card p-6 space-y-3 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Rencana pelayanan</h3>
                <div class="space-y-1.5 text-xs">
                    <p><span class="klinika-muted">Tanggal:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ \Carbon\Carbon::parse($data['visit_date'])->translatedFormat('j F Y') }}</span></p>
                    <p><span class="klinika-muted">Poli:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $data['poli_name'] }} &bull; Ruang 01</span></p>
                    <p><span class="klinika-muted">Dokter:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $data['doctor_name'] }}</span></p>
                    <p><span class="klinika-muted">Jadwal:</span> <span class="font-medium" style="color:var(--klinika-text)">08.00–12.00</span></p>
                    <p><span class="klinika-muted">Jenis:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $data['visit_type'] }}</span></p>
                    <p><span class="klinika-muted">Penjamin:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $data['insurance'] }}</span></p>
                    <p><span class="klinika-muted">Keluhan:</span> <span class="font-medium" style="color:var(--klinika-text)">{{ $data['complaint'] }}</span></p>
                </div>
            </div>

            <div class="pt-3 border-t klinika-border">
                <a href="{{ route('patients.visits.create', $data['patient']) }}"
                   class="inline-flex items-center gap-1 text-xs font-semibold klinika-primary-text hover:underline">
                    Ubah pilihan pelayanan &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- Card: Hasil setelah pendaftaran --}}
    <div class="klinika-card p-5 space-y-2">
        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Hasil setelah pendaftaran</h3>
        <p class="text-xs klinika-muted leading-relaxed">
            Nomor kunjungan dan antrean diterbitkan secara atomik. Status kunjungan menjadi Terdaftar dan status antrean Menunggu awal. Anda diarahkan ke Kunjungan aktif untuk mencetak nomor antrean.
        </p>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('visits.store') }}" id="form-confirm-visit" class="space-y-4">
        @csrf

        <input type="hidden" name="patient_id" value="{{ $data['patient']->id }}">
        <input type="hidden" name="poli_id" value="{{ $data['poli_id'] }}">
        <input type="hidden" name="visit_date" value="{{ $data['visit_date'] }}">
        <input type="hidden" name="complaint" value="{{ $data['complaint'] }}">
        <input type="hidden" name="redirect_to" value="index">

        {{-- Checkbox --}}
        <div class="flex items-center gap-2.5">
            <input type="checkbox" id="confirmed" name="confirmed" required checked
                   class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-500 cursor-pointer">
            <label for="confirmed" class="font-semibold text-xs cursor-pointer select-none" style="color:var(--klinika-text)">
                Identitas pasien dan pilihan pelayanan sudah dikonfirmasi.
            </label>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3 pt-1">
            <button type="submit" id="btn-submit-visit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Daftarkan kunjungan
            </button>
            <a href="{{ route('patients.visits.create', $data['patient']) }}"
               class="inline-flex items-center gap-1 px-4 py-2.5 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                <span aria-hidden="true">&larr;</span> Ubah pilihan
            </a>
        </div>

        <p class="text-[11px] klinika-muted">
            Jika kuota berubah atau pendaftaran gagal, input dipertahankan dan nomor antrean tidak diterbitkan. Tombol hanya dapat diproses satu kali.
        </p>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-confirm-visit');
    const submitBtn = document.getElementById('btn-submit-visit');
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
        });
    }
});
</script>
@endpush
