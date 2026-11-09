@extends('layouts.app')

@section('title', 'Edit Data ' . $patient->name)
@section('page-title', 'Data pasien')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.index') }}" class="hover:underline klinika-primary-text">Data pasien</a>
    <span class="mx-1">/</span>
    <a href="{{ route('patients.show', $patient) }}" class="hover:underline klinika-primary-text">{{ $patient->medical_record_number }}</a>
    <span class="mx-1">/</span>
    <span>Edit</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Edit data {{ $patient->name }}</h2>
            <p class="text-sm klinika-muted mt-0.5">Perubahan demografi tidak mengubah isi encounter sebelumnya.</p>
        </div>
        <a href="{{ route('patients.show', $patient) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold border klinika-border text-slate-700 bg-white hover:bg-gray-50 transition-colors shadow-xs">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Detail pasien
        </a>
    </div>

    {{-- Cyan Info Banner --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Nomor RM tetap: {{ $patient->medical_record_number }}</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Hanya Admin Klinik dan Pendaftaran yang dapat mengubah identitas. Semua perubahan direkam dalam audit.
            </p>
        </div>
    </div>

    {{-- Error Banner --}}
    @if ($errors->any())
    <div class="p-4 rounded-lg border flex items-start gap-3" style="background:#FFF1F2;border-color:#FECDD3">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#E11D48">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold" style="color:#9F1239">Periksa kembali {{ $errors->count() }} isian</p>
            <p class="text-xs mt-0.5" style="color:#BE123C">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('patients.update', $patient) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <input type="hidden" name="birth_place" value="{{ old('birth_place', $patient->birth_place ?? '-') }}">

        {{-- Card 1: Identitas dan kontak --}}
        <div class="klinika-card p-6 space-y-4">
            <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Identitas dan kontak</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nomor rekam medis --}}
                <div>
                    <label class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">Nomor rekam medis</label>
                    <input type="text" value="{{ $patient->medical_record_number }}" readonly disabled
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm bg-gray-100 text-gray-500 cursor-not-allowed"
                           style="border-color:var(--klinika-border)">
                </div>

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        NIK <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nik" name="nik" value="{{ old('nik', $patient->nik) }}" required maxlength="16"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('nik') border-rose-500 @enderror"
                           style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    @error('nik')<p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama lengkap --}}
                <div>
                    <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Nama lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $patient->name) }}" required
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('name') border-rose-500 @enderror"
                           style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    @error('name')<p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nomor telepon --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Nomor telepon <span class="text-rose-500">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" required
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('phone') border-rose-500 @enderror"
                           style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    @error('phone')<p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal lahir --}}
                <div>
                    <label for="birth_date" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Tanggal lahir <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $patient->birth_date?->format('Y-m-d')) }}" required
                           max="{{ now()->toDateString() }}"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors @error('birth_date') border-rose-500 @enderror"
                           style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                    @error('birth_date')<p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Jenis kelamin --}}
                <div>
                    <label for="gender" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Jenis kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="gender" name="gender" required
                                class="w-full appearance-none px-3.5 py-2.5 pr-8 rounded-md border text-sm outline-none transition-colors"
                                style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)">
                            <option value="P" {{ old('gender', $patient->gender) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            <option value="L" {{ old('gender', $patient->gender) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5" style="color:var(--klinika-muted)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="address" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Alamat <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" required
                              class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors resize-none @error('address') border-rose-500 @enderror"
                              style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                              onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">{{ old('address', $patient->address) }}</textarea>
                    @error('address')<p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Card 2: Catatan perubahan --}}
        <div class="klinika-card p-6 space-y-3">
            <h3 class="font-semibold text-base" style="color:var(--klinika-text)">Catatan perubahan</h3>

            <div>
                <label for="update_reason" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                    Alasan pembaruan <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="update_reason" name="update_reason"
                       value="{{ old('update_reason', 'Pembaruan nomor telepon atas konfirmasi pasien.') }}" required
                       class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                       style="border-color:var(--klinika-border);background:#fff;color:var(--klinika-text)"
                       placeholder="Alasan perubahan data identitas"
                       onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                <p class="text-[11px] klinika-muted mt-1.5">
                    Dicatat bersama aktor dan waktu perubahan; tidak tampil pada SOAP.
                </p>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Simpan perubahan
            </button>
            <a href="{{ route('patients.show', $patient) }}"
               class="px-5 py-2.5 rounded-lg border text-sm font-medium transition-colors hover:bg-gray-50 bg-white"
               style="border-color:var(--klinika-border);color:var(--klinika-text)">
                Batal
            </a>
        </div>

    </form>

    {{-- Footer --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
