@extends('layouts.app')

@section('title', 'Detail Tenaga Kesehatan - ' . $practitioner->name)
@section('page-title', 'Detail tenaga kesehatan')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('practitioners.index') }}" class="hover:underline klinika-primary-text">Tenaga kesehatan</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">{{ $practitioner->name }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h2 class="text-xl font-bold" style="color:var(--klinika-text)">{{ $practitioner->name }}</h2>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#EEF9F9;color:var(--klinika-primary)">
                    {{ $practitioner->profession_label }}
                </span>
                @if($practitioner->is_active)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                @endif
            </div>
            <p class="text-sm klinika-muted mt-0.5">
                {{ $practitioner->user ? 'Akun pengguna: ' . $practitioner->user->name . ' (' . $practitioner->user->email . ')' : 'Belum ditautkan ke akun pengguna' }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('practitioners.edit', $practitioner) }}"
               class="px-4 py-2 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90">
                Edit data
            </a>

            <form method="POST" action="{{ route('practitioners.toggle-status', $practitioner) }}">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="px-4 py-2 rounded-lg border klinika-border bg-white text-xs font-semibold hover:bg-slate-50"
                        style="color:var(--klinika-text)">
                    {{ $practitioner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>

            <form method="POST" action="{{ route('practitioners.destroy', $practitioner) }}" onsubmit="return confirm('Hapus data tenaga kesehatan ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 rounded-lg border border-rose-200 bg-rose-50 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Nomor STR</p>
            <p class="text-sm font-semibold mt-1.5" style="color:var(--klinika-text)">{{ $practitioner->str_number ?: '—' }}</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Nomor SIP</p>
            <p class="text-sm font-semibold mt-1.5" style="color:var(--klinika-text)">{{ $practitioner->sip_number ?: '—' }}</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">ID SATUSEHAT</p>
            <p class="text-sm font-semibold font-mono mt-1.5" style="color:var(--klinika-text)">{{ $practitioner->satusehat_practitioner_id ?: '—' }}</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total Kunjungan Ditangani</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $practitioner->visits_count }}</p>
        </div>
    </div>

    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border flex items-center justify-between gap-4" style="background:var(--klinika-bg)">
            <div>
                <h3 class="text-sm font-semibold" style="color:var(--klinika-text)">Poli yang Terhubung</h3>
                <p class="text-xs klinika-muted">Daftar poli tempat tenaga kesehatan bertugas melalui tabel `schedules`.</p>
            </div>
            <a href="{{ route('practitioners.edit', $practitioner) }}" class="text-xs font-semibold klinika-primary-text hover:underline">
                Ubah keterhubungan poli →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Kode Poli</th>
                        <th class="py-3 px-5 text-left">Nama Poli</th>
                        <th class="py-3 px-5 text-left">Kode Antrean</th>
                        <th class="py-3 px-5 text-left">Status Poli</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($connectedPolis as $poli)
                        <tr>
                            <td class="py-3.5 px-5 font-mono text-xs klinika-muted">{{ $poli->code }}</td>
                            <td class="py-3.5 px-5 font-semibold text-xs" style="color:var(--klinika-text)">{{ $poli->name }}</td>
                            <td class="py-3.5 px-5 font-mono text-xs">{{ $poli->queue_prefix }}</td>
                            <td class="py-3.5 px-5 text-xs">
                                @if($poli->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right text-xs">
                                <a href="{{ route('polis.show', $poli) }}" class="font-semibold klinika-primary-text hover:underline">Lihat poli →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-xs klinika-muted">Tenaga kesehatan ini belum dihubungkan dengan poli mana pun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
