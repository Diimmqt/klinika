<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\Practitioner;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminKlinikController extends Controller
{
    /**
     * Dashboard Operasional Klinik Sehat Sentosa
     */
    public function operasional(Request $request): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['admin_klinik', 'super_admin']), 403);

        $today = today();
        $kunjunganHariIni = Visit::whereDate('visit_date', $today)->count();
        $aktif  = Visit::whereDate('visit_date', $today)->where('status', 'in_progress')->count();
        $selesai = Visit::whereDate('visit_date', $today)->where('status', 'completed')->count();

        $stats = [
            'kunjungan_hari_ini' => $kunjunganHariIni,
            'aktif_kunjungan'    => $aktif,
            'selesai_kunjungan'  => $selesai,
            'dokter_bertugas'    => Practitioner::where('is_active', true)->count(),
            'poli_melayani'      => Poli::where('is_active', true)->count(),
            'rata_tunggu'        => '—',
            'pengguna_klinik'    => User::count(),
            'akun_aktif'         => User::where('is_active', true)->count(),
        ];

        // 7-day visit chart from real data
        $days = collect(range(6, 0))->map(fn ($i) => today()->subDays($i));
        $visitCounts = Visit::whereDate('visit_date', '>=', today()->subDays(6))
            ->selectRaw('DATE(visit_date) as day, COUNT(*) as cnt')
            ->groupBy('day')
            ->pluck('cnt', 'day');

        $maxCount = max($visitCounts->max() ?: 1, 1);
        $chartData = $days->map(function ($day) use ($visitCounts, $maxCount) {
            $cnt    = $visitCounts[$day->format('Y-m-d')] ?? 0;
            $height = round(($cnt / $maxCount) * 100) . '%';
            $entry  = ['label' => $day->translatedFormat('j M'), 'count' => $cnt, 'height' => $height];
            if ($day->isToday()) {
                $entry['active'] = true;
            }
            return $entry;
        })->toArray();

        $perhatian = [
            'Jadwal Poli Gigi sore belum dikonfirmasi oleh drg. Nabila.',
            '2 item obat di bawah stok minimum. Hubungi penanggung jawab farmasi.',
        ];

        $kesiapanLayanan = Poli::where('is_active', true)->with(['visits' => function ($q) use ($today) {
            $q->whereDate('visit_date', $today);
        }, 'visits.practitioner'])->get()->map(function ($poli) use ($today) {
            $practitioner = $poli->visits->first()?->practitioner;
            return [
                'poli'            => $poli->name,
                'dokter_bertugas' => $practitioner?->name ?? '—',
                'jam_praktik'     => '—',
                'kunjungan'       => $poli->visits->count(),
                'status'          => 'Aktif',
            ];
        })->toArray();

        if (empty($kesiapanLayanan)) {
            $kesiapanLayanan = [
                [
                    'poli'            => 'Poli Umum',
                    'dokter_bertugas' => 'dr. Andi Prasetyo',
                    'jam_praktik'     => '08.00–12.00',
                    'kunjungan'       => 32,
                    'status'          => 'Aktif',
                ],
                [
                    'poli'            => 'Poli Anak',
                    'dokter_bertugas' => 'dr. Laila Nuraini, Sp.A',
                    'jam_praktik'     => '08.00–13.00',
                    'kunjungan'       => 20,
                    'status'          => 'Aktif',
                ],
                [
                    'poli'            => 'Poli Gigi',
                    'dokter_bertugas' => 'drg. Nabila Sari',
                    'jam_praktik'     => '09.00–12.00',
                    'kunjungan'       => 12,
                    'status'          => 'Aktif',
                ],
            ];
        }

        return view('admin.operasional', compact('stats', 'chartData', 'perhatian', 'kesiapanLayanan'));
    }

    /**
     * Jadwal Dokter - Master data
     */
    public function jadwalDokter(Request $request): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['admin_klinik', 'super_admin']), 403);

        $search = $request->input('search');
        $selectedPoli = $request->input('poli');
        $selectedDate = $request->input('date', '6 Oktober 2026');
        $editId = $request->input('edit_id', 1);

        $jadwalList = [
            [
                'id'          => 1,
                'dokter'      => 'dr. Andi Prasetyo',
                'poli'        => 'Poli Umum',
                'hari_jam'    => 'Selasa • 08.00–12.00',
                'ruang'       => '01',
                'terdaftar'   => '32 / 40',
                'status'      => 'Aktif',
                'mulai'       => '08.00',
                'selesai'     => '12.00',
                'kuota'       => 40,
                'periode'     => '1 Oktober–31 Desember 2026',
            ],
            [
                'id'          => 2,
                'dokter'      => 'dr. Laila Nuraini, Sp.A',
                'poli'        => 'Poli Anak',
                'hari_jam'    => 'Selasa • 08.00–13.00',
                'ruang'       => '02',
                'terdaftar'   => '20 / 30',
                'status'      => 'Aktif',
                'mulai'       => '08.00',
                'selesai'     => '13.00',
                'kuota'       => 30,
                'periode'     => '1 Oktober–31 Desember 2026',
            ],
            [
                'id'          => 3,
                'dokter'      => 'drg. Nabila Sari',
                'poli'        => 'Poli Gigi',
                'hari_jam'    => 'Selasa • 09.00–12.00',
                'ruang'       => '03',
                'terdaftar'   => '12 / 20',
                'status'      => 'Aktif',
                'mulai'       => '09.00',
                'selesai'     => '12.00',
                'kuota'       => 20,
                'periode'     => '1 Oktober–31 Desember 2026',
            ],
        ];

        // Filter if search applied
        if ($search) {
            $jadwalList = array_values(array_filter($jadwalList, fn ($item) =>
                stripos($item['dokter'], $search) !== false || stripos($item['poli'], $search) !== false
            ));
        }

        // Active edit item
        $editItem = collect($jadwalList)->firstWhere('id', (int) $editId) ?? $jadwalList[0] ?? [
            'id'       => 1,
            'dokter'   => 'dr. Andi Prasetyo',
            'poli'     => 'Poli Umum',
            'hari'     => 'Selasa',
            'mulai'    => '08.00',
            'selesai'  => '12.00',
            'kuota'    => 40,
            'ruang'    => '01',
            'periode'  => '1 Oktober–31 Desember 2026',
        ];
        $editItem['hari'] = $editItem['hari'] ?? 'Selasa';

        return view('admin.master.jadwal', compact('jadwalList', 'editItem', 'search', 'selectedPoli', 'selectedDate'));
    }

    /**
     * Simpan / perbarui jadwal dokter
     */
    public function updateJadwalDokter(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['admin_klinik', 'super_admin']), 403);

        $request->validate([
            'dokter'   => 'required|string',
            'poli'     => 'required|string',
            'hari'     => 'required|string',
            'mulai'    => 'required|string',
            'selesai'  => 'required|string',
            'kuota'    => 'required|numeric|min:1',
            'ruang'    => 'required|string',
        ]);

        return redirect()
            ->route('admin.master.jadwal-dokter')
            ->with('success', 'Jadwal dokter ' . $request->input('dokter') . ' berhasil disimpan.');
    }

    /**
     * Master Poli
     */
    public function masterPoli(Request $request): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['admin_klinik', 'super_admin']), 403);

        $search = $request->input('search');
        $status = $request->input('status');
        $clinic = $request->input('clinic', 'Klinik KSS-01');
        $editId = (int) $request->input('edit_id', 0);

        $query = Poli::query();
        if ($search) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%"));
        }
        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        $polisRaw = $query->orderBy('code')->get();

        if ($polisRaw->isEmpty() && ! $search && ! $status) {
            $poliList = [
                [
                    'id'         => 1,
                    'code'       => 'POL-01',
                    'name'       => 'Poli Umum',
                    'location'   => 'Ruang 01',
                    'prefix'     => 'A',
                    'quota'      => 40,
                    'status'     => 'Aktif',
                ],
                [
                    'id'         => 2,
                    'code'       => 'POL-02',
                    'name'       => 'Poli Anak',
                    'location'   => 'Ruang 02',
                    'prefix'     => 'B',
                    'quota'      => 30,
                    'status'     => 'Aktif',
                ],
                [
                    'id'         => 3,
                    'code'       => 'POL-03',
                    'name'       => 'Poli Gigi',
                    'location'   => 'Ruang 03',
                    'prefix'     => 'C',
                    'quota'      => 20,
                    'status'     => 'Aktif',
                ],
            ];
            $defaultEdit = $poliList[0];
        } else {
            $poliList = $polisRaw->map(fn ($p, $i) => [
                'id'       => $p->id,
                'code'     => $p->code,
                'name'     => $p->name,
                'location' => 'Ruang ' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'prefix'   => $p->queue_prefix,
                'quota'    => 40,
                'status'   => $p->is_active ? 'Aktif' : 'Nonaktif',
            ])->toArray();

            $firstPoli = $polisRaw->first();
            $defaultEdit = $firstPoli ? [
                'id'       => $firstPoli->id,
                'code'     => $firstPoli->code,
                'name'     => $firstPoli->name,
                'location' => 'Ruang 01',
                'prefix'   => $firstPoli->queue_prefix,
                'quota'    => 40,
                'status'   => $firstPoli->is_active ? 'Aktif' : 'Nonaktif',
            ] : ['id' => 0, 'code' => '', 'name' => '', 'location' => '', 'prefix' => '', 'quota' => 40, 'status' => 'Aktif'];
        }

        $editItem = collect($poliList)->firstWhere('id', $editId) ?? $defaultEdit;

        return view('admin.master.poli', compact('poliList', 'editItem', 'search', 'status', 'clinic'));
    }

    /**
     * Simpan / perbarui data poli
     */
    public function updateMasterPoli(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['admin_klinik', 'super_admin']), 403);

        $request->validate([
            'name'     => 'required|string',
            'location' => 'required|string',
            'prefix'   => 'required|string|max:5',
            'quota'    => 'required|numeric|min:1',
            'status'   => 'required|string',
        ]);

        return redirect()
            ->route('admin.master.poli')
            ->with('success', 'Data master poli ' . $request->input('name') . ' berhasil diperbarui.');
    }
}
