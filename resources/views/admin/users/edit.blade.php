@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')
@section('page-title', 'Daftar akun')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Daftar akun</a>
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

    {{-- Info banner --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Perubahan penting</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Ubah role, status, atau reset password akan meminta konfirmasi sebelum disimpan.
            </p>
        </div>
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
            <div class="w-full lg:w-[65%] space-y-4">

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
                                Email atau username <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('email') border-rose-500 @enderror"
                                   style="{{ $errors->has('email') ? 'border-color:#F43F5E;' : 'border-color:var(--klinika-border);' }}background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='{{ $errors->has('email') ? '#F43F5E' : 'var(--klinika-border)' }}'">
                            @error('email')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Card 2: Role dan status --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Role dan status</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role_select" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Role <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="role_select" name="roles[]" required
                                        class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
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
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="status_select" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Status <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="status_select" name="is_active" required
                                        class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                        style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)">
                                    <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Alasan perubahan --}}
                <div class="klinika-card p-6 space-y-3">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Alasan perubahan</h3>

                    <div>
                        <label for="audit_note" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                            Catatan audit
                        </label>
                        <textarea id="audit_note" name="audit_note" rows="3"
                                  placeholder="Perbarui informasi akun sesuai penugasan pengguna."
                                  class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors resize-none"
                                  style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                  onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">{{ old('audit_note', 'Perbarui informasi akun sesuai penugasan pengguna.') }}</textarea>
                        <p class="text-[11px] klinika-muted mt-1.5">
                            Dicatat bersama pelaku, waktu, dan nilai sebelum–sesudah.
                        </p>
                    </div>
                </div>

                {{-- Card 4: Reset password (opsional) --}}
                <div class="klinika-card p-6 space-y-4">
                    <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Reset password (opsional)</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Kata sandi baru
                            </label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   placeholder="Kosongkan jika tidak diubah"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('password') border-rose-500 @enderror"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                            @error('password')
                                <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                                Konfirmasi kata sandi baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                                   style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                                   onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
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
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium" style="background:#FEF3C7;color:#92400E">
                            Belum disimpan
                        </span>
                    </div>

                    <div>
                        <p class="text-sm font-bold" id="summary-name-role" style="color:var(--klinika-text)">
                            {{ $user->name }} • {{ ucfirst(str_replace('_', ' ', $user->roles->first()?->name ?? 'Pengguna')) }}
                        </p>
                        <p class="text-xs klinika-muted mt-1 leading-relaxed">
                            Klinik Sehat Sentosa • KSS-01. Role dan status akun berlaku setelah perubahan dikonfirmasi.
                        </p>
                        <p class="text-xs klinika-muted mt-2 leading-relaxed">
                            Sesi dievaluasi ulang ketika role atau status berubah.
                        </p>
                    </div>
                </div>

                {{-- Card 2: Batas perubahan akses --}}
                <div class="rounded-lg border p-4" style="background:#FFFBEB;border-color:#FDE68A">
                    <div class="flex items-start gap-2.5">
                        <div class="w-4 h-4 rounded-full border border-amber-600 flex items-center justify-center shrink-0 mt-0.5" style="color:#B45309">
                            <span class="text-[10px] font-bold">i</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold" style="color:#92400E">Batas perubahan akses</p>
                            <p class="text-[11px] mt-1 leading-relaxed" style="color:#B45309">
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
                            <span>Identitas login harus unik</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span>Konfirmasi kata sandi harus sesuai</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
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
                   class="px-5 py-2 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50"
                   style="border-color:var(--klinika-border);color:var(--klinika-text);background:#fff">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-medium text-white transition-opacity hover:opacity-90 klinika-primary">
                    Simpan perubahan
                </button>
            </div>
        </div>

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
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const roleSelect = document.getElementById('role_select');
    const statusSelect = document.getElementById('status_select');
    const summaryEl = document.getElementById('summary-name-role');
    const passwordInput = document.getElementById('password');
    const form = document.getElementById('form-edit-akun');

    const initialRole = roleSelect ? roleSelect.value : '';
    const initialStatus = statusSelect ? statusSelect.value : '';

    function updateSummary() {
        if (!summaryEl) return;
        const name = (nameInput && nameInput.value.trim()) || 'Pengguna';
        const roleText = (roleSelect && roleSelect.options[roleSelect.selectedIndex]?.text) || '';
        summaryEl.textContent = name + ' • ' + roleText;
    }

    if (nameInput) nameInput.addEventListener('input', updateSummary);
    if (roleSelect) roleSelect.addEventListener('change', updateSummary);

    if (form) {
        form.addEventListener('submit', function (e) {
            const roleChanged = roleSelect && roleSelect.value !== initialRole;
            const statusChanged = statusSelect && statusSelect.value !== initialStatus;
            const passwordFilled = passwordInput && passwordInput.value.length > 0;

            if (roleChanged || statusChanged || passwordFilled) {
                const reasons = [];
                if (roleChanged) reasons.push('Role pengguna');
                if (statusChanged) reasons.push('Status akun');
                if (passwordFilled) reasons.push('Kata sandi');

                const confirmMsg = 'Konfirmasi perubahan penting (' + reasons.join(', ') + ')?\n\nPerubahan ini akan langsung diperbarui ke sistem.';
                if (!window.confirm(confirmMsg)) {
                    e.preventDefault();
                }
            }
        });
    }
});
</script>
@endpush
