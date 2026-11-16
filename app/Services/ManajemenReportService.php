<?php

namespace App\Services;

/**
 * Penyedia data laporan dan statistik sementara (dummy) untuk Role Manajemen.
 *
 * Seluruh data dipisahkan dari controller dan view agar mudah diganti
 * dengan query database asli (Eloquent/DB) ketika modul operasional terkait selesai.
 */
class ManajemenReportService
{
    /**
     * Daftar periode pelaporan yang didukung.
     *
     * @return array<string, array{label: string, subtitle: string, multiplier: float}>
     */
    public function getPeriods(): array
    {
        return [
            'hari_ini' => [
                'label' => 'Hari Ini',
                'subtitle' => 'Data operasional harian berjalan',
                'multiplier' => 0.16,
            ],
            '7_hari' => [
                'label' => '7 Hari Terakhir',
                'subtitle' => 'Akumulasi kinerja sepekan terakhir',
                'multiplier' => 1.0,
            ],
            '30_hari' => [
                'label' => '30 Hari Terakhir',
                'subtitle' => 'Akumulasi kinerja bulanan berjalan',
                'multiplier' => 4.1,
            ],
        ];
    }

    /**
     * Daftar poli resmi klinik untuk konsistensi filter dan agregasi.
     *
     * @return array<int, string>
     */
    public function getPoliList(): array
    {
        return [
            'Poli Umum',
            'Poli Gigi',
            'Poli KIA & Anak',
        ];
    }

    /**
     * 1. Ringkasan & Laporan Klinik (/manajemen).
     *
     * @return array<string, mixed>
     */
    public function getClinicOverview(string $period = '7_hari'): array
    {
        $period = $this->normalizePeriod($period);
        $visits = $this->getVisitStatistics($period);
        $transactions = $this->getTransactionReport($period);
        $pharmacy = $this->getPharmacyReport();
        $operations = $this->getOperationalMonitoring();

        $totalVisits = $visits['kpis']['total_kunjungan'];
        $totalRevenue = $transactions['kpis']['total_pendapatan'];
        $totalPrescriptions = (int) round(142 * $this->getPeriodMultiplier($period));
        $avgSla = $operations['kpis']['rata_rata_tunggu_menit'];

        return [
            'period' => $period,
            'period_meta' => $this->getPeriods()[$period],
            'kpis' => [
                'total_kunjungan' => $totalVisits,
                'kunjungan_delta' => '+8,4% vs periode sebelumnya',
                'total_pendapatan' => $totalRevenue,
                'pendapatan_delta' => '+6,2% vs periode sebelumnya',
                'total_resep' => $totalPrescriptions,
                'tingkat_tebus_persen' => 94.3,
                'rata_tunggu_menit' => $avgSla,
                'okupansi_poli_persen' => 82,
            ],
            'alerts' => [
                [
                    'severity' => 'warning',
                    'title' => $pharmacy['kpis']['stok_menipis'] . ' item obat berada di bawah batas stok minimum',
                    'detail' => 'Amoxicillin 500mg, Paracetamol Syrup 60ml, dan Amlodipine 5mg perlu pengadaan ulang.',
                    'route' => 'manajemen.pharmacy',
                ],
                [
                    'severity' => 'info',
                    'title' => $operations['kpis']['antrean_menunggu'] . ' pasien sedang menunggu di antrean poli saat ini',
                    'detail' => 'Kepadatan tertinggi berada di Poli Umum dengan waktu tunggu rata-rata ' . $avgSla . ' menit.',
                    'route' => 'manajemen.operations',
                ],
            ],
            'poli_summary' => $this->buildPoliSummaryRows($visits['by_poli'], $transactions['by_poli']),
            'weekly_highlights' => $visits['daily_trend'],
            'recent_reports' => [
                [
                    'kode' => 'LAP-OPS-01',
                    'nama' => 'Laporan Kinerja Kunjungan Poli',
                    'kategori' => 'Pelayanan',
                    'ringkasan' => number_format($totalVisits, 0, ',', '.') . ' kunjungan tercatat (' . $visits['kpis']['tingkat_selesai_persen'] . '% selesai)',
                    'status' => 'Tersedia',
                    'route' => 'manajemen.visits',
                ],
                [
                    'kode' => 'LAP-KEU-02',
                    'nama' => 'Laporan Pendapatan & Metode Bayar',
                    'kategori' => 'Keuangan',
                    'ringkasan' => $this->formatRupiah($totalRevenue) . ' dari ' . number_format($transactions['kpis']['jumlah_transaksi'], 0, ',', '.') . ' transaksi',
                    'status' => 'Tersedia',
                    'route' => 'manajemen.transactions',
                ],
                [
                    'kode' => 'LAP-FAR-03',
                    'nama' => 'Laporan Pemakaian & Stok Farmasi',
                    'kategori' => 'Farmasi',
                    'ringkasan' => number_format($totalPrescriptions, 0, ',', '.') . ' resep diproses, ' . $pharmacy['kpis']['stok_menipis'] . ' stok menipis',
                    'status' => 'Perlu Atensi',
                    'route' => 'manajemen.pharmacy',
                ],
                [
                    'kode' => 'LAP-ANT-04',
                    'nama' => 'Pantauan Antrean & Kesiapan Poli',
                    'kategori' => 'Operasional',
                    'ringkasan' => $operations['kpis']['poli_aktif'] . ' poli aktif • ' . $operations['kpis']['tenaga_bertugas'] . ' tenaga kesehatan bertugas',
                    'status' => 'Aktif',
                    'route' => 'manajemen.operations',
                ],
            ],
        ];
    }

