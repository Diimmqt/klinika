@extends('layouts.app')

@section('title', 'Pengelolaan Data Tenaga Kesehatan')
@section('page-title', 'Tenaga kesehatan')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Tenaga kesehatan</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pengelolaan Data Tenaga Kesehatan</h2>
            <p class="text-sm klinika-muted mt-0.5">Kelola profil dokter, perawat, dan apoteker beserta keterhubungannya ke poli.</p>
        </div>
        <a href="{{ route('practitioners.create') }}"
           class="px-4 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah tenaga kesehatan
        </a>
    </div>

    {{-- Grouping Cards by Profession --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <a href="{{ route('practitioners.index') }}" class="klinika-card p-4 hover:border-teal-600/40 transition-colors {{ $profession === '' ? 'ring-1 ring-[#087F83]' : '' }}">
            <p class="text-xs klinika-muted font-medium">Semua Profesi</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['total'] }}</p>
            <p class="text-[11px] klinika-muted mt-1">{{ $stats['active'] }} aktif</p>
        </a>
        <a href="{{ route('practitioners.index', ['profession' => 'dokter']) }}" class="klinika-card p-4 hover:border-teal-600/40 transition-colors {{ $profession === 'dokter' ? 'ring-1 ring-[#087F83]' : '' }}">
            <p class="text-xs klinika-muted font-medium">Dokter</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['dokter'] }}</p>
            <p class="text-[11px] klinika-muted mt-1">Kelompok medis dokter</p>
        </a>
        <a href="{{ route('practitioners.index', ['profession' => 'perawat']) }}" class="klinika-card p-4 hover:border-teal-600/40 transition-colors {{ $profession === 'perawat' ? 'ring-1 ring-[#087F83]' : '' }}">
            <p class="text-xs klinika-muted font-medium">Perawat</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['perawat'] }}</p>
            <p class="text-[11px] klinika-muted mt-1">Kelompok keperawatan</p>
        </a>
        <a href="{{ route('practitioners.index', ['profession' => 'apoteker']) }}" class="klinika-card p-4 hover:border-teal-600/40 transition-colors {{ $profession === 'apoteker' ? 'ring-1 ring-[#087F83]' : '' }}">
            <p class="text-xs klinika-muted font-medium">Apoteker</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $stats['apoteker'] }}</p>
            <p class="text-[11px] klinika-muted mt-1">Kelompok farmasi</p>
        </a>
        <div class="klinika-card p-4 col-span-2 lg:col-span-1">
            <p class="text-xs klinika-muted font-medium">Total Poli Tersedia</p>
            <p class="text-2xl font-bold mt-1" style="color:var(--klinika-text)">{{ $polis->count() }}</p>
            <a href="{{ route('polis.index') }}" class="text-[11px] font-semibold klinika-primary-text hover:underline mt-1 inline-block">Kelola poli →</a>
        </div>
    </div>

    {{-- Filter & Table --}}
    <div class="klinika-card overflow-hidden">
        <div class="p-5 border-b klinika-border">
            <form method="GET" action="{{ route('practitioners.index') }}" class="flex flex-col lg:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama, no. STR, no. SIP, atau ID SATUSEHAT..."
                           class="w-full pl-9 pr-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none"
                           style="color:var(--klinika-text)">
                </div>

                <div class="w-full lg:w-44">
                    <select name="profession"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none"
                            style="color:var(--klinika-text)">
                        <option value="">Semua profesi</option>
                        @foreach($professions as $value => $label)
                            <option value="{{ $value }}" @selected($profession === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <select name="poli_id"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none"
                            style="color:var(--klinika-text)">
                        <option value="">Semua poli</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}" @selected((string) $poliId === (string) $poli->id)>{{ $poli->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full lg:w-40">
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

                @if($search !== '' || $profession !== '' || ($poliId !== null && $poliId !== '') || $status !== '')
                    <a href="{{ route('practitioners.index') }}"
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
                        <th class="py-3 px-5 text-left">Tenaga Kesehatan</th>
                        <th class="py-3 px-5 text-left">Profesi</th>
                        <th class="py-3 px-5 text-left">Poli Terhubung</th>
                        <th class="py-3 px-5 text-left">STR / SIP</th>
                        <th class="py-3 px-5 text-left">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y klinika-border">
                    @forelse($practitioners as $practitioner)
                        @php
                            $linkedPolis = $practitioner->polis->unique('id');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="font-semibold text-xs" style="color:var(--klinika-text)">{{ $practitioner->name }}</div>
                                <div class="text-[11px] klinika-muted mt-0.5">
                                    {{ $practitioner->user ? 'Akun: ' . $practitioner->user->email : 'Belum ditautkan ke akun login' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-5 text-xs">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#EEF9F9;color:var(--klinika-primary)">
                                    {{ $practitioner->profession_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-xs">
                                @if($linkedPolis->isEmpty())
                                    <span class="klinika-muted">Belum terhubung ke poli</span>
                                @else
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($linkedPolis as $p)
                                            <a href="{{ route('polis.show', $p) }}"
                                               class="px-2 py-0.5 rounded border klinika-border text-[11px] font-medium hover:bg-slate-50"
                                               style="color:var(--klinika-text)">
                                                {{ $p->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-xs klinika-muted">
                                <div>STR: {{ $practitioner->str_number ?: '—' }}</div>
                                <div>SIP: {{ $practitioner->sip_number ?: '—' }}</div>
                            </td>
                            <td class="py-3.5 px-5 text-xs">
                                @if($practitioner->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#dcfce7;color:#166534">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-right text-xs">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('practitioners.show', $practitioner) }}" class="font-semibold klinika-primary-text hover:underline">Detail</a>
                                    <a href="{{ route('practitioners.edit', $practitioner) }}" class="font-semibold klinika-muted hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('practitioners.toggle-status', $practitioner) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="font-semibold hover:underline" style="color:{{ $practitioner->is_active ? '#96611C' : '#087F83' }}">
                                            {{ $practitioner->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs klinika-muted">Belum ada data tenaga kesehatan yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($practitioners->hasPages())
            <div class="px-5 py-3 border-t klinika-border" style="background:var(--klinika-bg)">
                {{ $practitioners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
