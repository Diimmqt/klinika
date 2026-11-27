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
        <p class="text-sm klinika-muted mt-1">
            Buat akun melalui undangan aktivasi email. Sistem men-<em>generate</em> kata sandi awal (tersimpan ter-<em>hash</em>) sebagai modal awal login pengguna.
        </p>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="p-3.5 rounded-lg border flex items-start gap-3 bg-rose-50 border-rose-200">
        <svg class="w-4 h-4 mt-0.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs text-rose-800">
            <p class="font-semibold">Periksa kembali isian form:</p>
            <p class="mt-0.5">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" id="form-tambah-akun">
        @csrf
        <input type="hidden" name="send_invitation" value="1">

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- LEFT: Main form --}}
            <div class="flex-1 space-y-4 min-w-0">

                {{-- Identitas pengguna --}}
                <div class="klinika-card p-6 space-y-4">
                    <div>
                        <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Identitas pengguna</h3>
                        <p class="text-xs klinika-muted mt-0.5">Gunakan email aktif milik pengguna untuk menerima tautan aktivasi dan modal awal login.</p>
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
                            <p class="text-[11px] klinika-muted mt-1">Undangan aktivasi &amp; kata sandi awal dikirim ke alamat ini.</p>
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
                                <select id="role_select" name="roles[]" required
                                        class="w-full px-3.5 py-2.5 rounded-md border text-sm appearance-none outline-none pr-9"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                        onchange="updateRoleSummary(this.value)">
                                    <option value="">-- Pilih role --</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->name }}" {{ old('roles.0') === $r->name ? 'selected' : '' }}>
                                            {{ \App\Models\User::ROLE_LABELS[$r->name] ?? ucwords(str_replace('_', ' ', $r->name)) }}
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

                    {{-- Automatic initial password notice --}}
                    <div class="rounded-md border p-3.5 text-xs space-y-1" style="background:#F0FDFA;border-color:#99F6E4;color:#0F766E">
                        <p class="font-semibold">Kata sandi awal dibuat otomatis oleh sistem</p>
                        <p class="text-[11px] leading-relaxed">
                            Saat Anda menekan konfirmasi (<em>Accept</em>), sistem mengirim email ke calon pengguna berisi konfirmasi nama lengkap, role di klinik/RS, kata sandi awal (tersimpan ter-<em>hash</em> di database), dan tautan aktivasi akun.
                        </p>
                    </div>
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
                        <button type="button" id="btn-open-confirm" onclick="openConfirmModal()"
                                class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors cursor-pointer">
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
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Email aktivasi terkirim</p>
                                <p class="text-[11px] klinika-muted">Memuat nama, role, kata sandi awal, dan tautan 24 jam.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5" style="background:var(--klinika-primary)">2</div>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Pengguna mengaktifkan akun</p>
                                <p class="text-[11px] klinika-muted">Klik tautan di email agar status berubah menjadi Aktif.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 mt-0.5" style="background:var(--klinika-primary)">3</div>
                            <div>
                                <p class="text-xs font-semibold" style="color:var(--klinika-text)">Login &amp; ganti sandi mandiri</p>
                                <p class="text-[11px] klinika-muted">Login memakai sandi awal lalu atur sandi baru di Pengaturan.</p>
                            </div>
                        </li>
                    </ol>

                    <p class="text-[11px] klinika-muted mt-4 pt-4 border-t" style="border-color:var(--klinika-border)">
                        Kata sandi awal tersimpan dalam bentuk hash aman di database sejak akun dibuat.
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
                            <p class="text-[11px] mt-0.5" style="color:#B07A30">Menunggu aktivasi bukan akun Aktif. Pengguna wajib menekan tautan aktivasi di email terlebih dahulu.</p>
                        </div>
                    </div>
                </div>

            </div>{{-- end sidebar --}}

        </div>{{-- end flex --}}

        {{-- Modal Konfirmasi (Accept) Pengiriman Email Aktivasi --}}
        <dialog id="confirm-user-modal" class="rounded-xl border klinika-border p-0 w-full max-w-md shadow-xl backdrop:bg-slate-900/40" style="margin:auto">
            <div class="p-5 space-y-4 bg-white">
                <div>
                    <h3 class="font-bold text-sm" style="color:var(--klinika-text)">Konfirmasi pembuatan &amp; pengiriman aktivasi</h3>
                    <p class="text-xs klinika-muted mt-1 leading-relaxed">
                        Pastikan data calon pengguna sudah benar. Email aktivasi beserta kata sandi awal akan langsung dikirimkan.
                    </p>
                </div>

                <div class="rounded-lg border klinika-border p-3.5 space-y-2 text-xs" style="background:#FAFCFC">
                    <div class="flex justify-between gap-3">
                        <span class="klinika-muted">Nama lengkap</span>
                        <span id="confirm-name" class="font-semibold text-right" style="color:var(--klinika-text)">—</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="klinika-muted">Email tujuan</span>
                        <span id="confirm-email" class="font-semibold text-right" style="color:var(--klinika-primary)">—</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="klinika-muted">Klinik</span>
                        <span id="confirm-klinik" class="font-semibold text-right" style="color:var(--klinika-text)">—</span>
                    </div>
                    <div class="flex justify-between gap-3">
                        <span class="klinika-muted">Role di klinik</span>
                        <span id="confirm-role" class="font-semibold text-right" style="color:var(--klinika-text)">—</span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-1">
                    <button type="button" onclick="document.getElementById('confirm-user-modal').close()"
                            class="px-4 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors cursor-pointer" style="color:var(--klinika-text)">
                        Periksa Kembali
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg text-xs font-semibold text-white transition-opacity hover:opacity-90 klinika-primary cursor-pointer">
                        Accept &amp; Kirim Email
                    </button>
                </div>
            </div>
        </dialog>

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
                    ✓ Akun dibuat. Email aktivasi beserta kata sandi awal berhasil dikirim; pengguna wajib mengaktifkan akun sebelum login.
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
    'super_admin':  'Akses penuh ke seluruh sistem termasuk manajemen role dan klinik.',
    'admin_klinik': 'Pengelolaan operasional klinik, master data poli, jadwal dokter, dan pengguna.',
    'pendaftaran':  'Pasien, kunjungan, dan antrean di KSS-01. Tanpa isi SOAP, resep, pembayaran, akun, atau manajemen role.',
    'dokter':       'Pemeriksaan, SOAP, dan resep di KSS-01. Tanpa manajemen akun atau pembayaran.',
    'kasir':        'Pembayaran dan kwitansi di KSS-01. Tanpa akses rekam medis atau manajemen akun.',
    'apoteker':     'Pengelolaan obat dan dispensing di KSS-01. Tanpa akses SOAP atau pembayaran.',
    'perawat':      'Asesmen awal dan pemeriksaan vital di KSS-01. Tanpa resep atau pembayaran.',
    'manajemen':    'Pemantauan laporan operasional dan ringkasan kinerja klinik (read-only).',
};