    /**
     * 2. Statistik Kunjungan Pasien (/manajemen/kunjungan).
     *
     * @return array<string, mixed>
     */
    public function getVisitStatistics(string $period = '7_hari', string $poli = 'semua'): array
    {
        $period = $this->normalizePeriod($period);
        $multiplier = $this->getPeriodMultiplier($period);

        $baseByPoli = [
            'Poli Umum' => ['kunjungan' => 98, 'baru' => 24, 'lama' => 74, 'selesai' => 93, 'avg_durasi' => 14],
            'Poli Gigi' => ['kunjungan' => 42, 'baru' => 11, 'lama' => 31, 'selesai' => 40, 'avg_durasi' => 24],
            'Poli KIA & Anak' => ['kunjungan' => 46, 'baru' => 13, 'lama' => 33, 'selesai' => 44, 'avg_durasi' => 18],
        ];

        $byPoli = [];
        foreach ($baseByPoli as $namaPoli => $row) {
            if ($poli !== 'semua' && $poli !== '' && $namaPoli !== $poli) {
                continue;
            }
            $kunjungan = max(1, (int) round($row['kunjungan'] * $multiplier));
            $baru = max(0, (int) round($row['baru'] * $multiplier));
            $lama = max(0, $kunjungan - $baru);
            $selesai = min($kunjungan, max(1, (int) round($row['selesai'] * $multiplier)));
            $byPoli[] = [
                'poli' => $namaPoli,
                'kunjungan' => $kunjungan,
                'pasien_baru' => $baru,
                'pasien_lama' => $lama,
                'selesai' => $selesai,
                'avg_durasi_menit' => $row['avg_durasi'],
            ];
        }

        $totalKunjungan = array_sum(array_column($byPoli, 'kunjungan'));
        $totalBaru = array_sum(array_column($byPoli, 'pasien_baru'));
        $totalLama = array_sum(array_column($byPoli, 'pasien_lama'));
        $totalSelesai = array_sum(array_column($byPoli, 'selesai'));

        foreach ($byPoli as &$item) {
            $item['persentase'] = $totalKunjungan > 0
                ? round(($item['kunjungan'] / $totalKunjungan) * 100, 1)
                : 0;
        }
        unset($item);

        $daysCount = match ($period) {
            'hari_ini' => 1,
            '30_hari' => 30,
            default => 7,
        };

        $dailyBase = [
            ['hari' => 'Sen', 'umum' => 16, 'gigi' => 7, 'kia' => 8],
            ['hari' => 'Sel', 'umum' => 14, 'gigi' => 6, 'kia' => 7],
            ['hari' => 'Rab', 'umum' => 15, 'gigi' => 7, 'kia' => 6],
            ['hari' => 'Kam', 'umum' => 13, 'gigi' => 5, 'kia' => 7],
            ['hari' => 'Jum', 'umum' => 17, 'gigi' => 7, 'kia' => 8],
            ['hari' => 'Sab', 'umum' => 15, 'gigi' => 6, 'kia' => 6],
            ['hari' => 'Min', 'umum' => 8, 'gigi' => 4, 'kia' => 4],
        ];

        $dailyTrend = [];
        foreach ($dailyBase as $d) {
            $umum = ($poli === 'semua' || $poli === '' || $poli === 'Poli Umum') ? (int) round($d['umum'] * max(0.35, $multiplier)) : 0;
            $gigi = ($poli === 'semua' || $poli === '' || $poli === 'Poli Gigi') ? (int) round($d['gigi'] * max(0.35, $multiplier)) : 0;
            $kia = ($poli === 'semua' || $poli === '' || $poli === 'Poli KIA & Anak') ? (int) round($d['kia'] * max(0.35, $multiplier)) : 0;
            $dailyTrend[] = [
                'hari' => $d['hari'],
                'umum' => $umum,
                'gigi' => $gigi,
                'kia' => $kia,
                'total' => $umum + $gigi + $kia,
            ];
        }

        $demographics = [
            ['kelompok' => 'Anak (0-12 thn)', 'jumlah' => max(1, (int) round($totalKunjungan * 0.22)), 'persen' => 22],
            ['kelompok' => 'Remaja & Dewasa Muda (13-30 thn)', 'jumlah' => max(1, (int) round($totalKunjungan * 0.28)), 'persen' => 28],
            ['kelompok' => 'Dewasa Produktif (31-55 thn)', 'jumlah' => max(1, (int) round($totalKunjungan * 0.34)), 'persen' => 34],
            ['kelompok' => 'Lansia (>55 thn)', 'jumlah' => max(1, $totalKunjungan - (int) round($totalKunjungan * 0.84)), 'persen' => 16],
        ];

        return [
            'period' => $period,
            'period_meta' => $this->getPeriods()[$period],
            'selected_poli' => $poli ?: 'semua',
            'kpis' => [
                'total_kunjungan' => $totalKunjungan,
                'pasien_baru' => $totalBaru,
                'pasien_lama' => $totalLama,
                'rata_harian' => round($totalKunjungan / $daysCount, 1),
                'tingkat_selesai_persen' => $totalKunjungan > 0 ? round(($totalSelesai / $totalKunjungan) * 100, 1) : 0,
            ],
            'by_poli' => $byPoli,
            'daily_trend' => $dailyTrend,
            'demographics' => $demographics,
        ];
    }

