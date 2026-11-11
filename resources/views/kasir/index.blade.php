@extends('layouts.app')

@section('title', 'Kas dan Tagihan Hari Ini')
@section('page-title', 'Kas dan tagihan hari ini')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Kas dan tagihan hari ini</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Buka Invoice --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Kas dan tagihan hari ini</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Shift pagi &bull; {{ auth()->user()?->name ?? 'Budi Hartono' }} &bull; 08.00–14.00
            </p>
        </div>
        <div>
            <a href="{{ route('kasir.invoices') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Buka invoice
            </a>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Belum dibayar --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Belum dibayar</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['unpaid_count'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $stats['unpaid_total'] }} total tagihan</p>
        </div>

        {{-- Card 2: Pembayaran hari ini --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Pembayaran hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['paid_count'] }}</p>
            <p class="text-xs klinika-muted mt-1">Transaksi berhasil</p>
        </div>

        {{-- Card 3: Penerimaan --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Penerimaan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['paid_total_abbr'] }}</p>
            <p class="text-xs klinika-muted mt-1">Tunai + nontunai</p>
        </div>

        {{-- Card 4: Selisih kas --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Selisih kas</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['diff'] }}</p>
            <p class="text-xs klinika-muted mt-1">Saldo sesuai rekap</p>
        </div>
    </div>

    {{-- Banner Informasi Cyan --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Konfirmasi sebelum mencatat pembayaran</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Cocokkan pasien, ID kunjungan, total tagihan, metode, dan nominal diterima. Jangan mencatat dua kali.
            </p>
        </div>
    </div>

    {{-- Tabel: Invoice menunggu pembayaran --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Invoice menunggu pembayaran</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Pasien / RM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse ($unpaidInvoices as $inv)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                {{ $inv['invoice_number'] }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">{{ $inv['patient_name'] }}</div>
                                <div class="text-[11px] klinika-muted">{{ $inv['mrn'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                {{ $inv['visit_number'] }}
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                {{ $inv['total'] }}
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                {{ $inv['status'] }}
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="{{ route('kasir.payment', $inv['invoice_number']) }}" 
                                   class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Bayar <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs klinika-muted">
                                Tidak ada invoice yang menunggu pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t klinika-border flex items-center justify-between text-xs klinika-muted">
            <span>Menampilkan {{ count($unpaidInvoices) }} dari {{ $stats['unpaid_count'] }} invoice belum dibayar &bull; cuplikan data simulasi</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Dua Kartu Bawah: Penerimaan per metode & Rekap shift --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Card 1: Penerimaan per metode --}}
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Penerimaan per metode</h3>
            <div class="mt-3 space-y-1.5 text-xs">
                <p style="color:var(--klinika-text)">Tunai &bull; 26 transaksi &bull; Rp3.840.000</p>
                <p style="color:var(--klinika-text)">QRIS &bull; 16 transaksi &bull; Rp2.400.000</p>
                <p style="color:var(--klinika-text)">Transfer &bull; 4 transaksi &bull; Rp600.000</p>
            </div>
        </div>

        {{-- Card 2: Rekap shift --}}
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Rekap shift</h3>
            <div class="mt-3 space-y-1 text-xs">
                <p style="color:var(--klinika-text)">Modal awal Rp500.000 &bull; Kas tunai Rp4.340.000</p>
                <p class="klinika-muted">Penutupan shift memerlukan rekonsiliasi.</p>
                <div class="pt-1">
                    <a href="{{ route('kasir.rekap') }}" class="inline-block font-semibold klinika-primary-text hover:underline">
                        Lihat rekap kas &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
