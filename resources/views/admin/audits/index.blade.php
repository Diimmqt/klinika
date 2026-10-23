@extends('layouts.app')

@section('title', 'Audit Aktivitas')
@section('page-title', 'Audit aktivitas')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:opacity-80" style="color:var(--klinika-primary)">Beranda</a>
    <span>/</span>
    <span style="color:var(--klinika-text)">Audit aktivitas</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Page Heading --}}
    <div>
        <h2 class="text-2xl font-bold tracking-tight" style="color:var(--klinika-text)">Audit aktivitas</h2>
        <p class="text-sm klinika-muted mt-1">
            Telusuri aktivitas administratif lintas klinik. Catatan audit hanya dapat dibaca.
        </p>
    </div>

    {{-- Card 1: Pencarian dan filter aktivitas --}}
    <div class="klinika-card p-6 space-y-4">
        <h3 class="font-bold text-base" style="color:var(--klinika-text)">Pencarian dan filter aktivitas</h3>

        <form method="GET" action="{{ route('admin.audits.index') }}" class="space-y-4">
            {{-- Row 1 --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6">
                    <label for="q" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Cari aktivitas
                    </label>
                    <input type="text" id="q" name="q" value="{{ $search }}"
                           placeholder="Aktor, tindakan, atau referensi"
                           class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                           style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                           onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                </div>

                <div class="md:col-span-3">
                    <label for="clinic" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Klinik
                    </label>
                    <select id="clinic" name="clinic"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="Semua klinik" {{ $clinic === 'Semua klinik' || !$clinic ? 'selected' : '' }}>Semua klinik</option>
                        <option value="KSS-01" {{ $clinic === 'KSS-01' ? 'selected' : '' }}>KSS-01 - Klinik Bandung</option>
                        <option value="KBS-02" {{ $clinic === 'KBS-02' ? 'selected' : '' }}>KBS-02 - Klinik Surabaya</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label for="date" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Tanggal
                    </label>
                    <select id="date" name="date"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="6 Oktober 2026" {{ $date === '6 Oktober 2026' ? 'selected' : '' }}>6 Oktober 2026</option>
                        <option value="Hari ini" {{ $date === 'Hari ini' ? 'selected' : '' }}>Hari ini</option>
                        <option value="7 hari terakhir" {{ $date === '7 hari terakhir' ? 'selected' : '' }}>7 hari terakhir</option>
                        <option value="30 hari terakhir" {{ $date === '30 hari terakhir' ? 'selected' : '' }}>30 hari terakhir</option>
                    </select>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="module" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Modul
                    </label>
                    <select id="module" name="module"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="Semua modul" {{ $module === 'Semua modul' || !$module ? 'selected' : '' }}>Semua modul</option>
                        <option value="Data klinik" {{ $module === 'Data klinik' ? 'selected' : '' }}>Data klinik</option>
                        <option value="Jadwal dokter" {{ $module === 'Jadwal dokter' ? 'selected' : '' }}>Jadwal dokter</option>
                        <option value="Hak akses" {{ $module === 'Hak akses' ? 'selected' : '' }}>Hak akses</option>
                        <option value="Autentikasi" {{ $module === 'Autentikasi' ? 'selected' : '' }}>Autentikasi</option>
                        <option value="Akun pengguna" {{ $module === 'Akun pengguna' ? 'selected' : '' }}>Akun pengguna</option>
                        <option value="Konfigurasi sistem" {{ $module === 'Konfigurasi sistem' ? 'selected' : '' }}>Konfigurasi sistem</option>
                    </select>
                </div>

                <div>
                    <label for="actor" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Aktor
                    </label>
                    <select id="actor" name="actor"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="Semua aktor" {{ $actor === 'Semua aktor' || !$actor ? 'selected' : '' }}>Semua aktor</option>
                        <option value="Fajar Pratama" {{ $actor === 'Fajar Pratama' ? 'selected' : '' }}>Fajar Pratama</option>
                        <option value="Ratna Dewi" {{ $actor === 'Ratna Dewi' ? 'selected' : '' }}>Ratna Dewi</option>
                        <option value="Sistem" {{ $actor === 'Sistem' ? 'selected' : '' }}>Sistem</option>
                    </select>
                </div>

                <div>
                    <label for="action_type" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Tindakan
                    </label>
                    <select id="action_type" name="action_type"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="Semua tindakan" {{ $action === 'Semua tindakan' || !$action ? 'selected' : '' }}>Semua tindakan</option>
                        <option value="Ubah kontak administrasi" {{ $action === 'Ubah kontak administrasi' ? 'selected' : '' }}>Ubah kontak administrasi</option>
                        <option value="Memperbarui jadwal" {{ $action === 'Memperbarui jadwal' ? 'selected' : '' }}>Memperbarui jadwal</option>
                        <option value="Menetapkan role Kasir" {{ $action === 'Menetapkan role Kasir' ? 'selected' : '' }}>Menetapkan role Kasir</option>
                        <option value="Menolak 3 upaya login" {{ $action === 'Menolak 3 upaya login' ? 'selected' : '' }}>Menolak 3 upaya login</option>
                    </select>
                </div>

                <div>
                    <label for="result" class="block text-xs font-semibold mb-1.5" style="color:var(--klinika-text)">
                        Hasil
                    </label>
                    <select id="result" name="result"
                            class="w-full px-3.5 py-2.5 rounded-md border text-sm outline-none transition-colors"
                            style="border-color:var(--klinika-border);background:#F4F7F8;color:var(--klinika-text)"
                            onfocus="this.style.borderColor='var(--klinika-primary)'" onblur="this.style.borderColor='var(--klinika-border)'">
                        <option value="Semua hasil" {{ $result === 'Semua hasil' || !$result ? 'selected' : '' }}>Semua hasil</option>
                        <option value="Tercatat" {{ $result === 'Tercatat' ? 'selected' : '' }}>Tercatat</option>
                        <option value="Ditolak" {{ $result === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="px-5 py-2.5 rounded-md text-white text-sm font-medium klinika-primary transition-colors">
                    Terapkan filter
                </button>
                <a href="{{ route('admin.audits.index') }}"
                   class="px-5 py-2.5 rounded-md border text-sm font-medium transition-colors bg-white hover:bg-slate-50"
                   style="border-color:var(--klinika-border);color:var(--klinika-text)">
                    Reset filter
                </a>
            </div>
        </form>
    </div>

    {{-- Card 2: Aktivitas Table --}}
    <div class="klinika-card p-6 space-y-4">
        <h3 class="font-bold text-base" style="color:var(--klinika-text)">Aktivitas • {{ $date ?? '6 Oktober 2026' }}</h3>

        <div class="overflow-x-auto border rounded-lg" style="border-color:var(--klinika-border)">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                        <th class="py-3 px-4 w-20">Waktu</th>
                        <th class="py-3 px-4 w-36">Aktor</th>
                        <th class="py-3 px-3 w-24">Klinik</th>
                        <th class="py-3 px-4 w-32">Modul</th>
                        <th class="py-3 px-4">Tindakan</th>
                        <th class="py-3 px-3 w-24">Hasil</th>
                        <th class="py-3 px-4 w-20 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse($audits as $item)
                        @php
                            $isSelected = $selectedAudit && $selectedAudit['id'] === $item['id'];
                        @endphp
                        <tr class="transition-colors {{ $isSelected ? 'bg-teal-50/40' : 'hover:bg-slate-50/60' }}"
                            style="{{ $isSelected ? 'border-left: 3px solid var(--klinika-primary);' : '' }}">
                            <td class="py-3.5 px-4 font-bold" style="color:var(--klinika-text)">
                                {{ $item['time'] }}
                            </td>
                            <td class="py-3.5 px-4 font-medium" style="color:var(--klinika-text)">
                                {{ $item['actor'] }}
                            </td>
                            <td class="py-3.5 px-3 font-mono text-[11px] text-slate-600">
                                {{ $item['clinic'] }}
                            </td>
                            <td class="py-3.5 px-4" style="color:var(--klinika-text)">
                                {{ $item['module'] }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium" style="color:var(--klinika-text)">{{ $item['action'] }}</span>
                                @if(!empty($item['is_simulation']))
                                    <span class="block text-[10px] text-slate-400 font-mono mt-0.5">SIMULASI • {{ $item['id'] }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3">
                                @if($item['result'] === 'Ditolak')
                                    <span class="text-rose-600 font-medium">Ditolak</span>
                                @else
                                    <span class="text-slate-600">Tercatat</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($isSelected)
                                    <span class="text-xs font-semibold" style="color:var(--klinika-primary)">Dipilih</span>
                                @else
                                    <a href="{{ request()->fullUrlWithQuery(['detail' => $item['id']]) }}"
                                       class="text-xs font-medium hover:underline inline-flex items-center gap-1"
                                       style="color:var(--klinika-primary)">
                                        <span>Lihat</span>
                                        <span>&rarr;</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-xs klinika-muted">
                                Tidak ada aktivitas yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Table footer / Pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs klinika-muted pt-1">
            <div>
                Menampilkan {{ $audits->count() }} aktivitas • 1 baris simulasi tambahan
            </div>
            <div class="flex items-center gap-3">
                <span class="text-slate-400 cursor-not-allowed">Sebelumnya</span>
                <span class="px-2.5 py-1 rounded font-semibold text-white klinika-primary">1</span>
                <span class="text-slate-400 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>

        <p class="text-[11px] klinika-muted pt-1">
            Waktu dalam WIB. Detail perubahan tidak diasumsikan untuk tiga aktivitas pada 09.40, 09.22, dan 09.05.
        </p>
    </div>

    {{-- Card 3: Detail aktivitas (shown when an audit is selected) --}}
    @if($selectedAudit)
    <div class="klinika-card p-6 space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="font-bold text-base" style="color:var(--klinika-text)">Detail aktivitas • {{ $selectedAudit['id'] }}</h3>
                <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $selectedAudit['title'] }}</p>
                <p class="text-[11px] klinika-muted mt-1">
                    {{ $selectedAudit['datetime_label'] }} • {{ $selectedAudit['actor'] }} • {{ $selectedAudit['role'] }} • {{ $selectedAudit['clinic'] }} • Modul {{ strtolower($selectedAudit['module']) }}
                </p>
            </div>
            <div>
                @if(!empty($selectedAudit['is_simulation']))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                          style="background:#E6F5F5;color:var(--klinika-primary);border-color:#B2DEDE">
                        Peristiwa simulasi
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border"
                          style="background:#F1F5F9;color:#475569;border-color:#CBD5E1">
                        Tercatat
                    </span>
                @endif
            </div>
        </div>

        @if(!empty($selectedAudit['changes']))
        <div class="overflow-x-auto border rounded-lg" style="border-color:var(--klinika-border)">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="font-semibold klinika-muted border-b" style="background:#F4F7F8;border-color:var(--klinika-border)">
                        <th class="py-3 px-4 w-1/3">Bidang</th>
                        <th class="py-3 px-4 w-1/3">Sebelum</th>
                        <th class="py-3 px-4 w-1/3">Sesudah</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @foreach($selectedAudit['changes'] as $change)
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-4 font-semibold" style="color:var(--klinika-text)">{{ $change['field'] }}</td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-600">{{ $change['before'] }}</td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-800 font-medium">{{ $change['after'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <p class="text-[11px] klinika-muted pt-1">
            Contoh sebelum/sesudah ini hanya untuk peristiwa simulasi, bukan perubahan historis yang telah tercatat pada referensi.
        </p>
    </div>
    @endif

    {{-- Cyan/Teal Info Banner --}}
    <div class="rounded-lg border p-4" style="background:#EEF9F9;border-color:#B2DEDE">
        <div class="flex items-start gap-2.5">
            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--klinika-primary)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-xs font-semibold" style="color:var(--klinika-primary)">Audit hanya baca</p>
                <p class="text-[11px] mt-0.5 leading-relaxed" style="color:var(--klinika-primary)">
                    Catatan tidak dapat diubah atau dihapus. Informasi rahasia, kata sandi, token, dan isi pemeriksaan klinis tidak ditampilkan.
                </p>
            </div>
        </div>
    </div>

    {{-- Footer Note --}}
    <div class="flex items-center gap-4 text-[11px] klinika-muted border-t pt-4" style="border-color:var(--klinika-border)">
        <span>Data simulasi • Bukan data pasien nyata</span>
        <span>|</span>
        <span>Klinika RME • Akses tercatat</span>
    </div>

</div>
@endsection