    /**
     * 3. Laporan Transaksi (/manajemen/transaksi).
     *
     * @return array<string, mixed>
     */
    public function getTransactionReport(
        string $period = '7_hari',
        string $paymentMethod = 'semua',
        string $status = 'semua'
    ): array {
        $period = $this->normalizePeriod($period);
        $multiplier = $this->getPeriodMultiplier($period);

        $baseTransactions = [
            ['kode' => 'TRX-20261112-001', 'tanggal' => '12 Nov 2026 • 08:42', 'pasien' => 'Budi Santoso', 'poli' => 'Poli Umum', 'layanan' => 'Konsultasi + Obat', 'metode' => 'QRIS', 'nominal' => 145000, 'status' => 'Lunas'],
            ['kode' => 'TRX-20261112-002', 'tanggal' => '12 Nov 2026 • 09:15', 'pasien' => 'Siti Aminah', 'poli' => 'Poli Gigi', 'layanan' => 'Scaling + Konsultasi', 'metode' => 'Transfer/Debit', 'nominal' => 320000, 'status' => 'Lunas'],
            ['kode' => 'TRX-20261112-003', 'tanggal' => '12 Nov 2026 • 09:48', 'pasien' => 'Ahmad Fauzi', 'poli' => 'Poli KIA & Anak', 'layanan' => 'Imunisasi + Vitamin', 'metode' => 'Tunai', 'nominal' => 210000, 'status' => 'Lunas'],
            ['kode' => 'TRX-20261112-004', 'tanggal' => '12 Nov 2026 • 10:12', 'pasien' => 'Rina Marlina', 'poli' => 'Poli Umum', 'layanan' => 'Pemeriksaan Umum', 'metode' => 'BPJS', 'nominal' => 95000, 'status' => 'Terverifikasi'],
            ['kode' => 'TRX-20261112-005', 'tanggal' => '12 Nov 2026 • 10:40', 'pasien' => 'Dedi Kurniawan', 'poli' => 'Poli Gigi', 'layanan' => 'Tambal Gigi Komposit', 'metode' => 'QRIS', 'nominal' => 385000, 'status' => 'Lunas'],
            ['kode' => 'TRX-20261112-006', 'tanggal' => '12 Nov 2026 • 11:05', 'pasien' => 'Nadia Putri', 'poli' => 'Poli KIA & Anak', 'layanan' => 'Kontrol Kehamilan + USG', 'metode' => 'Transfer/Debit', 'nominal' => 275000, 'status' => 'Menunggu'],
            ['kode' => 'TRX-20261112-007', 'tanggal' => '12 Nov 2026 • 11:30', 'pasien' => 'Hendra Wijaya', 'poli' => 'Poli Umum', 'layanan' => 'Cek Lab Sederhana + Obat', 'metode' => 'Tunai', 'nominal' => 185000, 'status' => 'Lunas'],
            ['kode' => 'TRX-20261112-008', 'tanggal' => '12 Nov 2026 • 12:04', 'pasien' => 'Lestari Dewi', 'poli' => 'Poli Umum', 'layanan' => 'Konsultasi + Nebulizer', 'metode' => 'BPJS', 'nominal' => 130000, 'status' => 'Terverifikasi'],
        ];

        $filteredLog = array_values(array_filter($baseTransactions, static function (array $row) use ($paymentMethod, $status) {
            if ($paymentMethod !== 'semua' && $paymentMethod !== '' && $row['metode'] !== $paymentMethod) {
                return false;
            }
            if ($status !== 'semua' && $status !== '' && $row['status'] !== $status) {
                return false;
            }

            return true;
        }));

        $methodTotalsBase = [
            'QRIS' => ['nominal' => 12650000, 'jumlah' => 64],
            'Tunai' => ['nominal' => 9420000, 'jumlah' => 58],
            'Transfer/Debit' => ['nominal' => 8150000, 'jumlah' => 32],
            'BPJS' => ['nominal' => 4580000, 'jumlah' => 32],
        ];

        $byMethod = [];
        foreach ($methodTotalsBase as $metode => $data) {
            if ($paymentMethod !== 'semua' && $paymentMethod !== '' && $metode !== $paymentMethod) {
                continue;
            }
            $byMethod[] = [
                'metode' => $metode,
                'nominal' => (int) round($data['nominal'] * $multiplier),
                'jumlah' => max(1, (int) round($data['jumlah'] * $multiplier)),
            ];
        }

        $totalNominalAll = array_sum(array_column($byMethod, 'nominal'));
        $totalTrxCount = array_sum(array_column($byMethod, 'jumlah'));

        foreach ($byMethod as &$m) {
            $m['persentase'] = $totalNominalAll > 0 ? round(($m['nominal'] / $totalNominalAll) * 100, 1) : 0;
        }
        unset($m);

        $byCategory = [
            ['kategori' => 'Konsultasi Dokter', 'nominal' => (int) round($totalNominalAll * 0.34), 'persen' => 34],
            ['kategori' => 'Tindakan Medis & Gigi', 'nominal' => (int) round($totalNominalAll * 0.39), 'persen' => 39],
            ['kategori' => 'Resep & Farmasi', 'nominal' => (int) round($totalNominalAll * 0.27), 'persen' => 27],
        ];

        $byPoli = [
            'Poli Umum' => (int) round(15400000 * $multiplier),
            'Poli Gigi' => (int) round(11250000 * $multiplier),
            'Poli KIA & Anak' => (int) round(8150000 * $multiplier),
        ];

        $lunasCount = max(1, (int) round($totalTrxCount * 0.92));
        $pendingCount = max(0, $totalTrxCount - $lunasCount);

        return [
            'period' => $period,
            'period_meta' => $this->getPeriods()[$period],
            'selected_method' => $paymentMethod ?: 'semua',
            'selected_status' => $status ?: 'semua',
            'kpis' => [
                'total_pendapatan' => $totalNominalAll,
                'jumlah_transaksi' => $totalTrxCount,
                'rata_transaksi' => $totalTrxCount > 0 ? (int) round($totalNominalAll / $totalTrxCount) : 0,
                'transaksi_lunas' => $lunasCount,
                'transaksi_tertunda' => $pendingCount,
            ],
            'by_method' => $byMethod,
            'by_category' => $byCategory,
            'by_poli' => $byPoli,
            'transactions' => $filteredLog,
        ];
    }

