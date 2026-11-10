@extends('layouts.app')

@section('title', 'Invoice')
@section('page-title', 'Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Invoice</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Rekap Hari Ini --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Invoice</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Tagihan terkait encounter. Catatan klinis tidak ditampilkan di ruang kasir.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('kasir.billing.create') }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                + Susun tagihan
            </a>
            <a href="{{ route('kasir.index') }}"
               class="px-4 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                Rekap hari ini
            </a>
        </div>
    </div>

    {{-- 3 Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1: Belum dibayar --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Belum dibayar</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['unpaid_count'] }}</p>
            <p class="text-xs klinika-muted mt-1">Total {{ $stats['unpaid_total'] }}</p>
        </div>

        {{-- Card 2: Lunas hari ini --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Lunas hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['paid_count'] }}</p>
            <p class="text-xs klinika-muted mt-1">Total penerimaan {{ $stats['paid_total'] }}</p>
        </div>

        {{-- Card 3: Menunggu validasi --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Menunggu validasi</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['pending_validation'] }}</p>
            <p class="text-xs klinika-muted mt-1">Tidak ada transaksi tertunda</p>
        </div>
    </div>

    {{-- Table Card: Daftar tagihan --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-4 border-b klinika-border">
            <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Daftar tagihan</h3>

            {{-- Search & Filters --}}
            <form method="GET" action="{{ route('kasir.invoices') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3 top-3 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ request('search', $search) }}"
                           placeholder="Cari invoice, nama, atau nomor RM"
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border focus:outline-none focus:ring-1 focus:ring-[#087F83] bg-white"
                           style="border-color:var(--klinika-border)">
                </div>

                <div class="w-full sm:w-48">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 text-xs rounded-lg border focus:outline-none focus:ring-1 focus:ring-[#087F83] bg-white"
                            style="border-color:var(--klinika-border)">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua pembayaran</option>
                        <option value="Belum dibayar" {{ request('status') == 'Belum dibayar' ? 'selected' : '' }}>Belum dibayar</option>
                        <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <div class="w-full sm:w-48">
                    <select name="date"
                            class="w-full px-3 py-2 text-xs rounded-lg border focus:outline-none focus:ring-1 focus:ring-[#087F83] bg-white"
                            style="border-color:var(--klinika-border)">
                        <option value="{{ date('Y-m-d') }}">{{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Invoice / tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Pasien / RM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Pembayaran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse ($invoices as $inv)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <div>{{ $inv['invoice_number'] }}</div>
                                <div class="text-[11px] klinika-muted font-normal">{{ $inv['date_time'] }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">{{ $inv['patient_name'] }}</div>
                                <div class="text-[11px] klinika-muted">{{ $inv['mrn'] }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                {{ $inv['visit_number'] }}
                            </td>
                            <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                                {{ $inv['total'] }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if ($inv['status'] === 'Lunas')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium" style="background:#DCFCE7;color:#15803D">Lunas</span>
                                @else
                                    <span style="color:var(--klinika-text)">{{ $inv['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="{{ route('kasir.show', $inv['invoice_number']) }}" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    {{ $inv['action_label'] }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs klinika-muted">
                                Tidak ada data tagihan yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t klinika-border flex items-center justify-between text-xs klinika-muted">
            <span>Menampilkan 1–{{ count($invoices) }} dari {{ count($invoices) }} data simulasi</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

</div>
@endsection
