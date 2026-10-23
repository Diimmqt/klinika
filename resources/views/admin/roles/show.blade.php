@extends('layouts.app')

@section('title', 'Detail Role')
@section('page-title', 'Detail Role')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.roles.index') }}" class="text-xs" style="color:var(--klinika-primary)">← Kembali</a>
            <h2 class="text-2xl font-bold tracking-tight mt-1 font-mono" style="color:var(--klinika-text)">{{ $role->name }}</h2>
            <p class="text-sm klinika-muted">{{ $role->description ?? 'Tanpa deskripsi' }}</p>
        </div>
        <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 rounded-md text-white text-sm font-medium klinika-primary">Edit</a>
    </div>

    <div class="klinika-card p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-xs klinika-muted block">Jumlah User</span>
                <span class="font-semibold" style="color:var(--klinika-text)">{{ $role->users_count }}</span>
            </div>
            <div>
                <span class="text-xs klinika-muted block">Jumlah Permission</span>
                <span class="font-semibold" style="color:var(--klinika-primary)">{{ $role->permissions->count() }}</span>
            </div>
        </div>

        <div class="border-t pt-4" style="border-color:var(--klinika-border)">
            <h3 class="text-xs font-bold uppercase tracking-wider klinika-muted mb-2">Permission</h3>
            <div class="flex flex-wrap gap-1.5">
                @forelse ($role->permissions as $p)
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded" style="background:#e6f5f5;color:var(--klinika-primary)">{{ $p->name }}</span>
                @empty
                    <span class="text-sm klinika-muted italic">Belum ada permission</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
