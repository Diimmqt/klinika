@extends('layouts.app')

@section('title', 'Data Pasien')

@section('breadcrumb')
    <span>Data pasien</span>
@endsection

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Data pasien</h2>
        <p class="text-sm klinika-muted mt-0.5">Klinik Sehat Sentosa &bull; KSS-01</p>
    </div>
    @can('patient.create')
    <a href="{{ route('patients.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white klinika-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pasien Baru
    </a>
    @endcan
</div>

{{-- 3 Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Pasien terdaftar</p>
        <p class="text-3xl font-bold mt-1" style="color:var(--klinika-primary)">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Pasien baru bulan ini</p>
        <p class="text-3xl font-bold mt-1" style="color:#2563eb">{{ $stats['this_month'] }}</p>
    </div>
    <div class="klinika-card p-4">
        <p class="text-xs klinika-muted font-medium">Kunjungan hari ini</p>
        <p class="text-3xl font-bold mt-1" style="color:#16a34a">64</p>
        {{-- ponytail: static sim — replace when visits table is integrated --}}
    </div>
</div>

{{-- Search + Filter --}}
<div class="klinika-card p-4">
    <form method="GET" action="{{ route('patients.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama, RM, NIK, telepon..."
                   class="w-full pl-9 pr-4 py-2 rounded-lg border klinika-border text-sm focus:outline-none focus:ring-2"
                   style="focus:ring-color:var(--klinika-primary)">
        </div>
        <select name="gender"
                class="px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none"
                onchange="this.form.submit()">
            <option value="">Semua pasien</option>
            <option value="L" {{ $gender === 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ $gender === 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        <select name="sort"
                class="px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none"
                onchange="this.form.submit()">
            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Urut: nama A–Z</option>
            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Urut: nama Z–A</option>
            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
        </select>
        <button type="submit"
                class="px-4 py-2 rounded-lg text-sm font-semibold text-white klinika-primary transition-colors">
            Cari
        </button>
    </form>
</div>

{{-- Table --}}
<div class="klinika-card overflow-hidden">
    @if ($patients->isEmpty())
        <div class="px-5 py-12 text-center">
            <svg class="w-10 h-10 mx-auto klinika-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm klinika-muted">Tidak ada pasien ditemukan.</p>
            @if ($search)
                <a href="{{ route('patients.index') }}" class="text-xs klinika-primary-text mt-1 inline-block hover:underline">Reset pencarian</a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Nomor RM / Pasien</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Tanggal lahir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Kunjungan terakhir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @foreach ($patients as $patient)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-sm" style="color:var(--klinika-text)">{{ $patient->name }}</div>
                            <div class="text-xs font-mono klinika-muted">{{ $patient->medical_record_number }}</div>
                        </td>
                        <td class="px-4 py-3 text-xs font-mono klinika-muted">{{ $patient->nik ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="text-xs" style="color:var(--klinika-text)">
                                {{ $patient->birth_date?->translatedFormat('j M Y') ?? '—' }}
                            </div>
                            <div class="text-xs klinika-muted">
                                {{ $patient->gender_label }}
                                @if ($patient->age) &bull; {{ $patient->age }} th @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs klinika-muted">{{ $patient->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs klinika-muted">
                            {{-- ponytail: static sim — replace when visits table is integrated --}}
                            <span class="italic">Belum ada kunjungan</span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('patients.show', $patient) }}"
                               class="inline-flex items-center gap-1 text-xs font-medium klinika-primary-text hover:underline">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t klinika-border flex flex-col sm:flex-row items-center justify-between gap-2">
            <p class="text-xs klinika-muted">
                Menampilkan {{ $patients->firstItem() }}–{{ $patients->lastItem() }} dari {{ $patients->total() }} pasien
            </p>
            {{ $patients->links() }}
        </div>
    @endif
</div>

<p class="text-center text-xs klinika-muted pt-2">Data simulasi &bull; Bukan data pasien nyata | Klinika RME &bull; Akses tercatat</p>
@endsection
