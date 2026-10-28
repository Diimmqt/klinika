<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function dashboard(): View
    {
        abort_unless(auth()->user()?->hasRole(['pendaftaran', 'super_admin']), 403);

        // ponytail: static simulation data — replace with real visits query when visits module is ready
        $stats = [
            'terdaftar_hari_ini' => 64,
            'pasien_lama'        => 52,
            'pasien_baru'        => 12,
            'menunggu'           => 8,
            'dalam_pelayanan'    => 10,
            'selesai'            => 46,
        ];

        $recentVisits = [
            ['patient' => 'Ahmad Fauzi', 'rm' => 'RM-2026-00041', 'visit_id' => 'KNJ-001', 'poli' => 'Umum', 'antrian' => 'A-001', 'tahap' => 'Pemeriksaan', 'status' => 'active'],
            ['patient' => 'Siti Aminah', 'rm' => 'RM-2026-00038', 'visit_id' => 'KNJ-002', 'poli' => 'Gigi', 'antrian' => 'B-003', 'tahap' => 'Menunggu', 'status' => 'wait'],
            ['patient' => 'Budi Santoso', 'rm' => 'RM-2026-00035', 'visit_id' => 'KNJ-003', 'poli' => 'Umum', 'antrian' => 'A-005', 'tahap' => 'Selesai', 'status' => 'done'],
            ['patient' => 'Dewi Rahayu', 'rm' => 'RM-2026-00029', 'visit_id' => 'KNJ-004', 'poli' => 'KIA', 'antrian' => 'C-002', 'tahap' => 'Pembayaran', 'status' => 'pay'],
            ['patient' => 'Rudi Hartono', 'rm' => 'RM-2026-00021', 'visit_id' => 'KNJ-005', 'poli' => 'Umum', 'antrian' => 'A-007', 'tahap' => 'Selesai', 'status' => 'done'],
        ];

        $jadwal = [
            ['poli' => 'Poli Umum', 'dokter' => 'dr. Hendra Wijaya', 'jam' => '08:00 – 12:00', 'status' => 'Buka'],
            ['poli' => 'Poli Gigi', 'dokter' => 'drg. Maya Putri', 'jam' => '09:00 – 13:00', 'status' => 'Buka'],
            ['poli' => 'KIA / Kandungan', 'dokter' => 'dr. Rina Susanti', 'jam' => '10:00 – 14:00', 'status' => 'Buka'],
        ];

        return view('pendaftaran.dashboard', compact('stats', 'recentVisits', 'jadwal'));
    }
}
