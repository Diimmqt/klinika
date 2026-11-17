@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')
@section('page-title', 'Edit akun pengguna')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Daftar akun</a>
    <span class="mx-1">/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:underline klinika-primary-text">Akun pengguna</a>
    <span class="mx-1">/</span>
    <span class="hover:underline klinika-primary-text">{{ $user->name }}</span>
    <span class="mx-1">/</span>
    <span>Edit</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page heading --}}
    <div>
        <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Edit akun pengguna</h2>
        <p class="text-xs klinika-muted mt-0.5">
            {{ $user->name }} &bull; USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }} &bull; Perubahan belum disimpan.
        </p>
    </div>

    {{-- Info banner --}}
    <div class="rounded-lg border p-3 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div class="text-xs" style="color:var(--klinika-primary)">
            <p class="font-bold">Perubahan penting</p>
            <p class="mt-0.5">
                Ubah role, status, atau reset password akan meminta konfirmasi sebelum disimpan.
            </p>
        </div>
    </div>

    {{-- Error banner --}}
    @if ($errors->any())
    <div class="p-3.5 rounded-lg border flex items-start gap-3 bg-rose-50 border-rose-200">
        <svg class="w-4 h-4 mt-0.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-xs text-rose-800">
            <p class="font-semibold">Periksa kembali {{ $errors->count() }} isian:</p>
            <p class="mt-0.5">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="form-edit-akun">
        @csrf
        @method('PUT')

        <div class="flex flex-col lg:flex-row gap-5 items-start">

            {{-- LEFT: Main form --}}
            <div class="w-full lg:w-[65%] space-y-4">

                {{-- Card 1: Identitas pengguna --}}
                <div class="klinika-card p-5 space-y-4">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Identitas pengguna</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Nama lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Email atau username <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        </div>
                    </div>
                </div>

                {{-- Card 2: Role dan status --}}
                <div class="klinika-card p-5 space-y-4">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Role dan status</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role_select" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Role <span class="text-rose-500">*</span>
                            </label>
                            <select id="role_select" name="roles[]" required
                                    class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                                @foreach ($roles as $role)
                                    @php
                                        $currentRole = old('roles', $user->roles->pluck('name')->toArray());
                                        $isSelected = in_array($role->name, is_array($currentRole) ? $currentRole : [$currentRole]);
                                    @endphp
                                    <option value="{{ $role->name }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status_select" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Status akun <span class="text-rose-500">*</span>
                            </label>
                            <select id="status_select" name="is_active" required
                                    class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                                <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Alasan perubahan --}}
                <div class="klinika-card p-5 space-y-3">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Alasan perubahan</h3>

                    <div>
                        <label for="audit_note" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                            Catatan audit
                        </label>
                        <textarea id="audit_note" name="audit_note" rows="2"
                                  placeholder="Perbarui informasi akun sesuai penugasan pengguna."
                                  class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none resize-none" style="color:var(--klinika-text)">{{ old('audit_note', 'Perbarui informasi akun sesuai penugasan pengguna.') }}</textarea>
                        <p class="text-[11px] klinika-muted mt-1">
                            Dicatat bersama pelaku, waktu, dan nilai sebelum-sesudah.
                        </p>
                    </div>
                </div>

                {{-- Card 4: Reset password (opsional) --}}
                <div class="klinika-card p-5 space-y-3">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Reset password (opsional)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Kata sandi baru
                            </label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   placeholder="Kosongkan jika tidak diubah"
                                   class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold mb-1" style="color:var(--klinika-text)">
                                Konfirmasi kata sandi baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full px-3 py-2 rounded-lg border klinika-border text-xs bg-white focus:outline-none" style="color:var(--klinika-text)">
                        </div>
                    </div>
                    <p class="text-[11px] klinika-muted">
                        Kosongkan untuk mempertahankan password saat ini.
                    </p>
                </div>

            </div>{{-- end left --}}

            {{-- RIGHT: Summary & validation cards --}}
            <div class="w-full lg:w-[35%] space-y-4">

                {{-- Card 1: Ringkasan akun --}}
                <div class="klinika-card p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Ringkasan akun</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-amber-100 text-amber-800">
                            Belum disimpan
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-bold" id="summary-name-role" style="color:var(--klinika-text)">
                            {{ $user->name }} &bull; {{ ucfirst(str_replace('_', ' ', $user->roles->first()?->name ?? 'Pengguna')) }}
                        </p>
                        <p class="text-xs klinika-muted mt-1 leading-relaxed">
                            Klinik Sehat Sentosa &bull; KSS-01. Role dan status akun berlaku setelah perubahan dikonfirmasi.
                        </p>
                        <p class="text-xs klinika-muted mt-2 leading-relaxed">
                            Sesi dievaluasi ulang ketika role atau status berubah.
                        </p>
                    </div>

                    {{-- Batas perubahan akses --}}
                    <div class="rounded-lg border p-3 bg-amber-50/70 border-amber-200">
                        <div class="flex items-start gap-2 text-xs">
                            <span class="text-amber-700 font-bold mt-0.5">&bull;</span>
                            <div>
                                <p class="font-semibold text-amber-900">Batas perubahan akses</p>
                                <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                    Anda tidak dapat menurunkan akses akun sendiri hingga kehilangan akses pengelolaan. Super Admin aktif terakhir tetap dilindungi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Validasi sebelum simpan --}}
                <div class="klinika-card p-5 space-y-3">
                    <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Validasi sebelum simpan</h3>

                    <ul class="space-y-2 text-xs" style="color:var(--klinika-text)">
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">&check;</span>
                            <span>Identitas login harus unik</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">&check;</span>
                            <span>Konfirmasi kata sandi harus sesuai</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">&check;</span>
                            <span>Riwayat pelayanan tetap tersimpan</span>
                        </li>
                    </ul>
                </div>

            </div>{{-- end right --}}

        </div>{{-- end flex --}}

        {{-- Bottom action bar --}}
        <div class="flex items-center justify-between pt-4 mt-2">
            <span class="text-xs klinika-muted">* Wajib diisi</span>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-5 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                    Batal
                </a>
                <button type="button" id="btn-submit-check"
                        class="px-5 py-2 rounded-lg text-xs font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                    Simpan perubahan
                </button>
            </div>
        </div>

    </form>

    {{-- Subtle footer --}}
    <div class="pt-2 text-xs klinika-muted flex flex-col sm:flex-row items-center gap-2">
        <span>Data simulasi &bull; Bukan data pasien nyata</span>
        <span class="hidden sm:inline">|</span>
        <span>Klinika RME &bull; Akses tercatat</span>
    </div>

