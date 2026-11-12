<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKlinikTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminKlinik;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);

        $this->adminKlinik = User::factory()->create([
            'name'      => 'Ratna Dewi',
            'email'     => 'ratna@klinika.id',
            'is_active' => true,
        ]);
        $this->adminKlinik->assignRole('admin_klinik');

        $this->regularUser = User::factory()->create([
            'name'      => 'User Biasa',
            'email'     => 'biasa@klinika.id',
            'is_active' => true,
        ]);
    }

    public function test_admin_klinik_dashboard_redirect(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('admin.operasional'));
    }

    public function test_operasional_page_renders_successfully(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->get(route('admin.operasional'));
        $response->assertOk();
        $response->assertSee('Operasional Klinik Sehat Sentosa');
        $response->assertSee('Kelola jadwal');
        $response->assertSee('Kunjungan hari ini');
        $response->assertSee('Kunjungan tujuh hari terakhir');
        $response->assertSee('Perlu perhatian');
        $response->assertSee('Kesiapan layanan');
        $response->assertSee('Poli Umum');
        $response->assertSee('dr. Andi Prasetyo');
    }

    public function test_jadwal_dokter_page_renders_successfully(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->get(route('admin.master.jadwal-dokter'));
        $response->assertOk();
        $response->assertSee('Jadwal dokter');
        $response->assertSee('Jadwal Selasa');
        $response->assertSee('dr. Andi Prasetyo');
        $response->assertSee('Poli Umum');
        $response->assertSee('Edit jadwal • dr. Andi Prasetyo');
    }

    public function test_jadwal_dokter_can_be_updated(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->post(route('admin.master.jadwal-dokter.update'), [
            'dokter'   => 'dr. Andi Prasetyo',
            'poli'     => 'Poli Umum',
            'hari'     => 'Selasa',
            'mulai'    => '08.00',
            'selesai'  => '12.00',
            'kuota'    => 40,
            'ruang'    => '01',
            'periode'  => '1 Oktober–31 Desember 2026',
        ]);

        $response->assertRedirect(route('admin.master.jadwal-dokter'));
        $response->assertSessionHas('success');
    }

    public function test_master_poli_page_renders_successfully(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->get(route('admin.master.poli'));
        $response->assertOk();
        $response->assertSee('Master poli');
        $response->assertSee('Poli klinik');
        $response->assertSee('POL-01 • Poli Umum');
        $response->assertSee('Edit poli • POL-01');
    }

    public function test_master_poli_can_be_updated(): void
    {
        $this->actingAs($this->adminKlinik);

        $response = $this->post(route('admin.master.poli.update'), [
            'name'     => 'Poli Umum',
            'location' => 'Ruang 01',
            'prefix'   => 'A',
            'quota'    => 40,
            'status'   => 'Aktif',
        ]);

        $response->assertRedirect(route('admin.master.poli'));
        $response->assertSessionHas('success');
    }

    public function test_unauthorized_user_cannot_access_admin_pages(): void
    {
        $this->actingAs($this->regularUser);

        $this->get(route('admin.operasional'))->assertForbidden();
        $this->get(route('admin.master.jadwal-dokter'))->assertForbidden();
        $this->get(route('admin.master.poli'))->assertForbidden();
    }
}
