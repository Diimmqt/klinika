@extends('layouts.app')

@section('title', 'Dashboard Administrasi')
@section('page-title', 'Dashboard administrasi')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Administrasi sistem</span>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Dashboard administrasi</h2>
            <p class="text-sm klinika-muted mt-0.5">Pantau akun, role, master data, dan aktivitas sistem Klinik Sehat Sentosa &bull; Bandung &bull; KSS-01 dalam satu tampilan.</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 rounded-lg klinika-primary text-white text-sm font-semibold hover:opacity-90 transition-opacity shrink-0 text-center">
            Kelola pengguna
        </a>
    </div>

    {{-- Top 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Total akun</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['total_akun'] }}</p>
            <p class="text-xs klinika-muted mt-1">Akun terdaftar</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Role aktif</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['role_aktif'] }}</p>
            <p class="text-xs klinika-muted mt-1">Role yang digunakan</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Aktivitas hari ini</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-primary)">{{ $stats['aktivitas_hari_ini'] }}</p>
            <p class="text-xs klinika-muted mt-1">Log terbaru</p>
        </div>
        <div class="klinika-card p-5">
            <p class="text-xs klinika-muted font-medium">Permintaan akses</p>
            <p class="text-3xl font-bold mt-1" style="color:#C4871A">{{ $stats['permintaan_akses'] }}</p>
            <p class="text-xs klinika-muted mt-1">Menunggu tindak lanjut</p>
        </div>
    </div>

    {{-- 2x2 Grid Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Card 1: Daftar Akun --}}
        <div class="klinika-card p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Daftar akun</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#E6F4F4;color:var(--klinika-primary)">
                    {{ $daftarAkun['total_akun'] }} akun
                </span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Total akun</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $daftarAkun['total_akun'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Akun aktif</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $daftarAkun['akun_aktif'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Permintaan akses</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $daftarAkun['permintaan_akses'] }}</span>
                </div>
            </div>

            <div class="pt-2 border-t klinika-border">
                <p class="text-xs font-semibold mb-2" style="color:var(--klinika-text)">Pengguna terbaru</p>
                <div class="space-y-1.5 text-xs">
                    @foreach($daftarAkun['pengguna_terbaru'] as $user)
                    <div class="flex justify-between py-0.5">
                        <span style="color:var(--klinika-text)">{{ $user['name'] }}</span>
                        <span class="klinika-muted">{{ $user['role'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card 2: Hak Akses --}}
        <div class="klinika-card p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Hak akses</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#E6F4F4;color:var(--klinika-primary)">
                    {{ $hakAkses['role_aktif'] }} role
                </span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Role aktif</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $hakAkses['role_aktif'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Akun terbanyak</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $hakAkses['akun_terbanyak'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Role tunggal</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $hakAkses['role_tunggal'] }}</span>
                </div>
            </div>

            <div class="pt-2 border-t klinika-border">
                <p class="text-xs font-semibold mb-2" style="color:var(--klinika-text)">Role prioritas</p>
                <div class="space-y-1.5 text-xs">
                    @foreach($hakAkses['role_prioritas'] as $rp)
                    <div class="flex justify-between py-0.5">
                        <span style="color:var(--klinika-text)">{{ $rp['name'] }}</span>
                        <span class="klinika-muted">{{ $rp['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card 3: Master Data --}}
        <div class="klinika-card p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Master data</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#E6F4F4;color:var(--klinika-primary)">
                    {{ $masterData['poli'] }} poli
                </span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Poli</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $masterData['poli'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Tenaga kesehatan</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $masterData['tenaga_kesehatan'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Jadwal hari ini</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $masterData['jadwal_hari_ini'] }}</span>
                </div>
            </div>

            <div class="pt-2 border-t klinika-border">
                <p class="text-xs font-semibold mb-2" style="color:var(--klinika-text)">Master prioritas</p>
                <div class="space-y-1.5 text-xs">
                    @foreach($masterData['master_prioritas'] as $mp)
                    <div class="flex justify-between py-0.5">
                        <span style="color:var(--klinika-text)">{{ $mp['name'] }}</span>
                        <span class="klinika-muted">{{ $mp['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card 4: Audit Aktivitas --}}
        <div class="klinika-card p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Audit aktivitas</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#E6F4F4;color:var(--klinika-primary)">
                    {{ $auditAktivitas['aktivitas_hari_ini'] }} aktivitas
                </span>
            </div>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Aktivitas hari ini</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $auditAktivitas['aktivitas_hari_ini'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Upaya login ditolak</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $auditAktivitas['upaya_login_ditolak'] }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="klinika-muted">Terakhir diperbarui</span>
                    <span class="font-medium" style="color:var(--klinika-text)">{{ $auditAktivitas['terakhir_diperbarui'] }}</span>
                </div>
            </div>

            <div class="pt-2 border-t klinika-border">
                <p class="text-xs font-semibold mb-2" style="color:var(--klinika-text)">Aktivitas terbaru</p>
                <div class="space-y-1.5 text-xs">
                    @foreach($auditAktivitas['aktivitas_terbaru'] as $act)
                    <div class="flex justify-between py-0.5">
                        <span style="color:var(--klinika-text)">{{ $act['time'] }}</span>
                        <span class="klinika-muted">{{ $act['desc'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>
@endsection
