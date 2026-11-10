@extends('layouts.app')

@section('title', 'Penyusunan Tagihan')
@section('page-title', 'Invoice')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.invoices') }}" class="hover:underline klinika-primary-text">Invoice</a>
    <span class="mx-1">/</span>
    <span>Penyusunan tagihan</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Kembali --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Penyusunan tagihan</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Tinjau sumber layanan final dan obat yang diserahkan sebelum penerbitan otomatis.
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

    {{-- Kartu: Pilih kunjungan layak tagih --}}
    <div class="klinika-card p-5 space-y-4">
        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Pilih kunjungan layak tagih</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">
                    Kunjungan &bull; Belum memiliki invoice
                </label>
                <select class="w-full px-3 py-2 text-xs rounded-lg border bg-white focus:outline-none focus:ring-1 focus:ring-[#087F83]"
                        style="border-color:var(--klinika-border)">
                    <option selected>{{ $billingData['visit_number'] }} &bull; {{ $billingData['patient_name'] }} &bull; {{ $billingData['poli'] }}</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">
                    Penjamin
                </label>
                <input type="text"
                       value="{{ $billingData['insurance'] }}"
                       disabled
                       class="w-full px-3 py-2 text-xs rounded-lg border bg-gray-100 text-gray-700 cursor-not-allowed"
                       style="border-color:var(--klinika-border)">
            </div>
        </div>

        <div class="pt-3 border-t space-y-1 text-xs" style="border-color:var(--klinika-border)">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <p class="font-bold text-sm" style="color:var(--klinika-text)">
                    {{ $billingData['patient_name'] }} &bull; {{ $billingData['mrn'] }}
                </p>
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold" style="background:#E6F7F7;color:#087F83">
                    Simulasi &bull; Belum diterbitkan
                </span>
            </div>
            <p class="klinika-muted">
                {{ $billingData['date'] }} &bull; {{ $billingData['clinic'] }} &bull; Antrean {{ $billingData['queue_code'] }} &bull; Kasir: {{ $billingData['cashier'] }}
            </p>
            <p class="klinika-muted">
                Kunjungan ilustratif baru, bukan KJ-20261006-023. Penyusunan tidak mengubah invoice yang sudah terbit.
            </p>
        </div>
    </div>

    {{-- Layout Dua Kolom: Rincian (Kiri) & Ringkasan (Kanan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Kolom Kiri: Layanan Final & Obat Diserahkan --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Tabel 1: Layanan dan tindakan final --}}
            <div class="klinika-card overflow-hidden">
                <div class="p-4 border-b klinika-border">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Layanan dan tindakan final</h3>
                    <p class="text-xs klinika-muted mt-0.5">
                        Sumber: RME-SIM-041 &bull; Final, 10.25 WIB (simulasi). Harga mengikuti katalog layanan.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#F4F7F8">
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Item layanan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Harga katalog</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--klinika-border)">
                            @foreach ($billingData['services'] as $srv)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                                        {{ $srv['name'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                        {{ $srv['qty'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                        {{ $srv['price'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                                        {{ $srv['subtotal'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel 2: Obat yang diserahkan --}}
            <div class="klinika-card overflow-hidden">
                <div class="p-4 border-b klinika-border">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Obat yang diserahkan</h3>
                    <p class="text-xs klinika-muted mt-0.5">
                        Sumber: RX-SIM-20261006-041 &bull; Penyerahan tercatat, 10.30 WIB (simulasi). Hanya kuantitas yang benar-benar diserahkan.
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#F4F7F8">
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Obat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Jumlah</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Harga katalog</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color:var(--klinika-border)">
                            @foreach ($billingData['medicines'] as $med)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                                        {{ $med['name'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                        {{ $med['qty'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                        {{ $med['price'] }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-medium" style="color:var(--klinika-text)">
                                        {{ $med['subtotal'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-t klinika-border text-[11px] klinika-muted" style="background:#FAFCFD">
                    Seluruh item dan harga pada kunjungan ini adalah simulasi. Kasir tidak mengubah order dokter atau jumlah penyerahan farmasi.
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Ringkasan Penyusunan & Pemeriksaan Penerbitan --}}
        <div class="lg:col-span-1 space-y-5">
            {{-- Card Ringkasan Penyusunan --}}
            <div class="klinika-card p-5 space-y-4">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ringkasan penyusunan</h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="klinika-muted">Subtotal layanan</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $billingData['services_subtotal'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Subtotal obat</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $billingData['medicines_subtotal'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Diskon</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $billingData['discount'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Pajak</span>
                        <span class="font-medium" style="color:var(--klinika-text)">{{ $billingData['tax'] }}</span>
                    </div>
                    <div class="pt-3 border-t" style="border-color:var(--klinika-border)">
                        <p class="text-xs klinika-muted">Total disiapkan</p>
                        <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $billingData['total'] }}</p>
                    </div>
                </div>

                <div class="pt-1">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background:#E6F7F7;color:#087F83">
                        Sumber lengkap &bull; Layak tagih
                    </span>
                </div>

                {{-- Form Simpan Penyusunan --}}
                <form method="POST" action="{{ route('kasir.billing.store') }}" class="pt-3 border-t space-y-2" style="border-color:var(--klinika-border)">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                        Simpan penyusunan
                    </button>
                    <a href="{{ route('kasir.invoices') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg border text-sm font-medium hover:bg-gray-50 bg-white"
                       style="border-color:var(--klinika-border);color:var(--klinika-text)">
                        Batal
                    </a>
                </form>

                <p class="text-[11px] klinika-muted leading-relaxed pt-2">
                    Setelah disimpan dan syarat terpenuhi, sistem menerbitkan invoice otomatis. Tidak ada penerbitan manual kedua.
                </p>
            </div>

            {{-- Card Pemeriksaan Penerbitan --}}
            <div class="klinika-card p-5 space-y-3">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Pemeriksaan penerbitan</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span style="color:var(--klinika-text)">Layanan dan tindakan final</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span style="color:var(--klinika-text)">Penyerahan obat tercatat</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span style="color:var(--klinika-text)">Belum ada invoice kunjungan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner Cyan: Satu kunjungan, satu invoice --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Satu kunjungan, satu invoice</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Sistem memeriksa ulang kelayakan dan invoice yang sudah ada saat menyimpan. Jika invoice telah terbit, gunakan invoice tersebut; jangan membuat tagihan ganda. Pembayaran tetap diproses melalui menu Pembayaran.
            </p>
        </div>
    </div>

</div>
@endsection
