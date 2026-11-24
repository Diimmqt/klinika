@extends('layouts.landing')

@section('title', 'KLINIKA — Pelayanan Kesehatan Lebih Terintegrasi')
@section('description', 'Pelayanan Kesehatan Lebih Terintegrasi, Dimulai dari KLINIKA. Satukan pendaftaran pasien, Rekam Medis Elektronik (RME), manajemen antrean, farmasi, dan kasir dalam satu ruang kerja.')

@section('content')
    <x-landing.navbar
        :has-login-route="$hasLoginRoute"
        :has-register-route="$hasRegisterRoute"
    />

    <main id="konten-utama">
        <x-landing.hero :has-login-route="$hasLoginRoute" />

        <x-landing.role-strip :team-roles="$teamRoles" />

        <x-landing.features :features="$features" />

        <x-landing.care-journey :journey-steps="$journeySteps" />

        <x-landing.dashboard-preview :workspace-matrix="$workspaceMatrix" />

        <x-landing.satusehat :satusehat-pillars="$satusehatPillars" />

        <x-landing.advantages :advantages="$advantages" />

        <x-landing.faq :faqs="$faqs" />

        <x-landing.contact :contact-info="$contactInfo" />

        <x-landing.cta-banner :has-login-route="$hasLoginRoute" />
    </main>

    <x-landing.footer
        :has-login-route="$hasLoginRoute"
        :has-register-route="$hasRegisterRoute"
        :contact-info="$contactInfo"
    />
@endsection