function updateRoleSummary(role) {
    const box  = document.getElementById('role-summary-box');
    const name = document.getElementById('role-summary-name');
    const desc = document.getElementById('role-summary-desc');
    const sel  = document.getElementById('role_select');
    const label = sel && sel.selectedIndex >= 0 ? sel.options[sel.selectedIndex].text.trim() : role;
    if (role && roleSummaries[role]) {
        name.textContent = label;
        desc.textContent = roleSummaries[role];
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
}

function openConfirmModal() {
    const form = document.getElementById('form-tambah-akun');
    if (!form.reportValidity()) {
        return;
    }
    const roleSelect = document.getElementById('role_select');
    const klinikSelect = document.getElementById('klinik');
    document.getElementById('confirm-name').textContent = document.getElementById('name').value.trim();
    document.getElementById('confirm-email').textContent = document.getElementById('email').value.trim();
    document.getElementById('confirm-klinik').textContent = klinikSelect?.options[klinikSelect.selectedIndex]?.text.trim() || 'KSS-01';
    document.getElementById('confirm-role').textContent = roleSelect.options[roleSelect.selectedIndex]?.text.trim() || '—';

    const dialog = document.getElementById('confirm-user-modal');
    if (typeof dialog.showModal === 'function') {
        dialog.showModal();
    } else {
        form.submit();
    }
}

const sel = document.getElementById('role_select');
if (sel && sel.value) updateRoleSummary(sel.value);
</script>
@endpush
