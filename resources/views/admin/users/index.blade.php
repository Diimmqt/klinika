@extends('layouts.app')

@section('title', 'Akun pengguna')
@section('page-title', 'Klinik & pengguna / Akun pengguna')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Akun pengguna</h2>
            <p class="text-sm klinika-muted mt-1">Kelola identitas, penempatan klinik, dan akses pengguna lintas organisasi.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah akun</span>
        </a>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Total akun</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">{{ number_format($stats['total']) }}</div>
            <p class="text-xs klinika-muted mt-1">3 klinik terdaftar</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Aktif</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-primary)">{{ number_format($stats['active']) }}</div>
            <p class="text-xs klinika-muted mt-1">Dapat mengakses sesuai izin</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Menunggu aktivasi</span>
            <div class="mt-2 text-3xl font-bold" style="color:#C4871A">{{ number_format($stats['pending'] ?? 0) }}</div>
            <p class="text-xs klinika-muted mt-1">Undangan belum diselesaikan</p>
        </div>
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Nonaktif</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-muted)">{{ number_format($stats['inactive']) }}</div>
            <p class="text-xs klinika-muted mt-1">Riwayat tetap tersimpan</p>
        </div>
    </div>

    {{-- Filter + Table --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border">
            <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Daftar akun</h3>
            <p class="text-xs klinika-muted mt-0.5">Status Aktif tidak membuka akses di luar role dan scope klinik yang ditetapkan.</p>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="px-5 py-4 border-b klinika-border grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">Cari pengguna</label>
                <input type="text" name="search" id="search-users" value="{{ $search }}" placeholder="Nama atau email"
                       class="w-full px-3 py-2 rounded-md border text-sm focus:outline-none focus:ring-2"
                       style="border-color:var(--klinika-border);background:#F4F7F8">
            </div>
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">Role</label>
                <select name="role" id="filter-role" class="w-full px-3 py-2 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
                    <option value="">Semua role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->name }}" {{ $role === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">Klinik</label>
                <select name="klinik" id="filter-klinik" class="w-full px-3 py-2 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
                    <option value="">Semua klinik</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium klinika-muted mb-1">Status</label>
                <select name="status" id="filter-status" class="w-full px-3 py-2 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
                    <option value="">Semua status</option>
                    <option value="active"   {{ $status === 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-semibold uppercase tracking-wider klinika-muted border-b" style="border-color:var(--klinika-border);background:#F4F7F8">
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-5">Role</th>
                        <th class="py-3.5 px-5">Klinik / scope</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Login terakhir</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm" style="border-color:var(--klinika-border)">
                    @forelse ($users as $u)
                    <tr class="hover:bg-[#F4F7F8]/60">
                        <td class="py-4 px-5">
                            <a href="{{ route('admin.users.show', $u) }}" class="font-semibold hover:opacity-80" style="color:var(--klinika-text)">{{ $u->name }}</a>
                            <div class="text-xs klinika-muted mt-0.5">{{ $u->email }}</div>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($u->roles as $r)
                                    <span class="text-xs font-medium">{{ $r->name }}</span>
                                @empty
                                    <span class="text-xs klinika-muted italic">Belum ada role</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="py-4 px-5 text-sm klinika-muted">Seluruh organisasi</td>
                        <td class="py-4 px-5">
                            @if ($u->is_active)
                                <span class="text-xs font-semibold px-2 py-0.5 rounded" style="background:#ecfdf5;color:#065f46">Aktif</span>
                            @else
                                <span class="text-xs font-semibold px-2 py-0.5 rounded" style="background:#F4F7F8;color:var(--klinika-muted)">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-xs klinika-muted">
                            {{ $u->last_login_at ? $u->last_login_at->format('d M, H.i') : 'Belum login' }}
                        </td>
                        <td class="py-4 px-5 text-right space-x-2">
                            <a href="{{ route('admin.users.show', $u) }}" class="text-sm font-medium" style="color:var(--klinika-primary)">Detail →</a>
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-sm klinika-muted">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <p class="font-semibold text-sm" style="color:var(--klinika-text)">Belum ada pengguna</p>
                            <a href="{{ route('admin.users.create') }}" class="inline-block mt-3 text-xs font-semibold" style="color:var(--klinika-primary)">+ Tambah akun</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="px-5 py-3 border-t klinika-border flex items-center justify-between flex-wrap gap-2">
            <span class="text-xs klinika-muted">Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} akun • 8 per halaman</span>
            @if ($users->hasPages())
                <div class="flex items-center gap-3 text-xs klinika-muted">
                    @if ($users->onFirstPage())
                        <span>Sebelumnya</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="hover:opacity-70" style="color:var(--klinika-primary)">Sebelumnya</a>
                    @endif
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="font-semibold {{ $page == $users->currentPage() ? '' : 'klinika-muted' }}"
                           style="{{ $page == $users->currentPage() ? 'color:var(--klinika-text)' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="hover:opacity-70" style="color:var(--klinika-primary)">Berikutnya</a>
                    @else
                        <span>Berikutnya</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Info footer --}}
    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#EFF8FF;border-color:#BFDBFE">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#3B82F6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold" style="color:#1D4ED8">Akun tidak dihapus permanen</p>
            <p class="text-xs mt-0.5" style="color:#3B82F6">Nonaktifkan akun untuk menghentikan akses. Identitas pada rekam medis, transaksi, dan audit tetap utuh.</p>
        </div>
    </div>

    <div class="text-xs klinika-muted text-center pb-2">
        Data simulasi • Bukan data pasien nyata &nbsp;|&nbsp; Klinika RME • Akses tercatat
    </div>
</div>
@endsection