    /**
     * 4. Laporan Farmasi (/manajemen/farmasi).
     *
     * @return array<string, mixed>
     */
    public function getPharmacyReport(
        string $category = 'semua',
        string $stockStatus = 'semua',
        string $search = ''
    ): array {
        $items = [
            ['kode' => 'OBT-001', 'nama' => 'Paracetamol 500mg Tablet', 'kategori' => 'Analgesik', 'stok' => 1450, 'min_stok' => 500, 'keluar_7h' => 420, 'kadaluarsa' => 'Agu 2027', 'status' => 'Aman'],
            ['kode' => 'OBT-002', 'nama' => 'Amoxicillin 500mg Kapsul', 'kategori' => 'Antibiotik', 'stok' => 140, 'min_stok' => 200, 'keluar_7h' => 215, 'kadaluarsa' => 'Jun 2027', 'status' => 'Menipis'],
            ['kode' => 'OBT-003', 'nama' => 'Paracetamol Syrup 60ml', 'kategori' => 'Analgesik', 'stok' => 28, 'min_stok' => 40, 'keluar_7h' => 52, 'kadaluarsa' => 'Des 2026', 'status' => 'Menipis'],
            ['kode' => 'OBT-004', 'nama' => 'Cetirizine 10mg Tablet', 'kategori' => 'Antihistamin', 'stok' => 680, 'min_stok' => 250, 'keluar_7h' => 164, 'kadaluarsa' => 'Nov 2027', 'status' => 'Aman'],
            ['kode' => 'OBT-005', 'nama' => 'Omeprazole 20mg Kapsul', 'kategori' => 'Gastrointestinal', 'stok' => 520, 'min_stok' => 200, 'keluar_7h' => 148, 'kadaluarsa' => 'Sep 2027', 'status' => 'Aman'],
            ['kode' => 'OBT-006', 'nama' => 'Amlodipine 5mg Tablet', 'kategori' => 'Kardiovaskular', 'stok' => 95, 'min_stok' => 150, 'keluar_7h' => 132, 'kadaluarsa' => 'Mar 2027', 'status' => 'Menipis'],
            ['kode' => 'OBT-007', 'nama' => 'OBH Combi Syrup 100ml', 'kategori' => 'Respirasi', 'stok' => 76, 'min_stok' => 50, 'keluar_7h' => 64, 'kadaluarsa' => 'Jan 2027', 'status' => 'Dekat ED'],
            ['kode' => 'OBT-008', 'nama' => 'Metformin 500mg Tablet', 'kategori' => 'Metabolik', 'stok' => 430, 'min_stok' => 180, 'keluar_7h' => 118, 'kadaluarsa' => 'Okt 2027', 'status' => 'Aman'],
            ['kode' => 'OBT-009', 'nama' => 'Asam Mefenamat 500mg', 'kategori' => 'Analgesik', 'stok' => 390, 'min_stok' => 180, 'keluar_7h' => 136, 'kadaluarsa' => 'Mei 2027', 'status' => 'Aman'],
            ['kode' => 'OBT-010', 'nama' => 'Vitamin B Complex Tablet', 'kategori' => 'Suplemen', 'stok' => 890, 'min_stok' => 300, 'keluar_7h' => 190, 'kadaluarsa' => 'Feb 2027', 'status' => 'Dekat ED'],
        ];

        $lowStockCount = count(array_filter($items, static fn (array $i) => $i['status'] === 'Menipis'));
        $nearExpiryCount = count(array_filter($items, static fn (array $i) => $i['status'] === 'Dekat ED'));

        $filteredItems = array_values(array_filter($items, static function (array $item) use ($category, $stockStatus, $search) {
            if ($category !== 'semua' && $category !== '' && $item['kategori'] !== $category) {
                return false;
            }
            if ($stockStatus !== 'semua' && $stockStatus !== '' && $item['status'] !== $stockStatus) {
                return false;
            }
            if ($search !== '') {
                $needle = mb_strtolower(trim($search));
                $haystack = mb_strtolower($item['kode'] . ' ' . $item['nama'] . ' ' . $item['kategori']);
                if (! str_contains($haystack, $needle)) {
                    return false;
                }
            }

            return true;
        }));

        $topPrescribed = $items;
        usort($topPrescribed, static fn (array $a, array $b) => $b['keluar_7h'] <=> $a['keluar_7h']);
        $topPrescribed = array_slice($topPrescribed, 0, 5);

        $categories = array_values(array_unique(array_column($items, 'kategori')));
        sort($categories);

        return [
            'selected_category' => $category ?: 'semua',
            'selected_status' => $stockStatus ?: 'semua',
            'search' => $search,
            'categories' => $categories,
            'kpis' => [
                'total_resep_mingguan' => 142,
                'resep_terlayani_persen' => 94.3,
                'item_obat_aktif' => 86,
                'stok_menipis' => $lowStockCount,
                'mendekati_ed' => $nearExpiryCount,
            ],
            'top_prescribed' => $topPrescribed,
            'items' => $filteredItems,
        ];
    }

