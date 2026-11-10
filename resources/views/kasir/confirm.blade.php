@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran - ' . $confirmData['invoice_number'])
@section('page-title', 'Pembayaran')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.show', $confirmData['invoice_number']) }}" class="hover:underline klinika-primary-text">{{ $confirmData['invoice_number'] }}</a>
    <span class="mx-1">/</span>
    <span>Konfirmasi</span>
@endsection

@section('content')
<div class="relative">

    {{-- Backdrop Background Page --}}
    <div class="space-y-5 opacity-40 pointer-events-none select-none">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pembayaran</h2>
                <p class="text-sm klinika-muted mt-0.5">
                    {{ $confirmData['patient']['cashier'] }} &bull; {{ $confirmData['patient']['shift'] }} &bull; Transaksi belum dicatat.
                </p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-lg border text-sm font-medium bg-white">&larr; Detail tagihan</span>
            </div>
        </div>

        <div class="klinika-card p-5">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">{{ $confirmData['patient']['name'] }}</h3>
                <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background:#E6F7F7;color:#087F83">Menunggu kasir</span>
            </div>
            <p class="text-xs klinika-muted mt-1">{{ $confirmData['patient']['mrn'] }} &bull; NIK 3273015405880002</p>
            <p class="text-xs klinika-muted mt-2 pt-2 border-t">{{ $confirmData['patient']['visit_number'] }} &bull; {{ $confirmData['patient']['visit_date'] }} | {{ $confirmData['patient']['poli'] }}</p>
        </div>

        <div class="klinika-card p-5">
            <h4 class="font-semibold text-sm">Ringkasan pembayaran</h4>
            <p class="text-xs klinika-muted mt-2">Invoice {{ $confirmData['total_amount'] }}</p>
            <span class="inline-block px-2 py-0.5 rounded text-xs mt-2" style="background:#FFF5E4;color:#96611C">Belum dibayar</span>
        </div>
    </div>

    {{-- Modal Konfirmasi Tengah Sesuai Gambar --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl border p-6 space-y-4" style="border-color:var(--klinika-border)">
            <div>
                <h3 class="text-lg font-bold" style="color:var(--klinika-text)">
                    Konfirmasi pembayaran {{ $confirmData['total_amount'] }}?
                </h3>
                <p class="text-xs klinika-muted mt-1 leading-relaxed">
                    Pastikan pembayaran telah diterima. Aksi ini mencatat transaksi dan menerbitkan kuitansi; bukan sekadar menyimpan draf.
                </p>
            </div>

            {{-- Kartu Ringkasan Pasien & Invoice --}}
            <div class="p-3.5 rounded-lg border text-xs space-y-1" style="background:#FAFCFD;border-color:var(--klinika-border)">
                <p class="font-bold text-sm" style="color:var(--klinika-text)">
                    {{ $confirmData['patient']['name'] }} • {{ $confirmData['patient']['mrn'] }}
                </p>
                <p class="klinika-muted">{{ $confirmData['patient']['visit_number'] }} • {{ $confirmData['patient']['visit_date'] }}</p>
                <p class="klinika-muted">{{ $confirmData['invoice_number'] }} • {{ $confirmData['patient']['poli'] }}</p>
                <p class="klinika-muted">Kasir: {{ $confirmData['patient']['cashier'] }} • {{ $confirmData['patient']['shift'] }}</p>
            </div>

            {{-- Breakdown Transaksi --}}
            <div class="text-xs space-y-1.5" style="color:var(--klinika-text)">
                <p><span class="klinika-muted">Metode:</span> <span class="font-medium">{{ $confirmData['payment_method'] }}</span></p>
                <p><span class="klinika-muted">Total tagihan:</span> <span class="font-medium">{{ $confirmData['total_amount'] }}</span></p>
                <p><span class="klinika-muted">Nominal diterima:</span> <span class="font-medium">{{ $confirmData['amount_received'] }}</span></p>
                <p><span class="klinika-muted">Kembalian:</span> <span class="font-medium">{{ $confirmData['change'] }}</span></p>
                <p><span class="klinika-muted">Saldo setelah bayar:</span> <span class="font-medium">{{ $confirmData['balance_after'] }}</span></p>
            </div>

            {{-- Amber Box: Satu transaksi, satu kuitansi --}}
            <div class="p-3.5 rounded-lg border flex items-start gap-2.5" style="background:#FFF9EE;border-color:#FDE68A">
                <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#D97706">
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                </svg>
                <div class="text-xs">
                    <p class="font-bold" style="color:#92400E">Satu transaksi, satu kuitansi</p>
                    <p class="mt-0.5" style="color:#B45309">
                        Setelah berhasil, invoice menjadi Lunas dan kunjungan Selesai. Koreksi pembayaran memerlukan izin dan jejak audit.
                    </p>
                </div>
            </div>

            {{-- Checkbox Verifikasi & Form Submit --}}
            <form method="POST" action="{{ route('kasir.process-payment', $confirmData['invoice_number']) }}" class="space-y-4 pt-1">
                @csrf
                <input type="hidden" name="payment_method" value="{{ $confirmData['payment_method'] }}">
                <input type="hidden" name="amount_received" value="{{ $confirmData['amount_received'] }}">

                <div class="flex items-center gap-2">
                    <input type="checkbox"
                           id="confirm_received"
                           name="confirm_received"
                           required
                           checked
                           class="w-4 h-4 rounded text-[#087F83] focus:ring-[#087F83]"
                           style="accent-color:#087F83">
                    <label for="confirm_received" class="text-xs font-medium cursor-pointer" style="color:var(--klinika-text)">
                        Uang sudah diterima dan rincian transaksi sudah saya periksa.
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <a href="{{ route('kasir.payment', $confirmData['invoice_number']) }}"
                       class="px-4 py-2 rounded-lg border text-xs font-semibold hover:bg-gray-50 bg-white"
                       style="border-color:var(--klinika-border);color:var(--klinika-text)">
                        Kembali
                    </a>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                        Catat pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
