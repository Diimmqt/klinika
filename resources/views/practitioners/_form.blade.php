@php
    $isEdit = isset($practitioner);
    $checkedPolis = collect(old('poli_ids', $selectedPoliIds ?? []))
        ->map(fn ($id) => (int) $id)
        ->all();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Nama tenaga kesehatan <span class="text-rose-500">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name', $practitioner->name ?? '') }}"
               placeholder="Contoh: dr. Andi Prasetyo"
               required
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('name')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="practitioner_type" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Profesi <span class="text-rose-500">*</span>
        </label>
        <select id="practitioner_type"
                name="practitioner_type"
                required
                class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
                style="color:var(--klinika-text)">
            <option value="">-- Pilih profesi --</option>
            @foreach($professions as $value => $label)
                <option value="{{ $value }}" @selected(old('practitioner_type', $practitioner->practitioner_type ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('practitioner_type')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label for="str_number" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Nomor STR
        </label>
        <input type="text"
               id="str_number"
               name="str_number"
               value="{{ old('str_number', $practitioner->str_number ?? '') }}"
               placeholder="Opsional"
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('str_number')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sip_number" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Nomor SIP
        </label>
        <input type="text"
               id="sip_number"
               name="sip_number"
               value="{{ old('sip_number', $practitioner->sip_number ?? '') }}"
               placeholder="Opsional"
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('sip_number')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="satusehat_practitioner_id" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            ID SATUSEHAT
        </label>
        <input type="text"
               id="satusehat_practitioner_id"
               name="satusehat_practitioner_id"
               value="{{ old('satusehat_practitioner_id', $practitioner->satusehat_practitioner_id ?? '') }}"
               placeholder="Opsional"
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('satusehat_practitioner_id')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="user_id" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Tautkan Akun Pengguna (Opsional)
        </label>
        <select id="user_id"
                name="user_id"
                class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
                style="color:var(--klinika-text)">
            <option value="">-- Belum ditautkan --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" @selected((string) old('user_id', $practitioner->user_id ?? '') === (string) $u->id)>
                    {{ $u->name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
        @error('user_id')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="is_active" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Status Tenaga Kesehatan <span class="text-rose-500">*</span>
        </label>
        @php
            $activeValue = (string) old('is_active', $isEdit ? ($practitioner->is_active ? '1' : '0') : '1');
        @endphp
        <select id="is_active"
                name="is_active"
                class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
                style="color:var(--klinika-text)">
            <option value="1" @selected($activeValue === '1')>Aktif</option>
            <option value="0" @selected($activeValue === '0')>Nonaktif</option>
        </select>
        @error('is_active')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- Poli assignment using existing schedules table --}}
<div class="space-y-2 pt-2">
    <div>
        <label class="block text-xs font-semibold" style="color:var(--klinika-text)">
            Hubungkan dengan Poli
        </label>
        <p class="text-[11px] klinika-muted">Pilih poli tempat tenaga kesehatan bertugas (disimpan melalui relasi tabel `schedules` yang tersedia tanpa membuat tabel baru).</p>
    </div>

    @if($polis->isEmpty())
        <div class="rounded-lg border klinika-border p-3 text-xs klinika-muted" style="background:var(--klinika-bg)">
            Belum ada data poli aktif. <a href="{{ route('polis.create') }}" class="font-semibold klinika-primary-text hover:underline">Tambah poli terlebih dahulu →</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
            @foreach($polis as $poli)
                <label class="flex items-center gap-2.5 rounded-lg border klinika-border px-3.5 py-2.5 bg-white cursor-pointer hover:bg-slate-50/70">
                    <input type="checkbox"
                           name="poli_ids[]"
                           value="{{ $poli->id }}"
                           @checked(in_array($poli->id, $checkedPolis, true))
                           class="w-4 h-4 rounded"
                           style="accent-color:var(--klinika-primary)">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold truncate" style="color:var(--klinika-text)">{{ $poli->name }}</div>
                        <div class="text-[11px] font-mono klinika-muted">{{ $poli->code }} • Prefix {{ $poli->queue_prefix }}</div>
                    </div>
                </label>
            @endforeach
        </div>
    @endif

    @error('poli_ids')
        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
    @enderror
    @error('poli_ids.*')
        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="pt-3 border-t klinika-border flex flex-wrap items-center justify-between gap-3">
    <span class="text-xs klinika-muted">* Wajib diisi</span>
    <div class="flex items-center gap-3">
        <a href="{{ $isEdit ? route('practitioners.show', $practitioner) : route('practitioners.index') }}"
           class="px-5 py-2.5 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors"
           style="color:var(--klinika-text)">
            Batal
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity">
            {{ $isEdit ? 'Simpan perubahan' : 'Simpan tenaga kesehatan' }}
        </button>
    </div>
</div>
