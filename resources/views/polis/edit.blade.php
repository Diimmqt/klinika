@extends('layouts.app')

@section('title', 'Edit Data Poli - ' . $poli->name)
@section('page-title', 'Edit poli')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('polis.index') }}" class="hover:underline klinika-primary-text">Data poli</a>
    <span class="mx-1">/</span>
    <a href="{{ route('polis.show', $poli) }}" class="hover:underline klinika-primary-text">{{ $poli->name }}</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Edit</span>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Edit Data Poli</h2>
        <p class="text-sm klinika-muted mt-0.5">Perbarui informasi kode, nama poli, awalan antrean, atau status keaktifan poli.</p>
    </div>

    <div class="klinika-card p-6">
        <form method="POST" action="{{ route('polis.update', $poli) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('polis._form', ['poli' => $poli])
        </form>
    </div>
</div>
@endsection
