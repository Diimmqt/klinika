@extends('layouts.app')

@section('title', 'Detail Poli - ' . $poli->name)
@section('page-title', 'Detail poli')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('polis.index') }}" class="hover:underline klinika-primary-text">Data poli</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">{{ $poli->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-xl font-bold" style="color:var(--klinika-text)">{{ $poli->name }}</h2>
                @if($poli->is_active)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                @endif
            </div>
            <p class="text-sm klinika-muted mt-0.5">Kode: <span class="font-mono">{{ $poli->code }}</span> • Prefix antrean: <span class="font-mono font-semibold">{{ $poli->queue_prefix }}</span></p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('polis.edit', $poli) }}"
               class="px-4 py-2 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90">
                Edit poli
            </a>

            <form method="POST" action="{{ route('polis.toggle-status', $poli) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="px-4 py-2 rounded-lg border klinika-border bg-white text-xs font-semibold hover:bg-slate-50"
                        style="color:var(--klinika-text)">
                    {{ $poli->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>

            <form method="POST" action="{{ route('polis.destroy', $poli) }}" onsubmit="return confirm('Hapus poli ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Tenaga Kesehatan Terhubung</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $connectedPractitioners->count() }}</p>
            <p class="text-xs klinika-muted mt-1">Terhubung melalui relasi jadwal</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total Kunjungan</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $poli->visits_count }}</p>
            <p class="text-xs klinika-muted mt-1">Riwayat kunjungan terdaftar</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total Antrean</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $poli->queues_count }}</p>
            <p class="text-xs klinika-muted mt-1">Nomor antrean diterbitkan</p>
        </div>
    </div>

    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border flex items-center justify-between gap-4" style="background:var(--klinika-bg)">
            <div>
                <h3 class="text-sm font-semibold" style="color:var(--klinika-text)">Tenaga Kesehatan yang Terhubung dengan {{ $poli->name }}</h3>
                <p class="text-xs klinika-muted">Diambil melalui relasi tabel `schedules` yang tersedia.</p>
            </div>
            <a href="{{ route('practitioners.create', ['poli_id' => $poli->id]) }}"
               class="text-xs font-semibold klinika-primary-text hover:underline">
                + Tambah tenaga kesehatan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Nama</th>
                        <th class="py-3 px-5 text-left">Profesi</th>
                        <th class="py-3 px-5 text-left">No. STR / SIP</th>
                        <th class="py-3 px-5 text-left">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($connectedPractitioners as $practitioner)
                        <tr>
                            <td class="py-3.5 px-5 font-semibold text-xs" style="color:var(--klinika-text)">{{ $practitioner->name }}</td>
                            <td class="py-3.5 px-5 text-xs">{{ $practitioner->profession_label }}</td>
                            <td class="py-3.5 px-5 text-xs klinika-muted">
                                {{ $practitioner->str_number ?: '—' }} / {{ $practitioner->sip_number ?: '—' }}
                            </td>
                            <td class="py-3.5 px-5 text-xs">
                                @if($practitioner->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right text-xs">
                                <a href="{{ route('practitioners.show', $practitioner) }}" class="font-semibold klinika-primary-text hover:underline">Detail →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-xs klinika-muted">Belum ada tenaga kesehatan yang dihubungkan ke poli ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
