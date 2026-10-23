@extends('layouts.app')

@section('title', 'Administrasi Sistem')
@section('page-title', 'Administrasi Sistem')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Administrasi sistem</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Administrasi sistem</h2>
            <p class="text-sm klinika-muted mt-1">Pantau klinik, pengguna, dan keamanan lintas organisasi.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Kelola pengguna</span>
        </a>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Klinik terdaftar</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">{{ $stats['klinik'] }}</div>
            <p class="text-xs klinika-muted mt-1">{{ $stats['klinik'] }} klinik aktif</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Pengguna aktif</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-primary)">{{ $stats['users'] }}</div>
            <p class="text-xs klinika-muted mt-1">{{ $stats['roles'] }} kelompok akses</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Aktivitas hari ini</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">—</div>
            <p class="text-xs klinika-muted mt-1">Semua perubahan tercatat</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Permintaan akses</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">{{ $stats['pending'] }}</div>
            <p class="text-xs klinika-muted mt-1">Menunggu persetujuan</p>
        </div>
    </div>

    {{-- Warning banner --}}
    @if($stats['pending'] > 0)
    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#FFF8ED;border-color:#F5D9A8">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold" style="color:#96611C">{{ $stats['pending'] }} permintaan akses perlu ditinjau</p>
            <p class="text-xs mt-0.5" style="color:#B07A30">Akun baru tidak mendapat izin klinis sebelum role dan klinik ditetapkan.</p>
        </div>
    </div>
    @endif

    {{-- Clinic table --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border">
            <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Klinik dalam sistem</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-semibold uppercase tracking-wider klinika-muted" style="border-bottom:1px solid var(--klinika-border);background:#F4F7F8">
                        <th class="py-3.5 px-5">Klinik</th>
                        <th class="py-3.5 px-5">Kode</th>
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm" style="border-color:var(--klinika-border)">
                    @foreach ($clinics as $clinic)
                    <tr class="hover:bg-[#F4F7F8]/60">
                        <td class="py-4 px-5">
                            <span class="font-semibold" style="color:var(--klinika-text)">{{ $clinic['name'] }}</span>
                            <span class="klinika-muted"> • {{ $clinic['city'] }}</span>
                        </td>
                        <td class="py-4 px-5 text-sm klinika-muted">{{ $clinic['code'] }}</td>
                        <td class="py-4 px-5 text-sm klinika-muted">{{ $clinic['users'] }} akun</td>
                        <td class="py-4 px-5">
                            <span class="text-xs font-medium px-2 py-0.5 rounded" style="background:#ecfdf5;color:#065f46">{{ $clinic['status'] }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm font-medium" style="color:var(--klinika-primary)">
                                Kelola
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t klinika-border flex items-center justify-between">
            <span class="text-xs klinika-muted">Menampilkan 1–{{ count($clinics) }} dari {{ count($clinics) }} data simulasi</span>
            <div class="flex items-center gap-3 text-xs klinika-muted">
                <span>Sebelumnya</span>
                <span class="font-semibold" style="color:var(--klinika-text)">1</span>
                <span>Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Bottom two panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-base mb-3" style="color:var(--klinika-text)">Audit aktivitas terakhir</h3>
            <p class="text-sm klinika-muted italic">Belum ada data aktivitas tercatat.</p>
        </div>
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-base mb-3" style="color:var(--klinika-text)">Kebijakan akses</h3>
            <p class="text-sm klinika-muted italic">Konfigurasi kebijakan akses belum tersedia.</p>
        </div>
    </div>

</div>
@endsection
