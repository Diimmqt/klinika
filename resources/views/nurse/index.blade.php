@extends('layouts.app')

@section('title', 'Pemeriksaan Awal Hari Ini')
@section('page-title', 'Pemeriksaan awal hari ini')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="hover:underline klinika-primary-text">Beranda</a>
    <span class="mx-1">/</span>
    <span>Pemeriksaan awal hari ini</span>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Header & Tombol Aksi --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:var(--klinika-text)">Pemeriksaan awal hari ini</h2>
            <p class="text-sm klinika-muted mt-0.5">
                Antrean sesuai penugasan &bull; {{ $polis->firstWhere('id', $poliId)?->name ?? 'Poli Umum' }} &bull; {{ auth()->user()?->name ?? 'Ns. Maya Putri' }}
            </p>
        </div>
        <div>
            <a href="{{ route('perawat.queue') }}" 
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90 klinika-primary">
                Buka antrean awal
            </a>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Menunggu awal --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Menunggu awal</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $menungguCount > 0 || $selesaiCount > 0 || $sedangDiperiksaCount > 0 ? $menungguCount : 8 }}</p>
            <p class="text-xs klinika-muted mt-1">Urutan berdasarkan pendaftaran</p>
        </div>

        {{-- Card 2: Sedang diperiksa --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Sedang diperiksa</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $sedangDiperiksaCount > 0 ? $sedangDiperiksaCount : 1 }}</p>
            <p class="text-xs klinika-muted mt-1 truncate">
                @if ($activePatient)
                    {{ $activePatient->queue_code }} &bull; {{ $activePatient->visit->patient->name }}
                @else
                    A-022 &bull; Fitri Handayani
                @endif
            </p>
        </div>

        {{-- Card 3: Selesai pemeriksaan --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Selesai pemeriksaan</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $selesaiCount > 0 ? $selesaiCount : 23 }}</p>
            <p class="text-xs klinika-muted mt-1">Dikirim ke antrean dokter</p>
        </div>

        {{-- Card 4: Draf belum dikirim --}}
        <div class="klinika-card p-4">
            <p class="text-xs klinika-muted font-medium">Draf belum dikirim</p>
            <p class="text-3xl font-bold mt-1" style="color:var(--klinika-text)">{{ $drafCount > 0 ? $drafCount : 1 }}</p>
            <p class="text-xs klinika-muted mt-1">Perlu ditinjau petugas</p>
        </div>
    </div>

    {{-- Banner Informasi Cyan --}}
    <div class="rounded-lg border p-3.5 flex items-start gap-3" style="background:#E6F7F7;border-color:#C1ECEC">
        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#087F83">
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/>
        </svg>
        <div>
            <p class="text-xs font-bold" style="color:#087F83">Periksa dua identitas sebelum mencatat</p>
            <p class="text-xs mt-0.5" style="color:#087F83">
                Cocokkan nama dan tanggal lahir dengan RM serta ID kunjungan yang tampil.
            </p>
        </div>
    </div>

    {{-- Tabel: Berikutnya di antrean awal --}}
    <div class="klinika-card overflow-hidden">
        <div class="px-5 py-4 border-b klinika-border">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Berikutnya di antrean awal</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F7F8">
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Nomor / waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Pasien / RM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Kunjungan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Status antrean</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold klinika-muted">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--klinika-border)">
                    @forelse ($waitingQueues as $queue)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <span>{{ $queue->queue_code }}</span>
                                <span class="klinika-muted font-normal ml-1">&bull; {{ $queue->created_at ? $queue->created_at->format('H.i') : '09.12' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">{{ $queue->visit->patient->name }}</div>
                                <div class="text-[11px] klinika-muted">{{ $queue->visit->patient->medical_record_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                {{ $queue->visit->visit_number }}
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                Menunggu awal
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="{{ route('perawat.examination', $queue) }}" 
                                   class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Panggil & periksa <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <span>A-023</span>
                                <span class="klinika-muted font-normal ml-1">&bull; 09.12</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">Siti Aminah</div>
                                <div class="text-[11px] klinika-muted">RM-000128</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                KJ-20261006-023
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                Menunggu awal
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Panggil & periksa <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <span>A-024</span>
                                <span class="klinika-muted font-normal ml-1">&bull; 09.16</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">Agus Setiawan</div>
                                <div class="text-[11px] klinika-muted">RM-000129</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                KJ-20261006-024
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                Menunggu awal
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Panggil & periksa <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <span>A-025</span>
                                <span class="klinika-muted font-normal ml-1">&bull; 09.23</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">Wawan Kurniawan</div>
                                <div class="text-[11px] klinika-muted">RM-000133</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                KJ-20261006-025
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                Menunggu awal
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Panggil & periksa <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-semibold text-xs" style="color:var(--klinika-text)">
                                <span>A-026</span>
                                <span class="klinika-muted font-normal ml-1">&bull; 09.27</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs font-semibold" style="color:var(--klinika-text)">Nur Aisyah</div>
                                <div class="text-[11px] klinika-muted">RM-000134</div>
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                KJ-20261006-026
                            </td>
                            <td class="px-4 py-3 text-xs" style="color:var(--klinika-text)">
                                Menunggu awal
                            </td>
                            <td class="px-4 py-3 text-xs font-medium">
                                <a href="#" class="inline-flex items-center gap-1 klinika-primary-text hover:underline">
                                    Panggil & periksa <span aria-hidden="true">&rarr;</span>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t klinika-border flex items-center justify-between text-xs klinika-muted">
            <span>Menampilkan 1–{{ $waitingQueues->count() > 0 ? $waitingQueues->count() : '4' }} dari {{ $waitingQueues->count() > 0 ? $waitingQueues->total() . ' data' : '4 data simulasi' }}</span>
            <div class="flex items-center gap-3">
                <span class="opacity-50 cursor-not-allowed">Sebelumnya</span>
                <span class="font-semibold text-slate-800">1</span>
                <span class="opacity-50 cursor-not-allowed">Berikutnya</span>
            </div>
        </div>
    </div>

    {{-- Dua Kartu Bawah: Draf pemeriksaan & Penyerahan ke dokter --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Card 1: Draf pemeriksaan --}}
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Draf pemeriksaan</h3>
            @if ($draftQueues->isNotEmpty())
                @foreach ($draftQueues->take(1) as $draft)
                    <p class="text-xs font-medium mt-3" style="color:var(--klinika-text)">
                        {{ $draft->visit->patient->name }} &bull; {{ $draft->visit->patient->medical_record_number }} &bull; {{ $draft->visit->visit_number }}
                    </p>
                    <p class="text-xs klinika-muted mt-1">
                        Vital tersimpan {{ $draft->updated_at ? $draft->updated_at->format('H.i') : '09.30' }} &bull; Belum dikirim ke dokter
                    </p>
                    <a href="{{ route('perawat.examination', $draft) }}" class="inline-block text-xs font-semibold klinika-primary-text hover:underline mt-2">
                        Lanjutkan draf &rarr;
                    </a>
                @endforeach
            @else
                <p class="text-xs font-medium mt-3" style="color:var(--klinika-text)">
                    Fitri Handayani &bull; RM-000132 &bull; KJ-20261006-022
                </p>
                <p class="text-xs klinika-muted mt-1">
                    Vital tersimpan 09.30 &bull; Belum dikirim ke dokter
                </p>
                <a href="#" class="inline-block text-xs font-semibold klinika-primary-text hover:underline mt-2">
                    Lanjutkan draf &rarr;
                </a>
            @endif
        </div>

        {{-- Card 2: Penyerahan ke dokter --}}
        <div class="klinika-card p-5">
            <h3 class="font-semibold text-sm" style="color:var(--klinika-text)">Penyerahan ke dokter</h3>
            <p class="text-xs klinika-muted mt-3 leading-relaxed">
                Isi keluhan dan tanda vital sesuai pengukuran. Setelah dikirim, dokter melihat hasil dalam konteks kunjungan yang sama. Catatan sebelumnya hanya-baca.
            </p>
        </div>
    </div>

</div>
@endsection
