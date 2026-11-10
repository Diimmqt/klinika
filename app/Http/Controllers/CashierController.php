<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    /**
     * Dashboard Kasir - Kas dan tagihan hari ini.
     */
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $stats = [
            'unpaid_count'    => 5,
            'unpaid_total'    => 'Rp740.000',
            'paid_count'      => 46,
            'paid_total_abbr' => 'Rp6,84 jt',
            'paid_total'      => 'Rp6.840.000',
            'diff'            => 'Rp0',
        ];

        $unpaidInvoices = [
            [
                'invoice_number' => 'INV-20261006-023',
                'patient_name'   => 'Siti Aminah',
                'mrn'            => 'RM-000128',
                'visit_number'   => 'KJ-20261006-023',
                'total'          => 'Rp145.000',
                'status'         => 'Belum dibayar',
            ],
            [
                'invoice_number' => 'INV-20261006-020',
                'patient_name'   => 'Rizky Maulana',
                'mrn'            => 'RM-000131',
                'visit_number'   => 'KJ-20261006-020',
                'total'          => 'Rp220.000',
                'status'         => 'Belum dibayar',
            ],
            [
                'invoice_number' => 'INV-20261006-019',
                'patient_name'   => 'Hendra Wijaya',
                'mrn'            => 'RM-000135',
                'visit_number'   => 'KJ-20261006-019',
                'total'          => 'Rp125.000',
                'status'         => 'Belum dibayar',
            ],
            [
                'invoice_number' => 'INV-20261006-018',
                'patient_name'   => 'Lilis Suryani',
                'mrn'            => 'RM-000136',
                'visit_number'   => 'KJ-20261006-018',
                'total'          => 'Rp150.000',
                'status'         => 'Belum dibayar',
            ],
        ];

        return view('kasir.index', compact('stats', 'unpaidInvoices'));
    }

    /**
     * Daftar Tagihan / Invoices Kasir.
     */
    public function invoices(Request $request): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $search = $request->input('search');
        $status = $request->input('status');

        $stats = [
            'unpaid_count'       => 5,
            'unpaid_total'       => 'Rp740.000',
            'paid_count'         => 46,
            'paid_total'         => 'Rp6.840.000',
            'pending_validation' => 0,
        ];

        $invoices = [
            [
                'invoice_number' => 'INV-20261006-023',
                'date_time'      => '6 Okt • 10.15',
                'patient_name'   => 'Siti Aminah',
                'mrn'            => 'RM-000128',
                'visit_number'   => 'KJ-20261006-023',
                'total'          => 'Rp145.000',
                'status'         => 'Belum dibayar',
                'action_label'   => 'Detail →',
            ],
            [
                'invoice_number' => 'INV-20261006-020',
                'date_time'      => '6 Okt • 09.55',
                'patient_name'   => 'Rizky Maulana',
                'mrn'            => 'RM-000131',
                'visit_number'   => 'KJ-20261006-020',
                'total'          => 'Rp220.000',
                'status'         => 'Belum dibayar',
                'action_label'   => 'Detail →',
            ],
            [
                'invoice_number' => 'INV-20261006-019',
                'date_time'      => '6 Okt • 09.48',
                'patient_name'   => 'Hendra Wijaya',
                'mrn'            => 'RM-000135',
                'visit_number'   => 'KJ-20261006-019',
                'total'          => 'Rp125.000',
                'status'         => 'Belum dibayar',
                'action_label'   => 'Detail →',
            ],
            [
                'invoice_number' => 'INV-20261006-016',
                'date_time'      => '6 Okt • 09.15',
                'patient_name'   => 'Yuni Astuti',
                'mrn'            => 'RM-000138',
                'visit_number'   => 'KJ-20261006-016',
                'total'          => 'Rp150.000',
                'status'         => 'Lunas',
                'action_label'   => 'Kuitansi →',
            ],
        ];

        if ($search) {
            $invoices = array_filter($invoices, function ($inv) use ($search) {
                return stripos($inv['invoice_number'], $search) !== false
                    || stripos($inv['patient_name'], $search) !== false
                    || stripos($inv['mrn'], $search) !== false;
            });
        }

        if ($status && $status !== 'all') {
            $invoices = array_filter($invoices, function ($inv) use ($status) {
                return strtolower($inv['status']) === strtolower($status);
            });
        }

        return view('kasir.invoices', compact('stats', 'invoices', 'search', 'status'));
    }

    /**
     * Detail Invoice.
     */
    public function show(string $invoice): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $invoiceData = [
            'invoice_number' => $invoice,
            'issue_date'     => '6 Oktober 2026 • 10.15',
            'insurance'      => 'Penjamin umum / pribadi',
            'patient' => [
                'name'         => 'Siti Aminah',
                'mrn'          => 'RM-000128',
                'gender'       => 'Perempuan',
                'birth_date'   => '14 Mei 1988',
                'age'          => 38,
                'nik'          => '3273015405880002',
            ],
            'visit' => [
                'visit_number' => 'KJ-20261006-023',
                'date_time'    => '6 Okt 2026 • 09.12',
                'poli'         => 'Poli Umum',
                'doctor'       => 'dr. Andi Prasetyo',
                'insurance'    => 'Umum / pribadi',
                'queue_code'   => 'A-023',
                'status'       => 'Menunggu kasir',
            ],
            'items' => [
                [
                    'name'     => 'Pemeriksaan awal',
                    'source'   => 'RME final',
                    'quantity' => 1,
                    'price'    => 'Rp20.000',
                    'subtotal' => 'Rp20.000',
                ],
                [
                    'name'     => 'Konsultasi dokter umum',
                    'source'   => 'RME final',
                    'quantity' => 1,
                    'price'    => 'Rp80.000',
                    'subtotal' => 'Rp80.000',
                ],
                [
                    'name'     => 'Paracetamol 500 mg',
                    'source'   => 'RX-023 diserahkan',
                    'quantity' => 10,
                    'price'    => 'Rp2.000',
                    'subtotal' => 'Rp20.000',
                ],
                [
                    'name'     => 'Cetirizine 10 mg',
                    'source'   => 'RX-023 diserahkan',
                    'quantity' => 5,
                    'price'    => 'Rp5.000',
                    'subtotal' => 'Rp25.000',
                ],
            ],
            'summary' => [
                'subtotal_services' => 'Rp100.000',
                'subtotal_medicine' => 'Rp45.000',
                'discount'          => 'Rp0',
                'tax'               => 'Rp0',
                'total'             => 'Rp145.000',
                'status'            => 'Belum dibayar',
            ],
            'logs' => [
                '10.05 • RME final oleh dr. Andi Prasetyo',
                '10.15 • Obat diserahkan oleh apt. Rina Susanti; invoice diterbitkan',
                'Pembayaran: belum ada transaksi. Kunjungan: Dalam pelayanan / menunggu kasir.',
            ],
        ];

        return view('kasir.show', compact('invoiceData'));
    }

    /**
     * Form Pembayaran Invoice.
     */
    public function payment(string $invoice): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $paymentData = [
            'invoice_number'  => $invoice,
            'total'           => 145000,
            'total_formatted' => 'Rp145.000',
            'patient' => [
                'name'         => 'Siti Aminah',
                'mrn'          => 'RM-000128',
                'gender'       => 'Perempuan',
                'birth_date'   => '14 Mei 1988',
                'age'          => 38,
                'nik'          => '3273015405880002',
            ],
            'visit' => [
                'visit_number' => 'KJ-20261006-023',
                'date_time'    => '6 Okt 2026 • 09.12',
                'poli'         => 'Poli Umum',
                'doctor'       => 'dr. Andi Prasetyo',
                'insurance'    => 'Umum / pribadi',
                'queue_code'   => 'A-023',
                'status_badge' => 'Kunjungan: Dalam pelayanan • tahap Menunggu kasir',
            ],
            'summary' => [
                'services' => 'Rp100.000',
                'medicine' => 'Rp45.000',
                'total'    => 'Rp145.000',
                'paid'     => 'Rp0',
                'status'   => 'Belum dibayar',
            ],
        ];

        return view('kasir.payment', compact('paymentData'));
    }

    /**
     * Halaman Konfirmasi Pembayaran.
     */
    public function confirm(Request $request, string $invoice): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $paymentMethod = $request->input('payment_method', 'Tunai');
        $amountReceived = $request->input('amount_received', '150.000');

        $confirmData = [
            'invoice_number'  => $invoice,
            'total_amount'    => 'Rp145.000',
            'patient' => [
                'name'         => 'Siti Aminah',
                'mrn'          => 'RM-000128',
                'visit_number' => 'KJ-20261006-023',
                'visit_date'   => '6 Oktober 2026',
                'poli'         => 'Poli Umum',
                'cashier'      => auth()->user()?->name ?? 'Budi Hartono',
                'shift'        => 'Shift pagi',
            ],
            'payment_method'  => $paymentMethod,
            'amount_received' => 'Rp' . ltrim($amountReceived, 'Rp'),
            'change'          => 'Rp5.000',
            'balance_after'   => 'Rp0',
        ];

        return view('kasir.confirm', compact('confirmData'));
    }

    /**
     * Proses Simpan Pembayaran.
     */
    public function processPayment(Request $request, string $invoice): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $request->validate([
            'payment_method'  => 'required|in:Tunai,QRIS,Transfer',
            'amount_received' => 'required',
        ]);

        return redirect()
            ->route('kasir.receipt', $invoice)
            ->with('success', 'Pembayaran sebesar Rp145.000 berhasil dicatat. Kunjungan selesai.');
    }

    /**
     * Kuitansi Pembayaran Berhasil.
     */
    public function receipt(string $invoice): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $receiptData = [
            'receipt_number' => 'KWT-20261006-023',
            'receipt_date'   => '6 Oktober 2026, 10.25',
            'trx_number'     => 'TRX-20261006-046',
            'amount'         => 'Rp145.000',
            'cashier'        => auth()->user()?->name ?? 'Budi Hartono',
            'patient' => [
                'name'         => 'Siti Aminah',
                'mrn'          => 'RM-000128',
                'gender'       => 'Perempuan',
                'birth_date'   => '14 Mei 1988',
                'age'          => 38,
                'nik'          => '3273015405880002',
            ],
            'visit' => [
                'visit_number' => 'KJ-20261006-023',
                'date_time'    => '6 Okt 2026 • 09.12',
                'poli'         => 'Poli Umum',
                'doctor'       => 'dr. Andi Prasetyo',
                'insurance'    => 'Umum / pribadi',
                'queue_code'   => 'A-023',
            ],
            'items' => [
                ['name' => 'Pemeriksaan awal + konsultasi', 'amount' => 'Rp100.000'],
                ['name' => 'Obat RX-20261006-023 • diserahkan', 'amount' => 'Rp45.000'],
            ],
            'total'          => 'Rp145.000',
            'payment_info'   => 'Tunai Rp150.000 • Kembalian Rp5.000 • Saldo Rp0',
            'timeline' => [
                'steps' => '09.12 Daftar → 09.35 Perawat → 09.40 Dokter → 10.05 RME final → 10.15 Obat diserahkan → 10.25 Lunas & selesai.',
                'note'  => 'Nomor antrean A-023 ditutup. Riwayat pasien kini memuat encounter 6 Oktober dan 12 September secara terpisah.',
            ],
        ];

        return view('kasir.receipt', compact('receiptData'));
    }

    /**
     * Form Penyusunan Tagihan.
     */
    public function createBilling(): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        $billingData = [
            'visit_number'   => 'KJ-SIM-20261006-041',
            'patient_name'   => 'Deni Saputra',
            'mrn'            => 'RM-SIM-000241',
            'poli'           => 'Poli Umum',
            'insurance'      => 'Umum / pribadi',
            'queue_code'     => 'A-041',
            'date'           => '6 Oktober 2026',
            'clinic'         => 'KSS-01',
            'cashier'        => auth()->user()?->name ?? 'Budi Hartono',
            'services' => [
                ['name' => 'Pemeriksaan awal', 'qty' => 1, 'price' => 'Rp20.000', 'subtotal' => 'Rp20.000'],
                ['name' => 'Konsultasi dokter umum', 'qty' => 1, 'price' => 'Rp80.000', 'subtotal' => 'Rp80.000'],
                ['name' => 'Perawatan luka sederhana', 'qty' => 1, 'price' => 'Rp35.000', 'subtotal' => 'Rp35.000'],
            ],
            'services_subtotal' => 'Rp135.000',
            'medicines' => [
                ['name' => 'Vitamin C 100 mg', 'qty' => '10 tablet', 'price' => 'Rp1.000', 'subtotal' => 'Rp10.000'],
                ['name' => 'Zinc 20 mg', 'qty' => '5 tablet', 'price' => 'Rp4.000', 'subtotal' => 'Rp20.000'],
            ],
            'medicines_subtotal' => 'Rp30.000',
            'discount'           => 'Rp0',
            'tax'                => 'Rp0',
            'total'              => 'Rp165.000',
        ];

        return view('kasir.billing', compact('billingData'));
    }

    /**
     * Simpan Penyusunan Tagihan.
     */
    public function storeBilling(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasAnyRole(['kasir', 'super_admin']), 403);

        return redirect()
            ->route('kasir.invoices')
            ->with('success', 'Tagihan untuk Deni Saputra (KJ-SIM-20261006-041) berhasil disusun dan diterbitkan otomatis.');
    }
}
