<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    /**
     * Display a listing of the audit activities.
     */
    public function index(Request $request): View
    {
        $allAudits = collect([
            [
                'id' => 'AUD-SIM-004',
                'time' => '10.20',
                'date' => '6 Oktober 2026',
                'datetime_label' => '6 Oktober 2026, 10.20 WIB',
                'actor' => 'Fajar Pratama',
                'role' => 'Super Admin',
                'clinic' => 'KSS-01',
                'module' => 'Data klinik',
                'action' => 'Ubah kontak administrasi',
                'result' => 'Tercatat',
                'is_simulation' => true,
                'title' => 'Ubah kontak administrasi klinik',
                'changes' => [
                    ['field' => 'Email kontak administrasi', 'before' => 'admin.lama@klinika.test', 'after' => 'kontak.kss@klinika.test'],
                    ['field' => 'Telepon administrasi', 'before' => '022 555 0100', 'after' => '022 555 0101'],
                ],
            ],
            [
                'id' => 'AUD-SIM-003',
                'time' => '09.40',
                'date' => '6 Oktober 2026',
                'datetime_label' => '6 Oktober 2026, 09.40 WIB',
                'actor' => 'Ratna Dewi',
                'role' => 'Admin Klinik',
                'clinic' => 'KSS-01',
                'module' => 'Jadwal dokter',
                'action' => 'Memperbarui jadwal',
                'result' => 'Tercatat',
                'is_simulation' => false,
                'title' => 'Pembaruan jadwal praktik dokter',
                'changes' => [
                    ['field' => 'Jadwal Poli Umum', 'before' => '08:00 - 12:00 WIB', 'after' => '08:00 - 14:00 WIB'],
                ],
            ],
            [
                'id' => 'AUD-SIM-002',
                'time' => '09.22',
                'date' => '6 Oktober 2026',
                'datetime_label' => '6 Oktober 2026, 09.22 WIB',
                'actor' => 'Fajar Pratama',
                'role' => 'Super Admin',
                'clinic' => 'KBS-02',
                'module' => 'Hak akses',
                'action' => 'Menetapkan role Kasir',
                'result' => 'Tercatat',
                'is_simulation' => false,
                'title' => 'Penetapan role kasir pada pengguna',
                'changes' => [
                    ['field' => 'Role Akun (USR-008)', 'before' => 'Belum ada role', 'after' => 'kasir'],
                ],
            ],
            [
                'id' => 'AUD-SIM-001',
                'time' => '09.05',
                'date' => '6 Oktober 2026',
                'datetime_label' => '6 Oktober 2026, 09.05 WIB',
                'actor' => 'Sistem',
                'role' => 'Sistem',
                'clinic' => 'KSS-01',
                'module' => 'Autentikasi',
                'action' => 'Menolak 3 upaya login',
                'result' => 'Ditolak',
                'is_simulation' => false,
                'title' => 'Blokir sementara percobaan login berulang',
                'changes' => [
                    ['field' => 'Status IP', 'before' => 'Normal', 'after' => 'Dibatasi sementara (15 menit)'],
                ],
            ],
        ]);

        $search = $request->input('q');
        $clinic = $request->input('clinic');
        $module = $request->input('module');
        $actor = $request->input('actor');
        $action = $request->input('action_type');
        $result = $request->input('result');
        $date = $request->input('date', '6 Oktober 2026');

        $filteredAudits = $allAudits->filter(function ($item) use ($search, $clinic, $module, $actor, $action, $result) {
            if ($search) {
                $term = strtolower($search);
                $matches = str_contains(strtolower($item['actor']), $term)
                    || str_contains(strtolower($item['action']), $term)
                    || str_contains(strtolower($item['id']), $term)
                    || str_contains(strtolower($item['module']), $term);
                if (! $matches) {
                    return false;
                }
            }

            if ($clinic && $clinic !== 'Semua klinik' && $item['clinic'] !== $clinic) {
                return false;
            }

            if ($module && $module !== 'Semua modul' && $item['module'] !== $module) {
                return false;
            }

            if ($actor && $actor !== 'Semua aktor' && $item['actor'] !== $actor) {
                return false;
            }

            if ($action && $action !== 'Semua tindakan' && $item['action'] !== $action) {
                return false;
            }

            if ($result && $result !== 'Semua hasil' && $item['result'] !== $result) {
                return false;
            }

            return true;
        })->values();

        $selectedId = $request->input('detail', 'AUD-SIM-004');
        $selectedAudit = $allAudits->firstWhere('id', $selectedId) ?? $filteredAudits->first();

        return view('admin.audits.index', [
            'audits' => $filteredAudits,
            'selectedAudit' => $selectedAudit,
            'search' => $search,
            'clinic' => $clinic,
            'module' => $module,
            'actor' => $actor,
            'action' => $action,
            'result' => $result,
            'date' => $date,
        ]);
    }
}
