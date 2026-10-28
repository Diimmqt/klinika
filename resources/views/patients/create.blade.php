@extends('layouts.app')

@section('title', 'Tambah Pasien')

@section('breadcrumb')
    <a href="{{ route('patients.index') }}" class="hover:underline klinika-primary-text">Data pasien</a>
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span>Tambah pasien</span>
@endsection

@section('content')

{{-- Duplicate NIK banner (shown when validation fails with nik.unique) --}}
@if ($errors->has('nik') && session('duplicate_patient'))
@php $dup = session('duplicate_patient'); @endphp
<div class="rounded-lg border p-4 flex items-start gap-3" style="background:#fff1f2;border-color:#fecdd3">
    <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:#be123c" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div class="flex-1">
        <p class="text-sm font-semibold" style="color:#9f1239">Pasien dengan NIK ini sudah terdaftar</p>
        <p class="text-xs mt-0.5" style="color:#be123c">{{ $dup['name'] }} &bull; {{ $dup['rm'] }}</p>
    </div>
    <a href="{{ $dup['url'] }}"
       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-white shrink-0"
       style="background:#be123c">
        Buka {{ $dup['rm'] }}
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>
@endif

<form method="POST" action="{{ route('patients.store') }}" id="createPatientForm">
@csrf

{{-- Identitas pasien --}}
<div class="klinika-card p-6 space-y-4">
    <h3 class="font-semibold text-sm border-b klinika-border pb-3" style="color:var(--klinika-text)">Identitas pasien</h3>

    {{-- NIK --}}
    <div>
        <label class="block text-xs font-semibold klinika-muted mb-1">NIK <span class="text-red-500">*</span></label>
        <input type="text" name="nik" id="nikInput" value="{{ old('nik') }}"
               maxlength="16" inputmode="numeric" pattern="[0-9]{16}"
               placeholder="16 digit NIK sesuai KTP"
               class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:ring-2 @error('nik') border-red-400 @else klinika-border @enderror">
        @error('nik')
            <p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Nama --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Nama lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="Nama sesuai KTP"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none @error('name') border-red-400 @enderror">
            @error('name')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
        </div>

        {{-- Jenis kelamin --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Jenis kelamin <span class="text-red-500">*</span></label>
            <select name="gender"
                    class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none @error('gender') border-red-400 @enderror">
                <option value="">Pilih jenis kelamin</option>
                <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('gender')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
        </div>

        {{-- Tempat lahir --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Tempat lahir <span class="text-red-500">*</span></label>
            <input type="text" name="birth_place" value="{{ old('birth_place') }}"
                   placeholder="Kota/kabupaten kelahiran"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none @error('birth_place') border-red-400 @enderror">
            @error('birth_place')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
        </div>

        {{-- Tanggal lahir --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Tanggal lahir <span class="text-red-500">*</span></label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                   max="{{ now()->toDateString() }}"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none @error('birth_date') border-red-400 @enderror">
            @error('birth_date')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
        </div>

        {{-- Telepon --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Nomor telepon <span class="text-red-500">*</span></label>
            <input type="tel" name="phone" value="{{ old('phone') }}"
                   placeholder="08xxxxxxxxxx"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none @error('phone') border-red-400 @enderror">
            @error('phone')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
        </div>

        {{-- Pekerjaan (optional) --}}
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Pekerjaan</label>
            <input type="text" name="occupation" value="{{ old('occupation') }}"
                   placeholder="Opsional"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none">
        </div>
    </div>

    {{-- Alamat --}}
    <div>
        <label class="block text-xs font-semibold klinika-muted mb-1">Alamat domisili <span class="text-red-500">*</span></label>
        <textarea name="address" rows="2"
                  placeholder="Alamat lengkap tempat tinggal saat ini"
                  class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none resize-none @error('address') border-red-400 @enderror">{{ old('address') }}</textarea>
        @error('address')<p class="text-xs mt-1" style="color:#be123c">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Kontak keluarga --}}
<div class="klinika-card p-6 space-y-4">
    <h3 class="font-semibold text-sm border-b klinika-border pb-3" style="color:var(--klinika-text)">Kontak keluarga</h3>
    <p class="text-xs klinika-muted -mt-2">Dihubungi saat kondisi darurat. Opsional namun sangat disarankan.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Nama kontak</label>
            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                   placeholder="Nama lengkap"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none">
        </div>
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Hubungan</label>
            <select name="emergency_contact_relationship"
                    class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none">
                <option value="">Pilih hubungan</option>
                @foreach (['Suami', 'Istri', 'Ayah', 'Ibu', 'Anak', 'Kakak', 'Adik', 'Wali', 'Lainnya'] as $rel)
                    <option value="{{ $rel }}" {{ old('emergency_contact_relationship') === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold klinika-muted mb-1">Telepon kontak</label>
            <input type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                   placeholder="08xxxxxxxxxx"
                   class="w-full px-3 py-2 rounded-lg border klinika-border text-sm focus:outline-none">
        </div>
    </div>
</div>

{{-- Footer buttons --}}
<div class="flex flex-wrap items-center gap-3">
    @php $isDuplicate = $errors->has('nik') && session('duplicate_patient'); @endphp

    <button type="submit" id="submitBtn"
            {{ $isDuplicate ? 'disabled' : '' }}
            class="px-5 py-2 rounded-lg text-sm font-semibold text-white transition-colors {{ $isDuplicate ? 'opacity-40 cursor-not-allowed bg-gray-400' : 'klinika-primary' }}">
        Simpan pasien
    </button>

    @if ($isDuplicate)
    <a href="{{ session('duplicate_patient.url') }}"
       class="inline-flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-semibold text-white"
       style="background:#be123c">
        Buka {{ session('duplicate_patient.rm') }}
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
    @endif

    <a href="{{ route('patients.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium border klinika-border klinika-muted hover:bg-gray-50 transition-colors">
        Batal
    </a>
</div>

</form>

<p class="text-center text-xs klinika-muted pt-2">Data simulasi &bull; Bukan data pasien nyata | Klinika RME &bull; Akses tercatat</p>
@endsection
