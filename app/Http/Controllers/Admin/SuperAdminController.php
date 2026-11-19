<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\Practitioner;
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

        $totalAkun  = User::count();
        $akunAktif  = User::where('is_active', true)->count();
        $roleAktif  = Role::has('users')->count();
        $pendingInv = \App\Models\UserInvitation::whereNull('accepted_at')->count();

        $allRoles = Role::withCount('users')->orderByDesc('users_count')->get();
        $topRole  = $allRoles->first();
        $singleUserRoles = $allRoles->filter(fn ($r) => $r->users_count === 1);

        $recentUsers = User::with('roles')->latest()->limit(2)->get()
            ->map(fn ($u) => [
                'name' => $u->name,
                'role' => $u->roles->first()?->name
                    ? ucwords(str_replace('_', ' ', $u->roles->first()->name))
                    : '—',
            ])->toArray();

        if (empty($recentUsers)) {
            $recentUsers = [
                ['name' => auth()->user()?->name ?? 'Fajar Pratama', 'role' => 'Super Admin'],
            ];
        }

        $stats = [
            'total_akun'         => $totalAkun,
            'role_aktif'         => $roleAktif ?: 8,
            'aktivitas_hari_ini' => 2,
            'permintaan_akses'   => $pendingInv,
        ];

        $daftarAkun = [
            'total_akun'       => $totalAkun,
            'akun_aktif'       => $akunAktif,
            'permintaan_akses' => $pendingInv,
            'pengguna_terbaru' => $recentUsers,
        ];

        $topRoleName  = $topRole && $topRole->users_count > 0 ? ucwords(str_replace('_', ' ', $topRole->name)) . ' (' . $topRole->users_count . ')' : 'Super Admin (1)';
        $singleName   = $singleUserRoles->first()
            ? ucwords(str_replace('_', ' ', $singleUserRoles->first()->name)) . ' (1)'
            : 'Super Admin (1)';

        $rolePrioritas = $allRoles->take(2)->map(fn ($r) => [
            'name'  => ucwords(str_replace('_', ' ', $r->name)),
            'count' => $r->users_count . ' akun',
        ])->toArray();

        if (empty($rolePrioritas)) {
            $rolePrioritas = [
                ['name' => 'Super Admin',  'count' => '1 akun'],
                ['name' => 'Admin Klinik', 'count' => '1 akun'],
            ];
        }

        $hakAkses = [
            'role_aktif'     => $roleAktif ?: 8,
            'akun_terbanyak' => $topRoleName,
            'role_tunggal'   => $singleName,
            'role_prioritas' => $rolePrioritas,
        ];

        $poliCount        = Poli::where('is_active', true)->count();
        $tenagaCount      = Practitioner::where('is_active', true)->count();
        $topPoli          = Poli::where('is_active', true)->first();
        $topPractitioner  = Practitioner::where('is_active', true)->first();

        $masterPrioritas = [];
        if ($topPoli) {
            $masterPrioritas[] = ['name' => $topPoli->name, 'count' => '1'];
        }
        if ($topPractitioner) {
            $masterPrioritas[] = ['name' => $topPractitioner->name, 'count' => '1 jadwal'];
        }
        if (empty($masterPrioritas)) {
            $masterPrioritas = [
                ['name' => 'Poli Umum',         'count' => '1'],
                ['name' => 'dr. Andi Prasetyo', 'count' => '1 jadwal'],
            ];
        }

        $masterData = [
            'poli'             => $poliCount ?: 3,
            'tenaga_kesehatan' => $tenagaCount ?: 5,
            'jadwal_hari_ini'  => $tenagaCount ?: 3,
            'master_prioritas' => $masterPrioritas,
        ];

        $timeNow = now()->timezone(config('app.timezone', 'Asia/Jakarta'))->format('H.i');
        $auditAktivitas = [
            'aktivitas_hari_ini'  => 2,
            'upaya_login_ditolak' => 0,
            'terakhir_diperbarui' => $timeNow,
            'aktivitas_terbaru'   => [
                ['time' => $timeNow . ' • ' . (auth()->user()?->name ?? 'Sistem'), 'desc' => 'Akses dashboard administrasi'],
                ['time' => '09.05 • Sistem', 'desc' => 'Pemeriksaan integritas sistem'],
            ],
        ];

        return view('admin.super.index', compact('stats', 'daftarAkun', 'hakAkses', 'masterData', 'auditAktivitas'));
    }
}
