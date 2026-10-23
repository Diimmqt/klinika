@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')
@section('page-title', 'Klinik & pengguna')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Klinik & pengguna</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Akun pengguna</a>
    <span>/</span>
    <a href="{{ route('admin.users.show', $user) }}" class="hover:opacity-80" style="color:var(--klinika-primary)">{{ $user->name }}</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Edit</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Edit akun pengguna</h2>
        <p class="text-sm klinika-muted mt-1">
            {{ $user->name }} • USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }} • Perubahan belum disimpan.
        </p>
    </div>

    {{-- Error banner --}}
    @if ($errors->any())
    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#FFF1F2;border-color:#FECDD3">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#E11D48">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold" style="color:#9F1239">Periksa kembali {{ $errors->count() }} isian</p>
            <p class="text-xs mt-0.5" style="color:#BE123C">
                {{ $errors->first() }}
            </p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="form-edit-akun">
        @csrf
        @method('PUT')

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- LEFT: Main form --}}
            <div class="flex-1 space-y-4 min-w-0">

                {{-- Card 1: Identitas pengguna --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Identitas pengguna</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Nama lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('email') border-rose-500 @enderror"
                                   style="{{ $errors->has('email') ? 'border-color:#F43F5E;' : 'border-color:var(--klinika-border);' }}background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='{{ $errors->has('email') ? '#F43F5E' : 'var(--klinika-border)' }}'">
                            @error('email')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }} Email sebelumnya: {{ $user->email }}</p>
                            @else
                                <p class="text-[11px] klinika-muted mt-1">Email sebelumnya: {{ $user->email }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Card 2: Penempatan klinik dan role --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Penempatan klinik dan role</h3>

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
                        <p class="text-[11px] klinika-muted mt-1">Hak role berlaku hanya pada klinik ini.</p>
                    </div>

                    {{-- Role & Status --}}
                    @php
                        $currentRole = $user->roles->first()?->name ?? '';
                        $selectedRole = old('roles.0', $currentRole);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role_select" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Role <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="role_select" name="roles[]"
                                        class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                        onchange="updateRoleDetails(this.value)">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}" {{ $selectedRole === $r->name ? 'selected' : '' }}>
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
                            <label for="status_select" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Status <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="status_select" name="is_active"
                                        class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
                                    <option value="1" {{ old('is_active', $user->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $user->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none klinika-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <p class="text-[11px] klinika-muted mt-1">Aktif tidak berarti kata sandi terlihat.</p>
                        </div>
                    </div>

                    {{-- Role info callout box --}}
                    <div id="role-summary-box" class="rounded-md border p-3.5" style="background:#EEF9F9;border-color:#B2DEDE">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-primary)" id="role-callout-title">Tidak ada akses klinis tambahan</p>
                                <p class="text-xs mt-0.5" style="color:var(--klinika-primary)" id="role-callout-desc">
                                    Role baru hanya mengatur pasien, kunjungan, dan antrean. Tidak memberi akses SOAP, farmasi, pembayaran, atau role.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Alasan perubahan --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Alasan perubahan</h3>

                    <div>
                        <label for="audit_note" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Catatan audit <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="audit_note" name="audit_note" rows="3" required
                                  placeholder="Penugasan sebagai koordinator loket pendaftaran KSS-01."
                                  class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                  style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                  onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">{{ old('audit_note', 'Penugasan sebagai koordinator loket pendaftaran KSS-01.') }}</textarea>
                        <p class="text-[11px] klinika-muted mt-1">Dicatat bersama pelaku, waktu, dan nilai sebelum–sesudah.</p>
                    </div>
                </div>

                {{-- Form footer actions --}}
                <div class="flex items-center justify-between py-1">
                    <span class="text-xs klinika-muted">* Wajib diisi</span>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.index') }}"
                           class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                           style="border-color:var(--klinika-border);color:var(--klinika-text)">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                            Simpan perubahan
                        </button>
                    </div>
                </div>

            </div>{{-- end left --}}

            {{-- RIGHT: Sidebar info cards --}}
            <div class="w-full lg:w-72 shrink-0 space-y-4">

                {{-- Card 1: Akses sebelum → sesudah --}}
                <div class="klinika-card p-5 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Akses sebelum &rarr; sesudah</h3>
                    </div>

                    <div>
                        <span class="inline-block text-[11px] font-semibold px-2 py-0.5 rounded" style="background:#FFF3CD;color:#856404">
                            Belum disimpan
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-bold" style="color:var(--klinika-text)">
                            <span id="badge-prev-role">{{ $currentRole ?: 'Belum ada role' }}</span>
                            &rarr;
                            <span id="badge-curr-role">{{ $selectedRole ?: 'Belum ada role' }}</span>
                        </p>
                    </div>

                    <p class="text-xs klinika-muted leading-relaxed">
                        Klinik tetap KSS-01. Akses mengikuti izin role baru setelah transaksi simpan berhasil.
                    </p>

                    <p class="text-[11px] klinika-muted leading-relaxed pt-2 border-t" style="border-color:var(--klinika-border)">
                        Sesi dievaluasi ulang ketika role atau status berubah. Perubahan email memerlukan verifikasi alamat baru.
                    </p>
                </div>

                {{-- Card 2: Batas perubahan akses (warning alert) --}}
                <div class="rounded-lg border p-4" style="background:#FFF8ED;border-color:#F5D9A8">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C4871A">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold" style="color:#96611C">Batas perubahan akses</p>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color:#B07A30">
                                Anda tidak dapat menurunkan akses akun sendiri hingga kehilangan akses pengelolaan. Super Admin aktif terakhir tetap dilindungi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Validasi sebelum simpan --}}
                <div class="klinika-card p-5 space-y-3">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Validasi sebelum simpan</h3>

                    <ul class="space-y-2 text-xs" style="color:var(--klinika-text)">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Klinik dan role wajib terisi</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Izin tidak melebihi hak pemberi</span>
                        </li>
                        <li class="flex items-center gap-2">
                            @if ($errors->has('email'))
                                <span class="text-rose-600 font-bold">✕</span>
                                <span class="text-rose-600">Email belum unik</span>
                            @else
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Email valid &amp; unik</span>
                            @endif
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Tidak mengubah riwayat pelayanan</span>
                        </li>
                    </ul>
                </div>

            </div>{{-- end right --}}

        </div>{{-- end flex --}}

    </form>

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
const roleDetails = {
    'super_admin': {
        title: 'Akses penuh seluruh sistem',
        desc: 'Akses tanpa batas ke seluruh klinik, pengelolaan role, izin, dan konfigurasi master.'
    },
    'admin_klinik': {
        title: 'Pengelolaan operasional klinik',
        desc: 'Mengelola jadwal, tenaga kesehatan, dan akun di klinik terkait. Tanpa izin modifikasi role sistem.'
    },
    'pendaftaran': {
        title: 'Tidak ada akses klinis tambahan',
        desc: 'Role baru hanya mengatur pasien, kunjungan, dan antrean. Tidak memberi akses SOAP, farmasi, pembayaran, atau role.'
    },
    'perawat': {
        title: 'Pemeriksaan awal dan vital',
        desc: 'Mengisi tanda vital dan asesmen awal pasien. Tanpa akses resep atau verifikasi pembayaran.'
    },
    'dokter': {
        title: 'Akses medis dan resep',
        desc: 'Pemeriksaan klinis, catatan SOAP, diagnosis, dan pembuatan resep pasien. Tanpa akses kasir.'
    },
    'apoteker': {
        title: 'Pelayanan farmasi dan stok obat',
        desc: 'Pengelolaan obat, dispensing, dan telaah resep. Tanpa akses riwayat tagihan dan kasir.'
    },
    'kasir': {
        title: 'Pembayaran dan tagihan',
        desc: 'Memproses pembayaran, kwitansi, dan tagihan pasien. Tanpa akses rekam medis atau SOAP.'
    },
    'manajemen': {
        title: 'Laporan dan monitoring (read-only)',
        desc: 'Melihat laporan dan analitik klinik secara read-only. Tidak dapat mengubah data medis.'
    }
};

function updateRoleDetails(role) {
    const titleEl = document.getElementById('role-callout-title');
    const descEl = document.getElementById('role-callout-desc');
    const badgeRoleEl = document.getElementById('badge-curr-role');

    if (badgeRoleEl) {
        badgeRoleEl.textContent = role || 'Belum ada role';
    }

    const key = (role || '').toLowerCase();
    const detail = roleDetails[key] || {
        title: 'Role: ' + role,
        desc: 'Role baru mengikuti izin yang telah ditetapkan pada konfigurasi hak akses.'
    };

    if (titleEl) titleEl.textContent = detail.title;
    if (descEl) descEl.textContent = detail.desc;
}

// Initialise on page load
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role_select');
    if (roleSelect && roleSelect.value) {
        updateRoleDetails(roleSelect.value);
    }
});
</script>
@endpush