    /**
     * 5. Pemantauan Operasional Klinik (/manajemen/operasional).
     *
     * @return array<string, mixed>
     */
    public function getOperationalMonitoring(string $poli = 'semua', string $status = 'semua'): array
    {
        $poliStatus = [
            [
                'poli' => 'Poli Umum',
                'ruang' => 'Ruang 01 & 02',
                'dokter' => 'dr. Andi Pratama',
                'perawat' => 'Ns. Rina Susanti',
                'status_poli' => 'Beroperasi',
                'antrean_menunggu' => 6,
                'sedang_diperiksa' => 2,
                'selesai_hari_ini' => 19,
                'rata_tunggu_menit' => 14,
                'kapasitas_persen' => 85,
            ],
            [
                'poli' => 'Poli Gigi',
                'ruang' => 'Ruang 03',
                'dokter' => 'drg. Maya Sari',
                'perawat' => 'Perawat Diah',
                'status_poli' => 'Beroperasi',
                'antrean_menunggu' => 3,
                'sedang_diperiksa' => 1,
                'selesai_hari_ini' => 9,
                'rata_tunggu_menit' => 18,
                'kapasitas_persen' => 78,
            ],
            [
                'poli' => 'Poli KIA & Anak',
                'ruang' => 'Ruang 04',
                'dokter' => 'dr. Putri Rahmawati',
                'perawat' => 'Bidan Ratna',
                'status_poli' => 'Beroperasi',
                'antrean_menunggu' => 4,
                'sedang_diperiksa' => 1,
                'selesai_hari_ini' => 11,
                'rata_tunggu_menit' => 12,
                'kapasitas_persen' => 82,
            ],
        ];

        $staffSchedules = [
            ['nama' => 'dr. Andi Pratama', 'peran' => 'Dokter Umum', 'poli' => 'Poli Umum', 'shift' => 'Pagi (08:00 - 14:00)', 'status' => 'Bertugas'],
            ['nama' => 'drg. Maya Sari', 'peran' => 'Dokter Gigi', 'poli' => 'Poli Gigi', 'shift' => 'Pagi (08:00 - 14:00)', 'status' => 'Bertugas'],
            ['nama' => 'dr. Putri Rahmawati', 'peran' => 'Dokter KIA & Anak', 'poli' => 'Poli KIA & Anak', 'shift' => 'Pagi (08:00 - 15:00)', 'status' => 'Bertugas'],
            ['nama' => 'Ns. Rina Susanti', 'peran' => 'Perawat Pemeriksaan Awal', 'poli' => 'Poli Umum', 'shift' => 'Pagi (07:30 - 14:30)', 'status' => 'Bertugas'],
            ['nama' => 'Bidan Ratna', 'peran' => 'Perawat / Bidan KIA', 'poli' => 'Poli KIA & Anak', 'shift' => 'Pagi (08:00 - 14:00)', 'status' => 'Bertugas'],
            ['nama' => 'Apt. Farhan Akbar', 'peran' => 'Apoteker Penanggung Jawab', 'poli' => 'Farmasi', 'shift' => 'Pagi (08:00 - 16:00)', 'status' => 'Bertugas'],
            ['nama' => 'dr. Hendra Kusuma', 'peran' => 'Dokter Umum', 'poli' => 'Poli Umum', 'shift' => 'Sore (14:00 - 20:00)', 'status' => 'Terjadwal'],
        ];

        $filteredPoli = array_values(array_filter($poliStatus, static function (array $row) use ($poli) {
            return $poli === 'semua' || $poli === '' || $row['poli'] === $poli;
        }));

        $filteredStaff = array_values(array_filter($staffSchedules, static function (array $row) use ($poli, $status) {
            if ($poli !== 'semua' && $poli !== '' && $row['poli'] !== $poli) {
                return false;
            }
            if ($status !== 'semua' && $status !== '' && $row['status'] !== $status) {
                return false;
            }

            return true;
        }));

        $totalWaiting = array_sum(array_column($filteredPoli, 'antrean_menunggu'));
        $totalActiveExam = array_sum(array_column($filteredPoli, 'sedang_diperiksa'));
        $avgWait = count($filteredPoli) > 0
            ? (int) round(array_sum(array_column($filteredPoli, 'rata_tunggu_menit')) / count($filteredPoli))
            : 0;
        $onDutyCount = count(array_filter($staffSchedules, static fn (array $s) => $s['status'] === 'Bertugas'));

        return [
            'selected_poli' => $poli ?: 'semua',
            'selected_status' => $status ?: 'semua',
            'kpis' => [
                'poli_aktif' => count($poliStatus),
                'antrean_menunggu' => $totalWaiting,
                'sedang_diperiksa' => $totalActiveExam,
                'rata_rata_tunggu_menit' => $avgWait,
                'tenaga_bertugas' => $onDutyCount,
                'target_sla_menit' => 20,
            ],
            'poli_status' => $filteredPoli,
            'staff_schedules' => $filteredStaff,
            'sla_metrics' => [
                ['tahap' => 'Pendaftaran & Verifikasi', 'capaian_menit' => 4, 'target_menit' => 5, 'status' => 'Sesuai Target'],
                ['tahap' => 'Pemeriksaan Awal Perawat', 'capaian_menit' => 6, 'target_menit' => 8, 'status' => 'Sesuai Target'],
                ['tahap' => 'Tunggu Konsultasi Dokter', 'capaian_menit' => $avgWait, 'target_menit' => 20, 'status' => 'Sesuai Target'],
                ['tahap' => 'Penyiapan Obat Farmasi', 'capaian_menit' => 9, 'target_menit' => 12, 'status' => 'Sesuai Target'],
            ],
        ];
    }

