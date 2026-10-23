@extends('layouts.app')

@section('title', 'Edit Permission')
@section('page-title', 'Edit Permission')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.permissions.index') }}" class="text-xs" style="color:var(--klinika-primary)">← Kembali</a>
        <h2 class="text-2xl font-bold tracking-tight mt-1" style="color:var(--klinika-text)">Edit Permission</h2>
        <p class="text-sm klinika-muted font-mono">{{ $permission->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="p-6 klinika-card space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Nama Permission <span class="text-rose-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" required
                   class="w-full px-3.5 py-2.5 rounded-md border text-sm font-mono" style="border-color:var(--klinika-border);background:#F4F7F8">
        </div>
        <div>
            <label for="description" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Deskripsi</label>
            <input type="text" id="description" name="description" value="{{ old('description', $permission->description) }}"
                   class="w-full px-3.5 py-2.5 rounded-md border text-sm" style="border-color:var(--klinika-border);background:#F4F7F8">
        </div>
        <div class="flex items-center gap-3 pt-2">
            <a href="{{ route('admin.permissions.index') }}" class="flex-1 py-2.5 text-center rounded-md border text-sm font-medium" style="border-color:var(--klinika-border)">Batal</a>
            <button type="submit" class="flex-1 py-2.5 rounded-md text-white text-sm font-medium klinika-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
