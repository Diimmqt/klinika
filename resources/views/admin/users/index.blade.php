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
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Akun pengguna</h2>
            <p class="text-sm klinika-muted mt-1">
                Kelola akun pengguna Klinik Sehat Sentosa • KSS-01. Buat akun langsung, tetapkan peran awal, dan ubah status tanpa alur aktivasi email.
            </p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Tambah pengguna</span>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Akun --}}
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Total akun</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">{{ $stats['total'] }}</div>
            <p class="text-xs klinika-muted mt-1">Klinik Sehat Sentosa • KSS-01</p>
        </div>

        {{-- Aktif --}}
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Aktif</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-primary)">{{ $stats['active'] }}</div>
            <p class="text-xs klinika-muted mt-1">Siap mengakses aplikasi</p>
        </div>

        {{-- Nonaktif --}}
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Nonaktif</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-muted)">{{ $stats['inactive'] }}</div>
            <p class="text-xs klinika-muted mt-1">Riwayat tetap tersimpan</p>
        </div>

        {{-- Super Admin --}}
        <div class="p-5 klinika-card">
            <span class="text-xs font-medium klinika-muted">Super Admin</span>
            <div class="mt-2 text-3xl font-bold" style="color:var(--klinika-text)">{{ $stats['super_admin'] }}</div>
            <p class="text-xs klinika-muted mt-1">Akses penuh klinik ini</p>
        </div>
    </div>

    {{-- Main Card: Daftar Akun --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-6 py-4 border-b klinika-border">
            <h3 class="font-bold text-base" style="color:var(--klinika-text)">Daftar akun</h3>
            <p class="text-xs klinika-muted mt-0.5">
                Status Aktif/Nonaktif mengatur akses aplikasi tanpa menghapus riwayat klinis. Super Admin terakhir tidak dapat dinonaktifkan.
            </p>
        </div>

        {{-- Filter Row (Nama/Username, Peran, Status) --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="px-6 py-4 border-b klinika-border grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Cari pengguna</label>
                <input type="text" name="search" id="search" value="{{ $search }}"
                       placeholder="Nama atau username"
                       class="w-full px-3.5 py-2 rounded-md border text-sm outline-none transition-colors"
                       style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                       onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
            </div>

            <div>
                <label for="role" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Peran</label>
                <select name="role" id="role" onchange="this.form.submit()"
                        class="w-full px-3.5 py-2 rounded-md border text-sm outline-none transition-colors"
                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                        onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    <option value="">Semua peran</option>
                    @php
                        $roleLabels = [
                            'super_admin' => 'Super Admin',
                            'admin_klinik' => 'Admin Klinik',
                            'pendaftaran' => 'Pendaftaran',
                            'dokter' => 'Dokter',
                            'perawat' => 'Perawat',
                            'kasir' => 'Kasir',
                            'apoteker' => 'Apoteker',
                            'manajemen' => 'Manajemen',
                        ];
                    @endphp
                    @foreach ($roles as $r)
                        <option value="{{ $r->name }}" {{ $role === $r->name ? 'selected' : '' }}>
                            {{ $roleLabels[$r->name] ?? ucwords(str_replace('_', ' ', $r->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Status</label>
                <select name="status" id="status" onchange="this.form.submit()"
                        class="w-full px-3.5 py-2 rounded-md border text-sm outline-none transition-colors"
                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                        onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    <option value="">Semua status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                        <th class="py-3 px-6">Pengguna</th>
                        <th class="py-3 px-6">Peran</th>
                        <th class="py-3 px-6">Status akun</th>
                        <th class="py-3 px-6">Login terakhir</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
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
                            <td class="py-3.5 px-6">
                                <a href="{{ route('admin.users.show', $u) }}" class="font-bold text-sm hover:underline" style="color:var(--klinika-text)">
                                    {{ $u->name }}
                                </a>
                                <span class="text-xs klinika-muted block mt-0.5">{{ $u->email }}</span>
                            </td>

                            {{-- Peran --}}
                            <td class="py-3.5 px-6 text-sm" style="color:var(--klinika-text)">
                                {{ $roleText }}
                            </td>

                            {{-- Status Akun --}}
                            <td class="py-3.5 px-6">
                                @if ($u->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold border"
                                          style="background:#ECFDF5;color:#047857;border-color:#A7F3D0">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold border"
                                          style="background:#F1F5F9;color:#64748B;border-color:#CBD5E1">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Login Terakhir --}}
                            <td class="py-3.5 px-6 text-xs text-slate-700">
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
                            <td class="py-3.5 px-6 text-right space-y-1">
                                <div>
                                    <a href="{{ route('admin.users.show', $u) }}"
                                       class="text-xs font-semibold hover:underline inline-flex items-center gap-1"
                                       style="color:var(--klinika-primary)">
                                        <span>Detail</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="text-xs font-medium text-slate-600 hover:underline">
                                        Edit
                                    </a>
                                </div>
                                @if(!$isSuper && $u->id !== auth()->id())
                                    @if($u->is_active)
                                        <div>
                                            <a href="{{ route('admin.users.deactivate', $u) }}"
                                               class="text-xs font-medium text-rose-600 hover:underline">
                                                Nonaktifkan
                                            </a>
                                        </div>
                                    @else
                                        <div>
                                            <form method="POST" action="{{ route('admin.users.update', $u) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $u->name }}">
                                                <input type="hidden" name="email" value="{{ $u->email }}">
                                                <input type="hidden" name="is_active" value="1">
                                                <button type="submit" class="text-xs font-semibold text-emerald-600 hover:underline">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-xs klinika-muted">
                                Tidak ada akun yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div class="px-6 py-3.5 border-t klinika-border flex items-center justify-between flex-wrap gap-2 text-xs klinika-muted">
            <div>
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} akun • {{ $users->perPage() }} per halaman
            </div>

            @if ($users->hasPages())
                <div class="flex items-center gap-3">
                    @if ($users->onFirstPage())
                        <span class="text-slate-400 cursor-not-allowed">Sebelumnya</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="hover:underline" style="color:var(--klinika-primary)">Sebelumnya</a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="px-2.5 py-1 rounded font-semibold text-white klinika-primary">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2 py-1 text-slate-600 hover:underline">{{ $page }}</a>
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
                    <span class="px-2.5 py-1 rounded font-semibold text-white klinika-primary">1</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Cyan Info Banner --}}
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

    {{-- Page Footer Note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
