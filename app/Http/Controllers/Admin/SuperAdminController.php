<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()?->hasRole('admin_klinik') && !auth()->user()?->hasRole('super_admin')) {
            return redirect()->route('admin.operasional');
        }

        $stats = [
            'total_akun'         => 24,
            'role_aktif'         => 8,
            'aktivitas_hari_ini' => 2,
            'permintaan_akses'   => 2,
        ];

        $daftarAkun = [
            'total_akun'         => 24,
            'akun_aktif'         => 24,
            'permintaan_akses'   => 2,
            'pengguna_terbaru'   => [
                ['name' => 'Fajar Pratama', 'role' => 'Super Admin'],
                ['name' => 'Ratna Dewi',    'role' => 'Admin Klinik'],
            ],
        ];

        $hakAkses = [
            'role_aktif'     => 8,
            'akun_terbanyak' => 'Perawat (6)',
            'role_tunggal'   => 'Super Admin (1)',
            'role_prioritas' => [
                ['name' => 'Super Admin',  'count' => '1 akun'],
                ['name' => 'Admin Klinik', 'count' => '1 akun'],
            ],
        ];

        $masterData = [
            'poli'             => 3,
            'tenaga_kesehatan' => 5,
            'jadwal_hari_ini'  => 3,
            'master_prioritas' => [
                ['name' => 'Poli Umum',         'count' => '1'],
                ['name' => 'dr. Andi Prasetyo', 'count' => '1 jadwal'],
            ],
        ];

        $auditAktivitas = [
            'aktivitas_hari_ini'  => 2,
            'upaya_login_ditolak' => 3,
            'terakhir_diperbarui' => '09.40',
            'aktivitas_terbaru'   => [
                ['time' => '09.40 • Ratna Dewi', 'desc' => 'Jadwal diperbarui'],
                ['time' => '09.05 • Sistem',     'desc' => '3 login ditolak'],
            ],
        ];

        return view('admin.super.index', compact('stats', 'daftarAkun', 'hakAkses', 'masterData', 'auditAktivitas'));
    }
}
