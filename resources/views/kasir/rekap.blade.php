@extends('layouts.app')

@section('title', 'Rekap Kas')
@section('page-title', 'Rekap kas')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('kasir.index') }}" class="hover:underline klinika-primary-text">Kasir</a>
    <span class="mx-1">/</span>
    <span>Rekap kas</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Rekap kas</h2>
            <p class="text-sm klinika-muted mt-0.5">Pantau penerimaan dan cocokkan saldo kas sebelum menutup shift.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button onclick="window.print()"
                    class="px-4 py-2 rounded-lg border klinika-border text-sm font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                Cetak
            </button>
            <button onclick="window.print()"
                    class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                Unduh rekap
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="klinika-card p-4">
        <form method="GET" action="{{ route('kasir.rekap') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Tanggal</label>
                <input type="text" name="tanggal" value="{{ $tanggal }}"
                       class="w-full px-3 py-2 rounded-lg border klinika-border text-sm bg-white focus:outline-none focus:ring-1" style="color:var(--klinika-text)">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Shift</label>
                <select name="shift" class="w-full px-3 py-2 rounded-lg border klinika-border text-sm bg-white focus:outline-none" style="color:var(--klinika-text)">
                    <option {{ str_contains($shift, 'Pagi') ? 'selected' : '' }}>Pagi • 08.00–14.00</option>
                    <option {{ str_contains($shift, 'Siang') ? 'selected' : '' }}>Siang • 14.00–20.00</option>
                    <option {{ str_contains($shift, 'Malam') ? 'selected' : '' }}>Malam • 20.00–08.00</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Kasir</label>
                <input type="text" name="kasir" value="{{ $kasir }}"
                       class="w-full px-3 py-2 rounded-lg border klinika-border text-sm bg-white focus:outline-none" style="color:var(--klinika-text)">
            </div>
            <button type="submit" class="px-5 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 shrink-0">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- 4 Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total penerimaan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $summary['total_penerimaan'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $summary['total_transaksi'] }} transaksi berhasil</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Penerimaan tunai</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $summary['penerimaan_tunai'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $summary['tunai_transaksi'] }} transaksi &bull; {{ $summary['tunai_pct'] }} dari total</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Penerimaan nontunai</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $summary['penerimaan_nontunai'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $summary['nontunai_desc'] }}</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Saldo kas diharapkan</p>
            <p class="text-3xl font-bold mt-1 klinika-primary-text">{{ $summary['saldo_diharapkan'] }}</p>
            <p class="text-xs klinika-muted mt-1">{{ $summary['saldo_desc'] }}</p>
        </div>
    </div>

    {{-- Main Grid: Penerimaan + Rekonsiliasi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Kiri: Penerimaan per metode + Pergerakan kas --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Penerimaan per metode --}}
            <div class="klinika-card overflow-hidden">
                <div class="p-5 klinika-border border-b">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Penerimaan per metode</h3>
                    <p class="text-xs klinika-muted mt-0.5">Hanya pembayaran berhasil pada periode dan shift terpilih.</p>
                </div>
                <table class="w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide klinika-border border-b" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                        <tr>
                            <th class="py-3 px-5 text-left">Metode pembayaran</th>
                            <th class="py-3 px-5 text-right">Transaksi</th>
                            <th class="py-3 px-5 text-right">Penerimaan</th>
                            <th class="py-3 px-5 text-right">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metodePembayaran as $i => $m)
                            <tr class="{{ $loop->last ? 'font-semibold klinika-border border-t' : 'klinika-border border-b' }}"
                                style="{{ $loop->last ? 'background:var(--klinika-bg)' : '' }}">
                                <td class="py-3 px-5" style="color:var(--klinika-text)">{{ $m['metode'] }}</td>
                                <td class="py-3 px-5 text-right klinika-muted">{{ $m['transaksi'] }}</td>
                                <td class="py-3 px-5 text-right font-mono" style="color:var(--klinika-text)">{{ $m['penerimaan'] }}</td>
                                <td class="py-3 px-5 text-right klinika-muted">{{ $m['kontribusi'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pergerakan kas tunai --}}
            <div class="klinika-card p-5">
                <h3 class="font-semibold text-sm mb-4" style="color:var(--klinika-text)">Pergerakan kas tunai</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="klinika-muted">Modal awal shift</span>
                        <span class="font-mono" style="color:var(--klinika-text)">{{ $pergerakanKas['modal_awal'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Penerimaan tunai</span>
                        <span class="font-mono text-emerald-600 font-medium">{{ $pergerakanKas['penerimaan'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="klinika-muted">Pengeluaran / pengembalian tunai</span>
                        <span class="font-mono" style="color:var(--klinika-text)">{{ $pergerakanKas['pengeluaran'] }}</span>
                    </div>
                    <div class="flex justify-between pt-2 klinika-border border-t font-semibold">
                        <span style="color:var(--klinika-text)">Saldo kas diharapkan</span>
                        <span class="font-mono klinika-primary-text">{{ $pergerakanKas['saldo_akhir'] }}</span>
                    </div>
                </div>
                <p class="mt-3 text-xs klinika-primary-text font-medium" style="background:var(--klinika-primary-bg,#e6f4f4);border-color:var(--klinika-primary-light,#b2dede)" >
                    <span class="inline-block px-3 py-2 rounded-lg border w-full">{{ $pergerakanKas['catatan'] }}</span>
                </p>
            </div>

        </div>

        {{-- Kanan: Rekonsiliasi shift --}}
        <div class="klinika-card p-5 h-fit">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Rekonsiliasi shift</h3>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                    Shift aktif
                </span>
            </div>

            <div class="text-xs klinika-muted mb-4">
                <p class="font-medium" style="color:var(--klinika-text)">{{ $kasir }} &bull; {{ $shift }}</p>
                <p>{{ $tanggal }}</p>
            </div>

            <div class="mb-4">
                <div class="flex justify-between items-center text-sm mb-2">
                    <span class="klinika-muted">Saldo diharapkan</span>
                    <span class="font-semibold font-mono" style="color:var(--klinika-text)">{{ $summary['saldo_diharapkan'] }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('kasir.tutup-shift') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Kas fisik terhitung</label>
                    <div class="flex items-center border klinika-border rounded-lg overflow-hidden">
                        <span class="px-3 py-2 text-sm klinika-muted border-r klinika-border" style="background:var(--klinika-bg)">Rp</span>
                        <input type="number"
                               id="kas_fisik"
                               name="kas_fisik"
                               placeholder="4.340.000"
                               class="flex-1 px-3 py-2 text-sm bg-white focus:outline-none" style="color:var(--klinika-text)"
                               onchange="hitungSelisih(this.value)">
                    </div>
                </div>

                <div class="p-3 rounded-lg klinika-border border" style="background:var(--klinika-bg)">
                    <div class="flex justify-between items-center text-sm">
                        <span class="klinika-muted">Selisih kas</span>
                        <span id="selisih_kas" class="font-semibold font-mono klinika-primary-text">Rp0</span>
                    </div>
                    <p id="selisih_label" class="text-xs text-emerald-600 mt-0.5">Saldo sesuai</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">Catatan penutupan</label>
                    <textarea name="catatan" rows="2" placeholder="Tambahkan catatan (opsional)"
                              class="w-full px-3 py-2 rounded-lg border klinika-border text-sm bg-white focus:outline-none resize-none" style="color:var(--klinika-text)"></textarea>
                </div>

                <button type="submit"
                        class="w-full py-2.5 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan &amp; tutup shift
                </button>

                <p class="text-xs klinika-muted text-center leading-relaxed">
                    Penutupan memerlukan konfirmasi. Data kas wajib diisi untuk disimpan.
                </p>
            </form>
        </div>
    </div>

    {{-- Rincian Transaksi --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 klinika-border border-b flex items-center justify-between">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Rincian transaksi</h3>
            <span class="text-xs klinika-muted">{{ count($rincianTransaksi) }} transaksi terakhir dari {{ $summary['total_transaksi'] }} transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide klinika-border border-b" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Waktu</th>
                        <th class="py-3 px-5 text-left">Nomor invoice</th>
                        <th class="py-3 px-5 text-left">Pasien</th>
                        <th class="py-3 px-5 text-left">Metode</th>
                        <th class="py-3 px-5 text-right">Penerimaan</th>
                        <th class="py-3 px-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @foreach ($rincianTransaksi as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-5 font-mono text-xs klinika-muted">{{ $trx['waktu'] }}</td>
                            <td class="py-3 px-5 font-mono text-xs klinika-primary-text">{{ $trx['invoice'] }}</td>
                            <td class="py-3 px-5 font-medium text-xs" style="color:var(--klinika-text)">{{ $trx['pasien'] }}</td>
                            <td class="py-3 px-5 text-xs klinika-muted">{{ $trx['metode'] }}</td>
                            <td class="py-3 px-5 text-right font-mono text-xs font-semibold" style="color:var(--klinika-text)">{{ $trx['penerimaan'] }}</td>
                            <td class="py-3 px-5 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $trx['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 klinika-border border-t text-xs klinika-muted">
            Pembayaran berhasil &bull; Diurutkan dari yang terbaru &bull; Data simulasi
        </div>
    </div>

    {{-- Riwayat Penutupan Shift --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 klinika-border border-b flex items-center justify-between">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Riwayat penutupan shift</h3>
            <span class="text-xs klinika-muted">{{ count($riwayatShift) }} shift terakhir</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide klinika-border border-b" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Tanggal</th>
                        <th class="py-3 px-5 text-left">Shift</th>
                        <th class="py-3 px-5 text-left">Kasir</th>
                        <th class="py-3 px-5 text-right">Kas diharapkan</th>
                        <th class="py-3 px-5 text-right">Kas fisik</th>
                        <th class="py-3 px-5 text-right">Selisih</th>
                        <th class="py-3 px-5 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @foreach ($riwayatShift as $sh)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-5 text-xs klinika-muted">{{ $sh['tanggal'] }}</td>
                            <td class="py-3 px-5 text-xs font-medium" style="color:var(--klinika-text)">{{ $sh['shift'] }}</td>
                            <td class="py-3 px-5 text-xs" style="color:var(--klinika-text)">{{ $sh['kasir'] }}</td>
                            <td class="py-3 px-5 text-right font-mono text-xs" style="color:var(--klinika-text)">{{ $sh['saldo_diharapkan'] }}</td>
                            <td class="py-3 px-5 text-right font-mono text-xs" style="color:var(--klinika-text)">{{ $sh['kas_fisik'] }}</td>
                            <td class="py-3 px-5 text-right font-mono text-xs font-semibold text-emerald-600">{{ $sh['selisih'] }}</td>
                            <td class="py-3 px-5 text-right">
                                <span class="text-xs klinika-muted">Ditutup &bull; {{ $sh['ditutup'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function hitungSelisih(val) {
        const saldoDiharapkan = 4340000; // ponytail: hardcoded, replace with PHP var when real data
        const kasFisik = parseFloat(val.replace(/\D/g, '')) || 0;
        const selisih = kasFisik - saldoDiharapkan;
        const el = document.getElementById('selisih_kas');
        const label = document.getElementById('selisih_label');

        el.textContent = 'Rp' + Math.abs(selisih).toLocaleString('id-ID');
        if (selisih === 0) {
            el.className = 'font-semibold font-mono text-emerald-600';
            label.textContent = 'Saldo sesuai';
            label.className = 'text-xs text-emerald-600 mt-0.5';
        } else if (selisih < 0) {
            el.className = 'font-semibold font-mono text-red-600';
            label.textContent = 'Kurang dari saldo diharapkan';
            label.className = 'text-xs text-red-600 mt-0.5';
        } else {
            el.className = 'font-semibold font-mono text-amber-600';
            label.textContent = 'Lebih dari saldo diharapkan';
            label.className = 'text-xs text-amber-600 mt-0.5';
        }
    }
</script>
@endsection
