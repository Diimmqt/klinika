<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CashierTest extends TestCase
{
    use RefreshDatabase;

    protected User $cashierUser;
    protected User $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $this->cashierUser = User::create([
            'name' => 'Budi Hartono',
            'email' => 'kasir@klinika.id',
            'password' => bcrypt('password'),
        ]);
        $this->cashierUser->assignRole('kasir');

        $this->unauthorizedUser = User::create([
            'name' => 'Pengguna Lain',
            'email' => 'other@klinika.id',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_can_render_cashier_dashboard_with_kpi_counters_and_waiting_list(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.index'));

        $response->assertStatus(200);
        $response->assertSee('Kas dan tagihan hari ini');
        $response->assertSee('Belum dibayar');
        $response->assertSee('Pembayaran hari ini');
        $response->assertSee('Penerimaan');
        $response->assertSee('Selisih kas');
        $response->assertSee('Konfirmasi sebelum mencatat pembayaran');
        $response->assertSee('Invoice menunggu pembayaran');
        $response->assertSee('Penerimaan per metode');
        $response->assertSee('Rekap shift');
        $response->assertSee('Buka invoice');
    }

    public function test_can_render_cashier_invoices_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.invoices'));

        $response->assertStatus(200);
        $response->assertSee('Invoice');
        $response->assertSee('Daftar tagihan');
        $response->assertSee('Belum dibayar');
        $response->assertSee('Lunas hari ini');
        $response->assertSee('Menunggu validasi');
        $response->assertSee('Rekap hari ini');
        $response->assertSee('Cari invoice, nama, atau nomor RM');
    }

    public function test_can_filter_invoices_by_search_keyword(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.invoices', ['search' => 'Aminah']));

        $response->assertStatus(200);
        $response->assertSee('Siti Aminah');
        $response->assertDontSee('Rizky Maulana');
    }

    public function test_can_render_cashier_invoice_detail_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.show', 'INV-20261006-023'));

        $response->assertStatus(200);
        $response->assertSee('Invoice INV-20261006-023');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Rincian tagihan');
        $response->assertSee('Ringkasan biaya');
        $response->assertSee('Pemeriksaan awal');
        $response->assertSee('Konsultasi dokter umum');
        $response->assertSee('Paracetamol 500 mg');
        $response->assertSee('Cetirizine 10 mg');
        $response->assertSee('Seluruh item telah final dan obat diserahkan');
        $response->assertSee('Jejak administrasi');
        $response->assertSee('Lanjut pembayaran');
    }

    public function test_can_render_cashier_payment_form_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.payment', 'INV-20261006-023'));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Metode dan nominal pembayaran');
        $response->assertSee('Ringkasan transaksi');
        $response->assertSee('Nominal diterima (Rp)');
        $response->assertSee('Kembalian (Rp)');
        $response->assertSee('Tinjau sebelum konfirmasi');
        $response->assertSee('Tinjau pembayaran');
    }

    public function test_can_render_cashier_payment_confirmation_modal_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.confirm', [
            'invoice'         => 'INV-20261006-023',
            'payment_method'  => 'Tunai',
            'amount_received' => '150.000',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Konfirmasi pembayaran Rp145.000?');
        $response->assertSee('Siti Aminah • RM-000128');
        $response->assertSee('Satu transaksi, satu kuitansi');
        $response->assertSee('Uang sudah diterima dan rincian transaksi sudah saya periksa.');
        $response->assertSee('Catat pembayaran');
    }

    public function test_can_process_cashier_payment_submission(): void
    {
        $payload = [
            'payment_method'  => 'Tunai',
            'amount_received' => '150.000',
            'notes'           => 'Lunas dengan uang pas/kembali',
        ];

        $response = $this->actingAs($this->cashierUser)->post(route('kasir.process-payment', 'INV-20261006-023'), $payload);

        $response->assertRedirect(route('kasir.receipt', 'INV-20261006-023'));
        $response->assertSessionHas('success');
    }

    public function test_can_render_cashier_receipt_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.receipt', 'INV-20261006-023'));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran berhasil');
        $response->assertSee('KWT-20261006-023');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Kuitansi pembayaran');
        $response->assertSee('Pemeriksaan awal + konsultasi');
        $response->assertSee('Obat RX-20261006-023 • diserahkan');
        $response->assertSee('Status akhir');
        $response->assertSee('Linimasa kunjungan');
        $response->assertSee('Cetak kuitansi');
    }

    public function test_can_render_cashier_billing_creation_page(): void
    {
        $response = $this->actingAs($this->cashierUser)->get(route('kasir.billing.create'));

        $response->assertStatus(200);
        $response->assertSee('Penyusunan tagihan');
        $response->assertSee('Pilih kunjungan layak tagih');
        $response->assertSee('Deni Saputra');
        $response->assertSee('Layanan dan tindakan final');
        $response->assertSee('Obat yang diserahkan');
        $response->assertSee('Ringkasan penyusunan');
        $response->assertSee('Pemeriksaan penerbitan');
        $response->assertSee('Simpan penyusunan');
    }

    public function test_can_store_cashier_compiled_billing(): void
    {
        $response = $this->actingAs($this->cashierUser)->post(route('kasir.billing.store'));

        $response->assertRedirect(route('kasir.invoices'));
        $response->assertSessionHas('success');
    }

    public function test_payment_validation_fails_for_invalid_input(): void
    {
        $response = $this->actingAs($this->cashierUser)->post(route('kasir.process-payment', 'INV-20261006-023'), [
            'payment_method' => 'InvalidMethod',
        ]);

        $response->assertSessionHasErrors(['payment_method', 'amount_received']);
    }

    public function test_unauthorized_user_cannot_access_cashier_routes(): void
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('kasir.index'));
        $response->assertStatus(403);

        $responseInvoices = $this->actingAs($this->unauthorizedUser)->get(route('kasir.invoices'));
        $responseInvoices->assertStatus(403);

        $responseDetail = $this->actingAs($this->unauthorizedUser)->get(route('kasir.show', 'INV-20261006-023'));
        $responseDetail->assertStatus(403);

        $responsePayment = $this->actingAs($this->unauthorizedUser)->get(route('kasir.payment', 'INV-20261006-023'));
        $responsePayment->assertStatus(403);

        $responseConfirm = $this->actingAs($this->unauthorizedUser)->get(route('kasir.confirm', 'INV-20261006-023'));
        $responseConfirm->assertStatus(403);

        $responseReceipt = $this->actingAs($this->unauthorizedUser)->get(route('kasir.receipt', 'INV-20261006-023'));
        $responseReceipt->assertStatus(403);

        $responseBilling = $this->actingAs($this->unauthorizedUser)->get(route('kasir.billing.create'));
        $responseBilling->assertStatus(403);
    }
}