</div>

{{-- MODAL KONFIRMASI NONAKTIFKAN AKUN --}}
<div id="modal-deactivate" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden border klinika-border animate-in fade-in zoom-in-95 duration-150">
        <div class="p-6 space-y-4">
            <div>
                <h3 class="text-base font-bold" style="color:var(--klinika-text)">Nonaktifkan {{ $user->name }}?</h3>
                <p class="text-xs klinika-muted mt-0.5">Konfirmasi perubahan status akun sebelum disimpan.</p>
            </div>

            {{-- Summary Card inside Modal --}}
            <div class="p-3.5 rounded-lg border klinika-border bg-slate-50/70 space-y-1.5 text-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800">{{ $user->name }} &bull; USR-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                        <p class="text-slate-500 text-[11px]">{{ $user->email }}</p>
                    </div>
                    <span class="font-semibold text-amber-600">Aktif &rarr; Nonaktif</span>
                </div>
                <p class="text-[11px] text-slate-500 pt-1 border-t klinika-border">
                    Role tetap {{ ucfirst(str_replace('_', ' ', $user->roles->first()?->name ?? 'Pengguna')) }} &bull; Kata sandi tidak diubah.
                </p>
            </div>

            {{-- Warning notice inside modal --}}
            <div class="rounded-lg border p-3.5 bg-amber-50/70 border-amber-200 text-xs">
                <div class="flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <div>
                        <p class="font-bold text-amber-900">Login dan sesi akan dihentikan</p>
                        <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                            {{ $user->name }} tidak dapat login. Semua sesi aktif dan token akses akan dicabut setelah Anda mengonfirmasi.
                        </p>
                    </div>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 leading-relaxed">
                Identitas penulis pada rekam medis / SOAP, referensi pelaku transaksi, dan riwayat audit tetap tersimpan. Perubahan status ini dicatat di audit.
            </p>

            {{-- Modal Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="closeDeactivateModal()"
                        class="px-4 py-2 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors" style="color:var(--klinika-text)">
                    Kembali mengedit
                </button>
                <button type="button" onclick="submitDeactivateForm()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-rose-700 hover:bg-rose-800 transition-colors">
                    Ya, nonaktifkan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function closeDeactivateModal() {
    document.getElementById('modal-deactivate').classList.add('hidden');
}

function submitDeactivateForm() {
    document.getElementById('form-edit-akun').submit();
}

document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const roleSelect = document.getElementById('role_select');
    const statusSelect = document.getElementById('status_select');
    const summaryEl = document.getElementById('summary-name-role');
    const form = document.getElementById('form-edit-akun');
    const btnSubmit = document.getElementById('btn-submit-check');
    const modalDeactivate = document.getElementById('modal-deactivate');

    function updateSummary() {
        if (!summaryEl) return;
        const name = (nameInput && nameInput.value.trim()) || 'Pengguna';
        const roleText = (roleSelect && roleSelect.options[roleSelect.selectedIndex]?.text) || '';
        summaryEl.textContent = name + ' • ' + roleText;
    }

    if (nameInput) nameInput.addEventListener('input', updateSummary);
    if (roleSelect) roleSelect.addEventListener('change', updateSummary);

    if (btnSubmit) {
        btnSubmit.addEventListener('click', function () {
            // If status is changed to nonaktif (0), show custom modal
            if (statusSelect && statusSelect.value === '0') {
                modalDeactivate.classList.remove('hidden');
            } else {
                form.submit();
            }
        });
    }
});
</script>
@endpush
