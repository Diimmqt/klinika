@extends('layouts.app')

@section('title', 'Tambah Tenaga Kesehatan')
@section('page-title', 'Tambah tenaga kesehatan')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('practitioners.index') }}" class="hover:underline klinika-primary-text">Tenaga kesehatan</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Tambah</span>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Tambah Tenaga Kesehatan</h2>
        <p class="text-sm klinika-muted mt-0.5">Tambahkan data dokter, perawat, atau apoteker dan hubungkan dengan unit poli terkait.</p>
    </div>

    <div class="klinika-card p-6">
        <form method="POST" action="{{ route('practitioners.store') }}" class="space-y-5">
            @csrf
            @include('practitioners._form')
        </form>
    </div>
</div>
@endsection