    /**
     * Format angka nominal menjadi Rupiah konsisten.
     */
    public function formatRupiah(int|float $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }

    private function normalizePeriod(string $period): string
    {
        $periods = $this->getPeriods();

        return array_key_exists($period, $periods) ? $period : '7_hari';
    }

    private function getPeriodMultiplier(string $period): float
    {
        $periods = $this->getPeriods();

        return $periods[$this->normalizePeriod($period)]['multiplier'];
    }

    /**
     * Gabungkan ringkasan kunjungan dan pendapatan per poli untuk halaman Ringkasan Klinik.
     *
     * @param  array<int, array<string, mixed>>  $visitsByPoli
     * @param  array<string, int>  $revenueByPoli
     * @return array<int, array<string, mixed>>
     */
    private function buildPoliSummaryRows(array $visitsByPoli, array $revenueByPoli): array
    {
        $rows = [];
        foreach ($visitsByPoli as $item) {
            $poliName = $item['poli'];
            $rows[] = [
                'poli' => $poliName,
                'kunjungan' => $item['kunjungan'],
                'persentase_kunjungan' => $item['persentase'],
                'selesai' => $item['selesai'],
                'pendapatan' => $revenueByPoli[$poliName] ?? 0,
            ];
        }

        return $rows;
    }
}

