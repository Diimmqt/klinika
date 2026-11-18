@extends('layouts.app')

@section('title', 'Pengelolaan Data Poli')
@section('page-title', 'Data poli')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Data poli</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pengelolaan Data Poli</h2>
            <p class="text-sm klinika-muted mt-0.5">Kelola daftar unit layanan poli, awalan antrean, dan keterkaitannya dengan tenaga kesehatan.</p>
        </div>
        <a href="{{ route('polis.create') }}"
           class="px-4 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah poli
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total poli</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['total'] }}</p>
            <p class="text-xs klinika-muted mt-1">Terdaftar di database</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Poli aktif</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['active'] }}</p>
            <p class="text-xs klinika-muted mt-1">Tersedia untuk pelayanan</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Poli nonaktif</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['inactive'] }}</p>
            <p class="text-xs klinika-muted mt-1">Sementara tidak beroperasi</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Nakes terhubung</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['practitioners'] }}</p>
            <p class="text-xs klinika-muted mt-1">Terhubung via jadwal/poli</p>
        </div>
    </div>

    {{-- Filter & Table --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <form method="GET" action="{{ route('polis.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari kode poli, nama poli, atau prefix antrean..."
                           class="w-full pl-9 pr-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none"
                           style="color:var(--klinika-text)">
                </div>

                <div class="w-full sm:w-44">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none"
                            style="color:var(--klinika-text)">
                        <option value="">Semua status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                    </select>
                </div>

                <button type="submit"
                        class="px-4 py-2 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90">
                    Cari
                </button>

                @if($search !== '' || $status !== '')
                    <a href="{{ route('polis.index') }}"
                       class="px-3 py-2 rounded-lg border klinika-border text-xs font-medium klinika-muted hover:bg-slate-50 text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs font-semibold uppercase tracking-wide border-b klinika-border" style="background:var(--klinika-bg);color:var(--klinika-muted)">
                    <tr>
                        <th class="py-3 px-5 text-left">Kode / Nama Poli</th>
                        <th class="py-3 px-5 text-left">Kode Antrean</th>
                        <th class="py-3 px-5 text-left">Tenaga Kesehatan</th>
                        <th class="py-3 px-5 text-left">Kunjungan</th>
                        <th class="py-3 px-5 text-left">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($polis as $poli)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="font-semibold text-xs" style="color:var(--klinika-text)">{{ $poli->name }}</div>
                                <div class="text-[11px] font-mono klinika-muted mt-0.5">{{ $poli->code }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2.5 py-1 rounded text-xs font-mono font-semibold" style="background:#EEF9F9;color:var(--klinika-primary)">
                                    {{ $poli->queue_prefix }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-xs klinika-muted">{{ $poli->practitioners_count }} orang</td>
                            <td class="py-3.5 px-5 text-xs klinika-muted">{{ $poli->visits_count }} kunjungan</td>
                            <td class="py-3.5 px-5 text-xs">
                                @if($poli->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right text-xs">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('polis.show', $poli) }}" class="font-semibold klinika-primary-text hover:underline">Detail</a>
                                    <a href="{{ route('polis.edit', $poli) }}" class="font-semibold klinika-muted hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('polis.toggle-status', $poli) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="font-semibold hover:underline" style="color:{{ $poli->is_active ? '#96611C' : '#087F83' }}">
                                            {{ $poli->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs klinika-muted">Belum ada data poli yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($polis->hasPages())
            <div class="px-5 py-3 border-t klinika-border" style="background:var(--klinika-bg)">
                {{ $polis->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
