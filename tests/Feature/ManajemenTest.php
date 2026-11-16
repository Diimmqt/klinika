<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ManajemenTest extends TestCase
{
    public function test_all_manajemen_pages_can_be_accessed_directly_without_authentication(): void
    {
        $pages = [
            '/manajemen' => 'Laporan Klinik',
            '/manajemen/kunjungan' => 'Statistik Kunjungan Pasien',
            '/manajemen/transaksi' => 'Laporan Transaksi',
            '/manajemen/farmasi' => 'Laporan Farmasi',
            '/manajemen/operasional' => 'Pemantauan Operasional Klinik',
        ];

        foreach ($pages as $url => $heading) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee($heading);
            $response->assertSee('Read-Only');
            $response->assertSee('Data simulasi');

            // Navigasi antarhalaman Role Manajemen harus tampil di sidebar
            $response->assertSee(route('manajemen.index'));
            $response->assertSee(route('manajemen.visits'));
            $response->assertSee(route('manajemen.transactions'));
            $response->assertSee(route('manajemen.pharmacy'));
            $response->assertSee(route('manajemen.operations'));
        }
    }

    public function test_manajemen_filters_work_via_get_query_parameters(): void
    {
        $this->get('/manajemen?period=30_hari')
            ->assertOk()
            ->assertSee('30 Hari Terakhir');

        $this->get('/manajemen/kunjungan?period=hari_ini&poli=Poli+Gigi')
            ->assertOk()
            ->assertSee('Poli Gigi')
            ->assertSee('Hari Ini');

        $this->get('/manajemen/transaksi?metode=QRIS&status=Lunas')
            ->assertOk()
            ->assertSee('TRX-20261112-001')
            ->assertDontSee('TRX-20261112-004');

        $this->get('/manajemen/farmasi?status=Menipis&q=Amoxicillin')
            ->assertOk()
            ->assertSee('1 item')
            ->assertSee('Amoxicillin 500mg Kapsul')
            ->assertDontSee('OBT-004');

        $this->get('/manajemen/operasional?poli=Poli+Umum&status=Bertugas')
            ->assertOk()
            ->assertSee('dr. Andi Pratama')
            ->assertDontSee('dr. Hendra Kusuma');
    }

    public function test_manajemen_routes_and_views_are_strictly_read_only(): void
    {
        foreach (Route::getRoutes() as $route) {
            if (str_starts_with((string) $route->getName(), 'manajemen.')) {
                $methods = $route->methods();
                $this->assertEqualsCanonicalizing(['GET', 'HEAD'], $methods, 'Route ' . $route->getName() . ' harus read-only.');
            }
        }

        foreach (['/manajemen', '/manajemen/kunjungan', '/manajemen/transaksi', '/manajemen/farmasi', '/manajemen/operasional'] as $url) {
            $html = $this->get($url)->getContent();
            $this->assertSame(1, preg_match('/<main\b[^>]*>(.*)<\/main>/is', $html, $matches));
            $mainHtml = $matches[1];

            $this->assertDoesNotMatchRegularExpression('/method=["\'](post|put|patch|delete)["\']/i', $mainHtml);
            $this->assertStringNotContainsString('name="_method"', $mainHtml);
        }
    }
}
