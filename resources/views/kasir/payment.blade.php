@extends('layouts.app')

@section('title', 'Pembayaran - ' . $paymentData['invoice_number'])
@section('page-title', 'Pembayaran')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.show', $paymentData['invoice_number']) }}" class="hover:underline klinika-primary-text">{{ $paymentData['invoice_number'] }}</a>
    <span class="mx-1">/</span>
    <span>Pembayaran</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Kembali --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pembayaran</h2>
            <p class="text-sm klinika-muted mt-0.5">
                {{ auth()->user()?->name ?? 'Budi Hartono' }} &bull; Shift pagi &bull; Transaksi belum dicatat.
            </p>
        </div>
        <div>
            <a href="{{ route('kasir.show', $paymentData['invoice_number']) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                &larr; Detail tagihan
            </a>
        </div>
    </div>

    {{-- Kartu Data Pasien & Status Kunjungan --}}
    <div class="klinika-card p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="text-lg font-bold" style="color:var(--klinika-text)">{{ $paymentData['patient']['name'] }}</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:#E6F7F7;color:#087F83">
                {{ $paymentData['visit']['status_badge'] }}
            </span>
        </div>
        <p class="text-xs klinika-muted mt-1">
            {{ $paymentData['patient']['mrn'] }} &bull; {{ $paymentData['patient']['gender'] }} &bull; {{ $paymentData['patient']['birth_date'] }} ({{ $paymentData['patient']['age'] }} tahun) &bull; NIK {{ $paymentData['patient']['nik'] }}
        </p>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-2 pt-2 border-t" style="border-color:var(--klinika-border)">
            <p class="text-xs klinika-muted">
                {{ $paymentData['visit']['visit_number'] }} &bull; {{ $paymentData['visit']['date_time'] }} | {{ $paymentData['visit']['poli'] }} | {{ $paymentData['visit']['doctor'] }} | {{ $paymentData['visit']['insurance'] }} | {{ $paymentData['visit']['queue_code'] }}
            </p>
            <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline text-xs font-medium">
                Lihat riwayat &rarr;
            </a>
        </div>
    </div>

    {{-- Formulir Pembayaran --}}
    <form method="GET" action="{{ route('kasir.confirm', $paymentData['invoice_number']) }}" class="space-y-5">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Kolom Kiri: Input Metode & Nominal --}}
            <div class="lg:col-span-2 klinika-card p-5 space-y-4">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Metode dan nominal pembayaran</h3>

                <div>
                    <label class="block text-xs font-medium klinika-muted mb-1">Invoice</label>
                    <input type="text"
                           value="{{ $paymentData['invoice_number'] }} • {{ $paymentData['total_formatted'] }}"
                           disabled
                           class="w-full px-3 py-2 text-xs rounded-lg border bg-gray-100 text-gray-700 cursor-not-allowed"
                           style="border-color:var(--klinika-border)">
                </div>

                <div>
                    <label for="payment_method" class="block text-xs font-medium mb-1" style="color:var(--klinika-text)">
                        Metode pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method"
                            id="payment_method"
                            required
                            class="w-full px-3 py-2 text-xs rounded-lg border bg-white focus:outline-none focus:ring-1 focus:ring-[#087F83]"
                            style="border-color:var(--klinika-border)">
                        <option value="Tunai" selected>Tunai</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                    <p class="text-[11px] klinika-muted mt-1">Pilihan lain: QRIS atau transfer; wajib nomor referensi valid.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="amount_received" class="block text-xs font-medium mb-1" style="color:var(--klinika-text)">
                            Nominal diterima (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="amount_received"
                               id="amount_received"
                               value="150.000"
                               required
                               class="w-full px-3 py-2 text-xs rounded-lg border bg-white focus:outline-none focus:ring-1 focus:ring-[#087F83]"
                               style="border-color:var(--klinika-border)">
                    </div>
                    <div>
                        <label for="change_amount" class="block text-xs font-medium mb-1" style="color:var(--klinika-text)">
                            Kembalian (Rp)
                        </label>
                        <input type="text"
                               id="change_amount"
                               value="5.000"
                               disabled
                               class="w-full px-3 py-2 text-xs rounded-lg border bg-gray-100 text-gray-700 cursor-not-allowed"
                               style="border-color:var(--klinika-border)">
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-medium mb-1" style="color:var(--klinika-text)">
                        Catatan pembayaran
                    </label>
                    <textarea name="notes"
                              id="notes"
                              rows="3"
                              class="w-full px-3 py-2 text-xs rounded-lg border bg-white focus:outline-none focus:ring-1 focus:ring-[#087F83]"
                              style="border-color:var(--klinika-border)">Pembayaran tunai diterima dari pasien.</textarea>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan Transaksi --}}
            <div class="lg:col-span-1 klinika-card p-5 space-y-4 flex flex-col justify-between">
                <div>
                    <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Ringkasan transaksi</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="klinika-muted">Layanan:</span>
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $paymentData['summary']['services'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="klinika-muted">Obat:</span>
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $paymentData['summary']['medicine'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="klinika-muted">Total:</span>
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $paymentData['summary']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="klinika-muted">Dibayar:</span>
                            <span class="font-medium" style="color:var(--klinika-text)">{{ $paymentData['summary']['paid'] }}</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="inline-block px-2.5 py-1 rounded text-xs font-semibold" style="background:#FFF5E4;color:#96611C">
                            Invoice: {{ $paymentData['summary']['status'] }}
                        </span>
                    </div>

                    <p class="text-xs klinika-muted leading-relaxed mt-4 pt-4 border-t" style="border-color:var(--klinika-border)">
                        Nominal tunai harus &ge; total. Kembalian dihitung server. Nontunai memerlukan verifikasi penerimaan, bukan hanya bukti unggah.
                    </p>
                </div>
            </div>
        </div>

        {{-- Banner Cyan: Tinjau sebelum konfirmasi --}}
        <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
            </svg>
            <div>
                <p class="text-xs font-bold" style="color:#087F83">Tinjau sebelum konfirmasi</p>
                <p class="text-xs mt-0.5" style="color:#087F83">
                    Aksi berikut membuka ringkasan. Invoice belum menjadi lunas sampai transaksi berhasil dicatat oleh server.
                </p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Tinjau pembayaran &rarr;
            </button>
            <a href="{{ route('kasir.show', $paymentData['invoice_number']) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                Batal
            </a>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const totalAmount = {{ $paymentData['total'] }};
    const receivedInput = document.getElementById('amount_received');
    const changeInput = document.getElementById('change_amount');

    function updateChange() {
        let raw = receivedInput.value.replace(/[^0-9]/g, '');
        let val = parseInt(raw, 10) || 0;
        let change = Math.max(0, val - totalAmount);
        changeInput.value = change.toLocaleString('id-ID');
    }

    if (receivedInput && changeInput) {
        receivedInput.addEventListener('input', updateChange);
    }
});
</script>
@endsection
