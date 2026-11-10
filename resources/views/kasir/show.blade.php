@extends('layouts.app')

@section('title', 'Detail Invoice - ' . $invoiceData['invoice_number'])
@section('page-title', 'Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.invoices') }}" class="hover:underline klinika-primary-text">Invoice</a>
    <span class="mx-1">/</span>
    <span>{{ $invoiceData['invoice_number'] }}</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Kembali --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Invoice {{ $invoiceData['invoice_number'] }}</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Terbit {{ $invoiceData['issue_date'] }} &bull; {{ $invoiceData['insurance'] }}
            </p>
        </div>
        <div>
            <a href="{{ route('kasir.invoices') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                &larr; Daftar invoice
            </a>
        </div>
    </div>

    {{-- Kartu Data Pasien & Kunjungan --}}
    <div class="klinika-card p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h3 class="text-lg font-bold" style="color:var(--klinika-text)">{{ $invoiceData['patient']['name'] }}</h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:#E6F7F7;color:#087F83">
                {{ $invoiceData['visit']['status'] }}
            </span>
        </div>
        <p class="text-xs klinika-muted mt-1">
            {{ $invoiceData['patient']['mrn'] }} &bull; {{ $invoiceData['patient']['gender'] }} &bull; {{ $invoiceData['patient']['birth_date'] }} ({{ $invoiceData['patient']['age'] }} tahun) &bull; NIK {{ $invoiceData['patient']['nik'] }}
        </p>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-2 pt-2 border-t" style="border-color:var(--klinika-border)">
            <p class="text-xs klinika-muted">
                {{ $invoiceData['visit']['visit_number'] }} &bull; {{ $invoiceData['visit']['date_time'] }} | {{ $invoiceData['visit']['poli'] }} | {{ $invoiceData['visit']['doctor'] }} | {{ $invoiceData['visit']['insurance'] }} | {{ $invoiceData['visit']['queue_code'] }}
            </p>
            <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline text-xs font-medium">
                Lihat riwayat &rarr;
            </a>
        </div>
    </div>

    {{-- Dua Kolom: Rincian Tagihan & Ringkasan Biaya --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Kolom Kiri: Tabel Rincian Tagihan --}}
        <div class="lg:col-span-2 klinika-card overflow-hidden">
            <div class="px-5 py-4 border-b klinika-border">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Rincian tagihan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#F4F7F8">
                            <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Item layanan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Sumber</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:var(--klinika-border)">
                        @foreach ($invoiceData['items'] as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                    {{ $item['name'] }}
                                </td>
                                <td class="px-4 py-3 text-xs klinika-muted">
                                    {{ $item['source'] }}
                                </td>
                                <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                    {{ $item['price'] }}
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold" style="color:#087F83">
                                    {{ $item['subtotal'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Biaya & Aksi Pembayaran --}}
        <div class="lg:col-span-1 klinika-card p-5 space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Ringkasan biaya</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="klinika-muted">Subtotal layanan</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $invoiceData['summary']['subtotal_services'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Subtotal obat</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $invoiceData['summary']['subtotal_medicine'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Diskon</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $invoiceData['summary']['discount'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Pajak</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $invoiceData['summary']['tax'] }}</span>
                    </div>
                    <div class="pt-2 border-t flex justify-between font-bold" style="border-color:var(--klinika-border)">
                        <span style="color:var(--klinika-text)">Total tagihan</span>
                        <span style="color:var(--klinika-text)">{{ $invoiceData['summary']['total'] }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <span class="inline-block px-2.5 py-1 rounded text-xs font-semibold" style="background:#FFF5E4;color:#96611C">
                        {{ $invoiceData['summary']['status'] }}
                    </span>
                </div>
            </div>

            <div class="pt-4 border-t" style="border-color:var(--klinika-border)">
                <a href="{{ route('kasir.payment', $invoiceData['invoice_number']) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                    Lanjut pembayaran &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- Banner Hijau/Cyan: Seluruh item telah final --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Seluruh item telah final dan obat diserahkan</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Pembayaran dapat diproses. Periksa ulang identitas, item layanan, dan penjamin sebelum menerima uang.
            </p>
        </div>
    </div>

    {{-- Jejak Administrasi --}}
    <div class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-2" style="color:var(--klinika-text)">Jejak administrasi</h3>
        <div class="space-y-1 text-xs klinika-muted">
            @foreach ($invoiceData['logs'] as $log)
                <p>{{ $log }}</p>
            @endforeach
        </div>
    </div>

</div>
@endsection
