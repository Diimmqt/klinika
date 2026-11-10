@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - ' . $receiptData['receipt_number'])
@section('page-title', 'Kuitansi')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.show', 'INV-20261006-023') }}" class="hover:underline klinika-primary-text">INV-20261006-023</a>
    <span class="mx-1">/</span>
    <span>Kuitansi</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Cetak Kuitansi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pembayaran berhasil</h2>
            <p class="text-sm klinika-muted mt-0.5">
                {{ $receiptData['receipt_number'] }} &bull; {{ $receiptData['receipt_date'] }}
            </p>
        </div>
        <div>
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Cetak kuitansi
            </button>
        </div>
    </div>

    {{-- Banner Hijau: Pembayaran Berhasil Dicatat --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#ecfdf5;border-color:#a7f3d0;color:#065f46">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs">
            <p class="font-bold">{{ $receiptData['amount'] }} berhasil dicatat &bull; Kunjungan selesai</p>
            <p class="mt-0.5">{{ $receiptData['trx_number'] }} &bull; Kasir {{ $receiptData['cashier'] }}. Semua layanan selesai dan saldo tagihan Rp0.</p>
        </div>
    </div>

    {{-- Kartu Data Pasien --}}
    <div class="klinika-card p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="text-lg font-bold" style="color:var(--klinika-text)">{{ $receiptData['patient']['name'] }}</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:#DCFCE7;color:#15803D">
                Selesai
            </span>
        </div>
        <p class="text-xs klinika-muted mt-1">
            {{ $receiptData['patient']['mrn'] }} &bull; {{ $receiptData['patient']['gender'] }} &bull; {{ $receiptData['patient']['birth_date'] }} ({{ $receiptData['patient']['age'] }} tahun) &bull; NIK {{ $receiptData['patient']['nik'] }}
        </p>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-2 pt-2 border-t" style="border-color:var(--klinika-border)">
            <p class="text-xs klinika-muted">
                {{ $receiptData['visit']['visit_number'] }} &bull; {{ $receiptData['visit']['date_time'] }} | {{ $receiptData['visit']['poli'] }} | {{ $receiptData['visit']['doctor'] }} | {{ $receiptData['visit']['insurance'] }} | {{ $receiptData['visit']['queue_code'] }}
            </p>
            <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline text-xs font-medium">
                Lihat riwayat &rarr;
            </a>
        </div>
    </div>

    {{-- Dua Kolom: Kuitansi Pembayaran & Status Akhir --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Kolom Kiri: Tabel Kuitansi --}}
        <div class="lg:col-span-2 klinika-card overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-5 py-4 border-b klinika-border">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Kuitansi pembayaran</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#F4F7F8">
                                <th class="px-5 py-3 text-left text-xs font-semibold klinika-muted">Rincian</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold klinika-muted">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--klinika-border)">
                            @foreach ($receiptData['items'] as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 text-xs" style="color:var(--klinika-text)">
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-xs font-medium" style="color:var(--klinika-text)">
                                        {{ $item['amount'] }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background:#FAFCFD">
                                <td class="px-5 py-3.5 text-xs font-bold" style="color:var(--klinika-text)">
                                    Total dibayar
                                </td>
                                <td class="px-5 py-3.5 text-right text-xs font-bold" style="color:var(--klinika-text)">
                                    {{ $receiptData['total'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="px-5 py-3.5 border-t klinika-border text-xs klinika-muted" style="background:#FAFCFD">
                {{ $receiptData['payment_info'] }}
            </div>
        </div>

        {{-- Kolom Kanan: Status Akhir --}}
        <div class="lg:col-span-1 klinika-card p-5 space-y-3">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Status akhir</h3>
            <div class="flex flex-col gap-2 pt-1">
                <div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">
                        Kunjungan: selesai
                    </span>
                </div>
                <div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">
                        RME: final
                    </span>
                </div>
                <div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">
                        Resep: diserahkan
                    </span>
                </div>
                <div>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">
                        Pembayaran: lunas
                    </span>
                </div>
            </div>
            <p class="text-xs klinika-muted pt-3 border-t leading-relaxed" style="border-color:var(--klinika-border)">
                Empat entitas berbeda. Encounter tetap {{ $receiptData['visit']['visit_number'] }}.
            </p>
        </div>
    </div>

    {{-- Linimasa Kunjungan --}}
    <div class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-2" style="color:var(--klinika-text)">Linimasa kunjungan</h3>
        <div class="space-y-1 text-xs klinika-muted leading-relaxed">
            <p>{{ $receiptData['timeline']['steps'] }}</p>
            <p>{{ $receiptData['timeline']['note'] }}</p>
        </div>
    </div>

    {{-- Tombol Navigasi Bawah --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('kasir.invoices') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
           style="border-color:var(--klinika-border);color:var(--klinika-text)">
            &larr; Daftar invoice
        </a>
        <a href="{{ route('visits.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
           style="border-color:var(--klinika-border);color:var(--klinika-text)">
            Riwayat kunjungan &rarr;
        </a>
    </div>

</div>
@endsection
