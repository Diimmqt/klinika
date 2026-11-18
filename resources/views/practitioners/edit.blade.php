@extends('layouts.app')

@section('title', 'Edit Tenaga Kesehatan - ' . $practitioner->name)
@section('page-title', 'Edit tenaga kesehatan')

@section('breadcrumb')
    <span class="klinika-muted">Admin Klinik</span>
    <span class="mx-1">/</span>
    <a href="{{ route('practitioners.index') }}" class="hover:underline klinika-primary-text">Tenaga kesehatan</a>
    <span class="mx-1">/</span>
    <a href="{{ route('practitioners.show', $practitioner) }}" class="hover:underline klinika-primary-text">{{ $practitioner->name }}</a>
    <span class="mx-1">/</span>
    <span style="color:var(--klinika-text)">Edit</span>
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Edit Tenaga Kesehatan</h2>
        <p class="text-sm klinika-muted mt-0.5">Perbarui data profesi, kredensial, status aktif, atau keterhubungan poli.</p>
    </div>

    <div class="klinika-card p-6">
        <form method="POST" action="{{ route('practitioners.update', $practitioner) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('practitioners._form', ['practitioner' => $practitioner])
        </form>
    </div>
</div>
@endsection
