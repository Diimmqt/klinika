@extends('layouts.app')

@section('title', 'Tambah Akun Pengguna')
@section('page-title', 'Daftar akun')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Daftar akun</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Akun pengguna</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Tambah pengguna</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Tambah akun pengguna</h2>
        <p class="text-sm klinika-muted mt-1">Buat akun melalui undangan email. Pengguna mengatur kata sandinya sendiri.</p>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" id="form-tambah-akun">
        @csrf

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- LEFT: Main form --}}
            <div class="flex-1 space-y-4 min-w-0">

                {{-- Identitas pengguna --}}
                <div class="klinika-card p-6 space-y-4">
                    <div>
                        <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Identitas pengguna</h3>
                        <p class="text-xs klinika-muted mt-0.5">Gunakan email unik milik pengguna. Tidak ada kata sandi yang ditampilkan atau dikirim.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Nama lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Nadia Putri"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors focus:ring-2"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   placeholder="nama@klinika.test"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                            <p class="text-[11px] klinika-muted mt-1">Undangan dikirim ke alamat ini.</p>
                        </div>
                    </div>
                </div>

                {{-- Penempatan dan akses --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Penempatan dan akses</h3>

                    {{-- Klinik --}}
                    <div>
                        <label for="klinik" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Klinik <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="klinik" name="klinik_id"
                                    class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                    style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
                                <option value="KSS-01">Klinik Sehat Sentosa • Bandung (KSS-01)</option>
                                <option value="KBS-02">Bina Sehat • Cimahi (KBS-02)</option>
                                <option value="KMU-03">Medika Utama • Garut (KMU-03)</option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <p class="text-[11px] klinika-muted mt-1">Scope hanya KSS-01; bukan akses ke seluruh organisasi.</p>
                    </div>

                    {{-- Role & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role_select" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Role <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="role_select" name="roles[]"
                                        class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                        onchange="updateRoleSummary(this.value)">
                                    <option value="">-- Pilih role --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}" {{ old('roles.0') === $r->name ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Status awal</label>
                            <input type="text" value="Menunggu aktivasi" disabled
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm klinika-muted"
                                   style="border-color:var(--klinika-border);background:#F4F7F8">
                        </div>
                    </div>

                    {{-- Role summary box --}}
                    <div id="role-summary-box" class="hidden rounded-md border p-3.5" style="background:#EEF9F9;border-color:#B2DEDE">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Ringkasan akses <span id="role-summary-name"></span></p>
                                <p class="text-xs mt-0.5" style="color:var(--klinika-primary)" id="role-summary-desc">—</p>
                            </div>
                        </div>
                    </div>

                    {{-- Send invitation checkbox --}}
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input type="checkbox" id="send_invitation" name="send_invitation" value="1" checked
                               class="w-4 h-4 rounded" style="accent-color:var(--klinika-primary)">
                        <span class="text-sm" style="color:var(--klinika-text)">Kirim undangan aktivasi melalui email setelah akun dibuat</span>
                    </label>
                </div>

                {{-- Form footer --}}
                <div class="flex items-center justify-between py-1">
                    <span class="text-xs klinika-muted">* Wajib diisi</span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors"
                           style="border-color:var(--klinika-border);color:var(--klinika-text)">
                            Kembali
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                            Buat &amp; kirim undangan
                        </button>
                    </div>
                </div>

            </div>{{-- end left --}}

            {{-- RIGHT: Alur aktivasi sidebar --}}
            <div class="w-full lg:w-72 shrink-0 space-y-4">

                <div class="klinika-card p-5">
                    <h3 class="font-semibold text-sm mb-4" style="color:var(--klinika-text)">Alur aktivasi</h3>

                    {{-- Status badge --}}
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded mb-4" style="background:#FFF3CD;color:#856404">
                        Menunggu aktivasi
                    </span>

                    {{-- Steps --}}
                    <ol class="space-y-4">
                        <li class="flex gap-3">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5" style="background:var(--klinika-primary)">1</div>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Undangan terkirim</p>
                                <p class="text-[11px] klinika-muted">Tautan sekali pakai berlaku 24 jam.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5" style="background:var(--klinika-primary)">2</div>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Pengguna mengatur sandi</p>
                                <p class="text-[11px] klinika-muted">Validasi sandi kuat dan konfirmasi sandi.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5" style="background:var(--klinika-primary)">3</div>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Akun menjadi Aktif</p>
                                <p class="text-[11px] klinika-muted">Login baru tersedia setelah aktivasi selesai.</p>
                            </div>
                        </li>
                    </ol>

                    <p class="text-[11px] klinika-muted mt-4 pt-4 border-t" style="border-color:var(--klinika-border)">
                        Undangan kedaluwarsa? Kirim ulang dari detail akun; tautan lama dibatalkan.
                    </p>
                </div>

                {{-- Warning: belum bisa login --}}
                <div class="rounded-lg border p-4" style="background:#FFF8ED;border-color:#F5D9A8">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#96611C">Belum dapat login</p>
                            <p class="text-[11px] mt-0.5" style="color:#B07A30">Menunggu aktivasi bukan akun Aktif. Pengguna belum memiliki sesi atau akses aplikasi.</p>
                        </div>
                    </div>
                </div>

            </div>{{-- end sidebar --}}

        </div>{{-- end flex --}}

    </form>

    {{-- Hasil setelah berhasil dibuat (shown after success) --}}
    @if(session('created_user'))
    <div class="klinika-card p-5">
        <h3 class="font-semibold text-sm mb-3" style="color:var(--klinika-text)">Hasil setelah berhasil dibuat</h3>
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold" style="color:var(--klinika-text)">
                    {{ session('created_user')['name'] }} • {{ session('created_user')['email'] }}
                </p>
                <p class="text-xs klinika-muted mt-0.5">
                    {{ session('created_user')['role'] ?? '—' }} • KSS-01 • Undangan berlaku hingga {{ session('created_user')['expires'] ?? '—' }}
                </p>
                <p class="text-xs mt-1" style="color:var(--klinika-primary)">
                    ✓ Akun dibuat. Undangan aktivasi berhasil dikirim; pengguna belum dapat login.
                </p>
            </div>
            <span class="text-xs font-semibold shrink-0" style="color:#C4871A">Menunggu aktivasi</span>
        </div>
    </div>
    @endif

    {{-- Footer note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection

@push('scripts')
<script>
const roleSummaries = {
    'super_admin':   'Akses penuh ke seluruh sistem termasuk manajemen role dan klinik.',
    'Pendaftaran':   'Pasien, kunjungan, dan antrean di KSS-01. Tanpa isi SOAP, resep, pembayaran, akun, atau manajemen role.',
    'Dokter':        'Pemeriksaan, SOAP, dan resep di KSS-01. Tanpa manajemen akun atau pembayaran.',
    'Kasir':         'Pembayaran dan kwitansi di KSS-01. Tanpa akses rekam medis atau manajemen akun.',
    'Farmasi':       'Pengelolaan obat dan dispensing di KSS-01. Tanpa akses SOAP atau pembayaran.',
    'Perawat':       'Asesmen awal dan pemeriksaan vital di KSS-01. Tanpa resep atau pembayaran.',
};

function updateRoleSummary(role) {
    const box  = document.getElementById('role-summary-box');
    const name = document.getElementById('role-summary-name');
    const desc = document.getElementById('role-summary-desc');
    if (role && roleSummaries[role]) {
        name.textContent = role;
        desc.textContent = roleSummaries[role];
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
}

// Trigger on load if old value was selected
const sel = document.getElementById('role_select');
if (sel && sel.value) updateRoleSummary(sel.value);
</script>
@endpush
