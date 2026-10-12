@extends('layouts.app')

@section('title', 'Tambah Pasien Baru')
@section('page-title', 'Tambah Pasien')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('patients.index') }}" class="text-xs text-slate-400 hover:text-teal-600 transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight mt-1">Pendaftaran Pasien Baru</h2>
            <p class="text-sm text-slate-500">Lengkapi data identitas pasien untuk pembuatan nomor rekam medis.</p>
        </div>
    </div>

    <!-- Informasi Penomoran Otomatis -->
    <div class="p-4 rounded-2xl bg-teal-50/70 border border-teal-200/60 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <h4 class="text-xs font-bold text-teal-900">Penomoran Rekam Medis Otomatis</h4>
            <p class="text-xs text-teal-800/80 mt-0.5">
                Nomor Rekam Medis (No. RM) akan otomatis di-generate secara atomic dan berurutan dari database (format: <code class="font-mono bg-teal-100/80 px-1 py-0.5 rounded text-teal-900 font-bold">RM-{{ date('Y') }}-XXXXX</code>) saat formulir ini disimpan.
            </p>
        </div>
    </div>

    <!-- Form Pasien Baru -->
    <form method="POST" action="{{ route('patients.store') }}" class="p-6 md:p-8 rounded-2xl bg-white border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                1. Data Identitas Pasien
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- NIK -->
                <div>
                    <label for="nik" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        NIK (Nomor Induk Kependudukan)
                    </label>
                    <input type="text" 
                           id="nik" 
                           name="nik" 
                           value="{{ old('nik') }}" 
                           placeholder="16 digit angka sesuai KTP" 
                           maxlength="16"
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('nik') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono">
                    @error('nik')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-[11px] text-slate-400">Opsional, jika belum memiliki KTP dapat dikosongkan.</p>
                    @enderror
                </div>

                <!-- Nama Pasien -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap Pasien <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Masukkan nama lengkap pasien" 
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('name') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors {{ old('gender') === 'L' ? 'border-teal-500 bg-teal-50/50 text-teal-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                            <input type="radio" name="gender" value="L" {{ old('gender') === 'L' ? 'checked' : '' }} required class="text-teal-600 focus:ring-teal-500">
                            <span class="text-xs font-medium">Laki-laki</span>
                        </label>
                        <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition-colors {{ old('gender') === 'P' ? 'border-teal-500 bg-teal-50/50 text-teal-900' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                            <input type="radio" name="gender" value="P" {{ old('gender') === 'P' ? 'checked' : '' }} required class="text-teal-600 focus:ring-teal-500">
                            <span class="text-xs font-medium">Perempuan</span>
                        </label>
                    </div>
                    @error('gender')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label for="birth_place" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tempat Lahir <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="birth_place" 
                           name="birth_place" 
                           value="{{ old('birth_place') }}" 
                           placeholder="Kota kelahiran" 
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('birth_place') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    @error('birth_place')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="birth_date" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tanggal Lahir <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           id="birth_date" 
                           name="birth_date" 
                           value="{{ old('birth_date') }}" 
                           max="{{ date('Y-m-d') }}"
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('birth_date') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    @error('birth_date')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-4 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">
                2. Kontak & Informasi Klinis
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Nomor Telepon -->
                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nomor Telepon / WhatsApp
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="Contoh: 08123456789" 
                           class="w-full px-3.5 py-2.5 rounded-xl border @error('phone') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    @error('phone')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Golongan Darah -->
                <div>
                    <label for="blood_type" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Golongan Darah
                    </label>
                    <select id="blood_type" 
                            name="blood_type" 
                            class="w-full px-3.5 py-2.5 rounded-xl border @error('blood_type') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        <option value="">-- Pilih Golongan Darah (Opsional) --</option>
                        <option value="A" {{ old('blood_type') === 'A' ? 'selected' : '' }}>Golongan A</option>
                        <option value="B" {{ old('blood_type') === 'B' ? 'selected' : '' }}>Golongan B</option>
                        <option value="AB" {{ old('blood_type') === 'AB' ? 'selected' : '' }}>Golongan AB</option>
                        <option value="O" {{ old('blood_type') === 'O' ? 'selected' : '' }}>Golongan O</option>
                        <option value="-" {{ old('blood_type') === '-' ? 'selected' : '' }}>Tidak Tahu / Belum Cek (-)</option>
                    </select>
                    @error('blood_type')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat Domisili -->
            <div>
                <label for="address" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Alamat Domisili Lengkap
                </label>
                <textarea id="address" 
                          name="address" 
                          rows="3" 
                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota..."
                          class="w-full px-3.5 py-2.5 rounded-xl border @error('address') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- SATUSEHAT IHS Number (Opsional) -->
            <div>
                <label for="satusehat_ihs_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nomor IHS SATUSEHAT (Opsional)
                </label>
                <input type="text" 
                       id="satusehat_ihs_number" 
                       name="satusehat_ihs_number" 
                       value="{{ old('satusehat_ihs_number') }}" 
                       placeholder="Contoh: P10002819283 (dapat diisi jika pasien telah memiliki IHS Number)" 
                       class="w-full px-3.5 py-2.5 rounded-xl border @error('satusehat_ihs_number') border-rose-300 bg-rose-50/30 text-rose-900 @else border-slate-200 bg-slate-50/50 text-slate-900 @enderror text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all font-mono">
                @error('satusehat_ihs_number')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @else
                    <p class="mt-1 text-[11px] text-slate-400">Field persiapan integrasi SATUSEHAT Kemenkes (dapat dilengkapi nanti).</p>
                @enderror
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('patients.index') }}" 
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm transition-all shadow-sm shadow-teal-200 hover:shadow-md">
                Simpan & Daftarkan Pasien
            </button>
        </div>
    </form>
</div>
@endsection
