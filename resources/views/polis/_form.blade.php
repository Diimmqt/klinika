@php
    $isEdit = isset($poli);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="code" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Kode poli <span class="text-rose-500">*</span>
        </label>
        <input type="text"
               id="code"
               name="code"
               value="{{ old('code', $poli->code ?? '') }}"
               placeholder="Contoh: POLI-UMUM"
               required
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('code')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @else
            <p class="text-[11px] klinika-muted mt-1">Kode unik poli (otomatis huruf kapital).</p>
        @enderror
    </div>

    <div>
        <label for="name" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Nama poli <span class="text-rose-500">*</span>
        </label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name', $poli->name ?? '') }}"
               placeholder="Contoh: Poli Umum"
               required
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('name')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="queue_prefix" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Kode antrean <span class="text-rose-500">*</span>
        </label>
        <input type="text"
               id="queue_prefix"
               name="queue_prefix"
               maxlength="5"
               value="{{ old('queue_prefix', $poli->queue_prefix ?? '') }}"
               placeholder="Contoh: A"
               required
               class="w-full px-3.5 py-2.5 rounded-lg border klinika-border text-sm font-mono bg-white focus:outline-none"
               style="color:var(--klinika-text)">
        @error('queue_prefix')
            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
        @else
            <p class="text-[11px] klinika-muted mt-1">Huruf/angka unik awalan nomor antrean (maks 5 karakter).</p>
        @enderror
    </div>

    <div>
        <label for="is_active" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
            Status poli <span class="text-rose-500">*</span>
        </label>
        @php
            $activeValue = (string) old('is_active', $isEdit ? ($poli->is_active ? '1' : '0') : '1');
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

<div class="pt-3 border-t klinika-border flex flex-wrap items-center justify-between gap-3">
    <span class="text-xs klinika-muted">* Wajib diisi</span>
    <div class="flex items-center gap-3">
        <a href="{{ $isEdit ? route('polis.show', $poli) : route('polis.index') }}"
           class="px-5 py-2.5 rounded-lg border klinika-border text-xs font-semibold hover:bg-slate-50 transition-colors"
           style="color:var(--klinika-text)">
            Batal
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-lg klinika-primary text-white text-xs font-semibold hover:opacity-90 transition-opacity">
            {{ $isEdit ? 'Simpan perubahan' : 'Simpan poli' }}
        </button>
    </div>
</div>
