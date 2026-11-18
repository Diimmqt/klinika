@extends('layouts.app')

@section('title', 'Tambah Data Poli')
@section('page-title', 'Tambah poli')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('polis.index') }}" class="hover:underline klinika-primary-text">Data poli</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Tambah</span>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Tambah Data Poli</h2>
        <p class="text-sm klinika-muted mt-0.5">Tambahkan unit layanan poli baru lengkap dengan kode poli dan prefix antrean.</p>
    </div>

    <div class="klinika-card p-6">
        <form method="POST" action="{{ route('polis.store') }}" class="space-y-5">
            @csrf
            @include('polis._form')
        </form>
    </div>
</div>
@endsection
