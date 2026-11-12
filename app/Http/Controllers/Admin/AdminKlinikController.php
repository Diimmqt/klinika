<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
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

        $stats = [
            'kunjungan_hari_ini' => 64,
            'aktif_kunjungan'    => 18,
            'selesai_kunjungan'  => 46,
            'dokter_bertugas'    => 4,
            'poli_melayani'      => 3,
            'rata_tunggu'        => '18 menit',
            'pengguna_klinik'    => 24,
            'akun_aktif'         => 21,
        ];

        // 7 days visit data for pure CSS/SVG chart
        $chartData = [
            ['label' => '30 Sep', 'count' => 42, 'height' => '62%'],
            ['label' => '1 Okt',  'count' => 55, 'height' => '81%'],
            ['label' => '2 Okt',  'count' => 48, 'height' => '70%'],
            ['label' => '3 Okt',  'count' => 68, 'height' => '100%'],
            ['label' => '4 Okt',  'count' => 54, 'height' => '79%'],
            ['label' => '5 Okt',  'count' => 49, 'height' => '72%'],
            ['label' => '6 Okt',  'count' => 64, 'height' => '94%', 'active' => true],
        ];

        $perhatian = [
            'Jadwal Poli Gigi sore belum dikonfirmasi oleh drg. Nabila.',
            '2 item obat di bawah stok minimum. Hubungi penanggung jawab farmasi.',
        ];

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
        $editId = $request->input('edit_id', 1);

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

        if ($search) {
            $poliList = array_values(array_filter($poliList, fn ($item) =>
                stripos($item['name'], $search) !== false || stripos($item['code'], $search) !== false
            ));
        }

        $editItem = collect($poliList)->firstWhere('id', (int) $editId) ?? $poliList[0] ?? [
            'id'       => 1,
            'code'     => 'POL-01',
            'name'     => 'Poli Umum',
            'location' => 'Ruang 01',
            'prefix'   => 'A',
            'quota'    => 40,
            'status'   => 'Aktif',
        ];

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
