@extends('layouts.app')

@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Data Pasien</h2>
            <p class="text-sm text-slate-500 mt-1">Cari identitas yang sudah terdaftar sebelum mendaftarkan pasien baru.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm shadow-sm shadow-teal-200 transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Pasien Baru</span>
            </a>
        </div>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Total Pasien Terdaftar</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['total']) }}</span>
                <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md">Total</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Pasien Bulan Ini</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['this_month']) }}</span>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ now()->translatedFormat('F') }}</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Pasien Laki-laki</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['male']) }}</span>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">L</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
            <span class="text-xs font-medium text-slate-500">Pasien Perempuan</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-900">{{ number_format($stats['female']) }}</span>
                <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-0.5 rounded-md">P</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('patients.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Input Pencarian -->
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama, NIK, No. RM, atau nomor telepon..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
            </div>

            <!-- Filter Gender -->
            <div class="md:col-span-2">
                <select name="gender" 
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ $gender === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                    <option value="P" {{ $gender === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                </select>
            </div>

            <!-- Urutkan -->
            <div class="md:col-span-2">
                <select name="sort" 
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama Z - A</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" 
                        class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm rounded-xl transition-colors">
                    Filter
                </button>
                @if ($search !== '' || $gender || $sort !== 'latest')
                    <a href="{{ route('patients.index') }}" 
                       class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm rounded-xl transition-colors text-center" 
                       title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Pasien -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5">No. RM & Nama Pasien</th>
                        <th class="py-3.5 px-5">NIK</th>
                        <th class="py-3.5 px-5">Jenis Kelamin</th>
                        <th class="py-3.5 px-5">Tempat & Tgl Lahir</th>
                        <th class="py-3.5 px-5">Kontak</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($patients as $patient)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- No RM & Nama -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-teal-700 font-mono text-xs">
                                    {{ $patient->medical_record_number }}
                                </div>
                                <a href="{{ route('patients.show', $patient) }}" class="font-semibold text-slate-900 hover:text-teal-600 transition-colors block mt-0.5">
                                    {{ $patient->name }}
                                </a>
                                @if($patient->blood_type)
                                    <span class="inline-block mt-1 text-[10px] bg-red-50 text-red-700 border border-red-200/60 font-semibold px-1.5 py-0.2 rounded">
                                        Gol: {{ $patient->blood_type }}
                                    </span>
                                @endif
                            </td>

                            <!-- NIK -->
                            <td class="py-4 px-5">
                                @if ($patient->nik)
                                    <span class="font-mono text-xs text-slate-700 bg-slate-100 px-2 py-1 rounded-md">
                                        {{ $patient->nik }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Belum ada NIK</span>
                                @endif
                            </td>

                            <!-- Gender & Usia -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2">
                                    @if ($patient->gender === 'L')
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200/50">
                                            Laki-laki
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-pink-50 text-pink-700 border border-pink-200/50">
                                            Perempuan
                                        </span>
                                    @endif
                                </div>
                                @if ($patient->age !== null)
                                    <span class="text-xs text-slate-500 block mt-1">Usia: {{ $patient->age }} th</span>
                                @endif
                            </td>

                            <!-- Tempat & Tanggal Lahir -->
                            <td class="py-4 px-5">
                                <div class="text-slate-900 font-medium">
                                    {{ $patient->birth_place }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    {{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}
                                </div>
                            </td>

                            <!-- Kontak -->
                            <td class="py-4 px-5">
                                <div class="text-slate-800 text-xs">
                                    {{ $patient->phone ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400 truncate max-w-xs mt-0.5" title="{{ $patient->address }}">
                                    {{ $patient->address ?? '-' }}
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 text-right space-x-1">
                                <a href="{{ route('patients.show', $patient) }}" 
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 text-xs font-semibold transition-colors"
                                   title="Lihat Detail">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>

                                <a href="{{ route('patients.edit', $patient) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-medium transition-colors"
                                   title="Ubah Data">
                                    Edit
                                </a>

                                <button type="button" 
                                        onclick="openDeleteModal('{{ $patient->id }}', '{{ addslashes($patient->name) }}', '{{ $patient->medical_record_number }}')"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-medium transition-colors"
                                        title="Hapus Data">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="max-w-sm mx-auto text-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-semibold text-slate-800 text-sm">Tidak ada data pasien ditemukan</h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        @if ($search !== '' || $gender)
                                            Coba ubah kata kunci pencarian atau reset filter.
                                        @else
                                            Belum ada pasien terdaftar. Silakan tambahkan pasien baru.
                                        @endif
                                    </p>
                                    <a href="{{ route('patients.create') }}" class="inline-block mt-4 text-xs font-semibold text-teal-600 hover:underline">
                                        + Daftarkan Pasien Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($patients->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Konfirmasi Hapus Pasien -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 space-y-4">
        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="text-center">
            <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Pasien</h3>
            <p class="text-xs text-slate-500 mt-1">
                Apakah Anda yakin ingin menghapus data pasien:
            </p>
            <p class="text-sm font-semibold text-slate-800 mt-2 p-2 rounded-lg bg-slate-50" id="deletePatientName">
                -
            </p>
            <p class="text-[11px] text-slate-400 mt-2">
                Data akan dihapus secara soft-delete dan dapat dipulihkan oleh administrator jika diperlukan.
            </p>
        </div>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="flex items-center gap-3 pt-2">
                <button type="button" 
                        onclick="closeDeleteModal()" 
                        class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-medium transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium transition-colors shadow-sm shadow-rose-200">
                    Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(patientId, patientName, mrn) {
        const form = document.getElementById('deleteForm');
        form.action = `/patients/${patientId}`;
        document.getElementById('deletePatientName').textContent = `${patientName} (${mrn})`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
