<?php

namespace App\Http\Controllers;

use App\Services\ManajemenReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller read-only untuk seluruh halaman Role Manajemen.
 *
 * Seluruh data diambil dari ManajemenReportService (sementara dummy)
 * dan hanya mendukung aksi baca (GET) tanpa mutasi data.
 */
class ManajemenController extends Controller
{
    public function __construct(
        private readonly ManajemenReportService $reportService
    ) {}

    /**
     * Halaman utama Role Manajemen: Ringkasan & Laporan Klinik.
     */
    public function index(Request $request): View
    {
        $period = (string) $request->query('period', '7_hari');

        return view('manajemen.index', [
            'periods' => $this->reportService->getPeriods(),
            'report' => $this->reportService->getClinicOverview($period),
        ]);
    }

    /**
     * Statistik Kunjungan Pasien (read-only).
     */
    public function visits(Request $request): View
    {
        $period = (string) $request->query('period', '7_hari');
        $poli = (string) $request->query('poli', 'semua');

        return view('manajemen.visits', [
            'periods' => $this->reportService->getPeriods(),
            'poliList' => $this->reportService->getPoliList(),
            'stats' => $this->reportService->getVisitStatistics($period, $poli),
        ]);
    }

    /**
     * Laporan Transaksi & Keuangan Klinik (read-only).
     */
    public function transactions(Request $request): View
    {
        $period = (string) $request->query('period', '7_hari');
        $paymentMethod = (string) $request->query('metode', 'semua');
        $status = (string) $request->query('status', 'semua');

        return view('manajemen.transactions', [
            'periods' => $this->reportService->getPeriods(),
            'report' => $this->reportService->getTransactionReport($period, $paymentMethod, $status),
        ]);
    }

    /**
     * Laporan Farmasi, Stok & Pemakaian Obat (read-only).
     */
    public function pharmacy(Request $request): View
    {
        $category = (string) $request->query('kategori', 'semua');
        $stockStatus = (string) $request->query('status', 'semua');
        $search = (string) $request->query('q', '');

        return view('manajemen.pharmacy', [
            'report' => $this->reportService->getPharmacyReport($category, $stockStatus, $search),
        ]);
    }

    /**
     * Pemantauan Operasional Klinik (read-only).
     */
    public function operations(Request $request): View
    {
        $poli = (string) $request->query('poli', 'semua');
        $status = (string) $request->query('status', 'semua');

        return view('manajemen.operations', [
            'poliList' => $this->reportService->getPoliList(),
            'monitoring' => $this->reportService->getOperationalMonitoring($poli, $status),
        ]);
    }
}

