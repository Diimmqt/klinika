@extends('layouts.app')

@section('title', 'Kelola Permission')
@section('page-title', 'Kelola Permission')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Kelola Permission</h2>
            <p class="text-sm klinika-muted mt-1">Daftar hak akses yang dapat dipasang ke role.</p>
        </div>
        <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-md text-white font-medium text-sm klinika-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Permission
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-4 klinika-card">
            <span class="text-xs font-medium klinika-muted">Total Permission</span>
            <div class="mt-2 text-2xl font-bold" style="color:var(--klinika-text)">{{ $stats['total'] }}</div>
        </div>
        <div class="p-4 klinika-card">
            <span class="text-xs font-medium klinika-muted">Modul</span>
            <div class="mt-2 text-2xl font-bold" style="color:var(--klinika-primary)">{{ $stats['modules'] }}</div>
        </div>
    </div>

    <div class="p-5 klinika-card">
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-8">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau deskripsi..."
                       class="w-full px-3.5 py-2.5 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
            </div>
            <div class="md:col-span-3">
                <select name="sort" class="w-full px-3 py-2.5 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </div>
            <div class="md:col-span-1">
                <button type="submit" class="w-full py-2.5 px-4 text-white font-medium text-sm rounded-md" style="background:#182B35">Filter</button>
            </div>
        </form>
    </div>

    <div class="klinika-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b text-[11px] font-semibold uppercase tracking-wider klinika-muted" style="border-color:var(--klinika-border);background:#F4F7F8">
                        <th class="py-3.5 px-5">Nama</th>
                        <th class="py-3.5 px-5">Deskripsi</th>
                        <th class="py-3.5 px-5">Dipakai Role</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm" style="border-color:var(--klinika-border)">
                    @forelse ($permissions as $permission)
                        <tr class="hover:bg-[#F4F7F8]/60">
                            <td class="py-4 px-5 font-mono text-xs font-semibold" style="color:var(--klinika-primary)">{{ $permission->name }}</td>
                            <td class="py-4 px-5 text-sm" style="color:var(--klinika-text)">{{ $permission->description ?? '—' }}</td>
                            <td class="py-4 px-5 text-xs klinika-muted">{{ $permission->roles_count }} role</td>
                            <td class="py-4 px-5 text-right space-x-1">
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="inline-flex px-2.5 py-1.5 rounded-md text-xs font-medium" style="background:#F4F7F8;color:var(--klinika-text)">Edit</a>
                                <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="inline" onsubmit="return confirm('Hapus permission ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex px-2.5 py-1.5 rounded-md text-xs font-medium" style="background:#fff1f2;color:#e11d48">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <p class="font-semibold text-sm" style="color:var(--klinika-text)">Belum ada permission</p>
                                <a href="{{ route('admin.permissions.create') }}" class="inline-block mt-3 text-xs font-semibold" style="color:var(--klinika-primary)">+ Tambah Permission</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($permissions->hasPages())
            <div class="p-4 border-t klinika-border">{{ $permissions->links() }}</div>
        @endif
    </div>
</div>
@endsection
