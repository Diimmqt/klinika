@extends('layouts.app')

@section('title', 'Akun Pengguna')
@section('page-title', 'Daftar akun')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Daftar akun</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Akun pengguna</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Akun pengguna</h2>
            <p class="text-sm klinika-muted mt-1">
                Kelola akun pengguna Klinik Sehat Sentosa • KSS-01. Buat akun langsung, tetapkan peran awal, dan ubah status tanpa alur aktivasi email.
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2.5 rounded-md text-white text-sm font-medium klinika-primary hover:opacity-90 transition-opacity shrink-0 text-center">
            + Tambah pengguna
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-lg border flex items-center justify-between" style="background:#ECFDF5;border-color:#A7F3D0;color:#065F46">
        <div class="flex items-center gap-2.5 text-xs font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-lg border flex items-center justify-between" style="background:#FFF1F2;border-color:#FECDD3;color:#9F1239">
        <div class="flex items-center gap-2.5 text-xs font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- Stats Cards (4) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total akun</p>
            <p class="text-3xl font-bold mt-1.5" style="color:var(--klinika-text)">{{ $stats['total'] ?? 24 }}</p>
            <p class="text-xs klinika-muted mt-1">Klinik Sehat Sentosa • KSS-01</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Aktif</p>
            <p class="text-3xl font-bold mt-1.5" style="color:var(--klinika-text)">{{ $stats['active'] ?? 22 }}</p>
            <p class="text-xs klinika-muted mt-1">Siap mengakses aplikasi</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Nonaktif</p>
            <p class="text-3xl font-bold mt-1.5" style="color:var(--klinika-text)">{{ $stats['inactive'] ?? 2 }}</p>
            <p class="text-xs klinika-muted mt-1">Riwayat tetap tersimpan</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Super Admin</p>
            <p class="text-3xl font-bold mt-1.5" style="color:var(--klinika-text)">{{ $stats['super_admin'] ?? 1 }}</p>
            <p class="text-xs klinika-muted mt-1">Akses penuh klinik ini</p>
        </div>
    </div>

    {{-- Main Card: Daftar Akun --}}
    <div class="klinika-card p-6 space-y-4">
        <div>
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Daftar akun</h3>
            <p class="text-xs klinika-muted mt-0.5">
                Status Aktif/Nonaktif mengatur akses aplikasi tanpa menghapus riwayat klinis. Super Admin terakhir tidak dapat dinonaktifkan.
            </p>
        </div>

        {{-- Filter Row --}}
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6">
                    <label for="search" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Cari pengguna</label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                           placeholder="Nama atau username"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                </div>

                <div class="md:col-span-3">
                    <label for="role" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Peran</label>
                    <select name="role" id="role" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="">Semua peran</option>
                        @php
                            $roleLabels = [
                                'super_admin'  => 'Super Admin',
                                'admin_klinik' => 'Admin Klinik',
                                'pendaftaran'  => 'Pendaftaran',
                                'dokter'       => 'Dokter',
                                'perawat'      => 'Perawat',
                                'kasir'        => 'Kasir',
                                'apoteker'     => 'Apoteker',
                                'manajemen'    => 'Manajemen',
                            ];
                        @endphp
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}" {{ $role === $r->name ? 'selected' : '' }}>
                                {{ $roleLabels[$r->name] ?? ucwords(str_replace('_', ' ', $r->name)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label for="status" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Status</label>
                    <select name="status" id="status" onchange="this.form.submit()"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="">Semua status</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto border rounded-lg" style="border-color:var(--klinika-border)">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                        <th class="py-3 px-4 w-1/4">Pengguna</th>
                        <th class="py-3 px-4 w-1/5">Peran</th>
                        <th class="py-3 px-4 w-1/6">Status akun</th>
                        <th class="py-3 px-4 w-1/5">Login terakhir</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse ($users as $u)
                        @php
                            $primaryRole = $u->roles->first()?->name;
                            $roleText = $roleLabels[$primaryRole] ?? ($primaryRole ? ucwords(str_replace('_', ' ', $primaryRole)) : 'Belum ada peran');
                            $isSuper = $u->hasRole('super_admin');
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            {{-- Pengguna --}}
                            <td class="py-3.5 px-4 align-top">
                                <a href="{{ route('admin.users.show', $u) }}" class="font-bold text-xs hover:underline" style="color:var(--klinika-text)">
                                    {{ $u->name }}
                                </a>
                                <span class="text-[11px] klinika-muted block mt-0.5">{{ $u->email }}</span>
                            </td>

                            {{-- Peran --}}
                            <td class="py-3.5 px-4 align-top" style="color:var(--klinika-text)">
                                {{ $roleText }}
                            </td>

                            {{-- Status Akun --}}
                            <td class="py-3.5 px-4 align-top">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold text-slate-600 bg-slate-100 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Login Terakhir --}}
                            <td class="py-3.5 px-4 align-top klinika-muted">
                                @if($u->last_login_at)
                                    @if($u->last_login_at->isToday())
                                        Hari ini, {{ $u->last_login_at->format('H.i') }}
                                    @else
                                        {{ $u->last_login_at->translatedFormat('j M, H.i') }}
                                    @endif
                                @else
                                    Hari ini, 09.00
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-4 align-top">
                                <div class="flex flex-col items-start gap-1">
                                    <a href="{{ route('admin.users.show', $u) }}"
                                       class="font-medium hover:underline inline-flex items-center gap-0.5"
                                       style="color:var(--klinika-primary)">
                                        <span>Detail</span> <span>&rarr;</span>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="font-medium hover:underline"
                                       style="color:var(--klinika-primary)">
                                        Edit
                                    </a>
                                    @if(!$isSuper)
                                        @if($u->is_active)
                                            <a href="{{ route('admin.users.deactivate', $u) }}"
                                               class="font-medium hover:underline"
                                               style="color:var(--klinika-primary)">
                                                Nonaktifkan
                                            </a>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.activate', $u) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="font-medium hover:underline p-0 border-0 bg-transparent text-left cursor-pointer"
                                                        style="color:var(--klinika-primary)">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-14 px-6 text-center">
                                <div class="max-w-md mx-auto">
                                    <h4 class="font-bold text-sm mb-1.5" style="color:var(--klinika-text)">Belum ada pengguna</h4>
                                    <p class="text-xs klinika-muted mb-4 leading-relaxed">
                                        Belum ada akun staf yang dibuat untuk klinik ini. Tambahkan pengguna pertama untuk mulai mengakses aplikasi sesuai perannya.
                                    </p>
                                    <a href="{{ route('admin.users.create') }}"
                                       class="inline-block px-4 py-2 rounded-lg text-white text-xs font-semibold klinika-primary hover:opacity-90 transition-opacity">
                                        + Tambah pengguna
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table footer / Pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs klinika-muted pt-1">
            <div>
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} akun • 8 per halaman
            </div>

            @if ($users->hasPages())
                <div class="flex items-center gap-2">
                    @if ($users->onFirstPage())
                        <span class="text-slate-400 cursor-not-allowed">Sebelumnya</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="hover:underline" style="color:var(--klinika-primary)">Sebelumnya</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-2.5 py-1 rounded font-semibold text-white klinika-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2.5 py-1 rounded hover:bg-slate-100 text-slate-700">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="hover:underline" style="color:var(--klinika-primary)">Berikutnya</a>
                    @else
                        <span class="text-slate-400 cursor-not-allowed">Berikutnya</span>
                    @endif
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="text-slate-400 cursor-not-allowed">Sebelumnya</span>
                    <span class="px-2.5 py-1 rounded font-semibold text-white klinika-primary">1</span>
                    <span class="text-slate-400 cursor-not-allowed">Berikutnya</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Teal Callout Box --}}
    <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Akun tidak dihapus permanen</p>
                <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                    Nonaktifkan akun untuk menghentikan akses. Identitas pada rekam medis, transaksi, dan audit tetap utuh.
                </p>
            </div>
        </div>
    </div>

    {{-- Footer note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
