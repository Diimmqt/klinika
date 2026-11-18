<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApotekerTest extends TestCase
{
    use RefreshDatabase;

    protected User $apotekerUser;
    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'apoteker',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        $this->apotekerUser = User::create([
            'name'     => 'Rina Susanti',
            'email'    => 'apoteker@klinika.test',
            'password' => bcrypt('password'),
        ]);
        $this->apotekerUser->assignRole('apoteker');

        $this->otherUser = User::create([
            'name'     => 'Other User',
            'email'    => 'other@klinika.test',
            'password' => bcrypt('password'),
        ]);
        // no role assigned
    }

    public function test_dashboard_redirects_to_apoteker_index(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('dashboard'));
        $response->assertRedirect(route('apoteker.index'));
    }

    public function test_dashboard_renders(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.index'));
        $response->assertOk();
        $response->assertSee('Pelayanan farmasi');
        $response->assertSee('Menunggu verifikasi');
        $response->assertSee('RX-20261006-023');
    }

    public function test_resep_index_renders(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.resep.index'));
        $response->assertOk();
        $response->assertSee('Daftar resep');
        $response->assertSee('RX-20261006-021');
        $response->assertSee('Diracik');
    }

    public function test_resep_show_renders(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.resep.show', 'RX-20261006-023'));
        $response->assertOk();
        $response->assertSee('RX-20261006-023');
        $response->assertSee('Siti Aminah');
        $response->assertSee('Batch dan kesiapan obat');
        $response->assertSee('Catat penyerahan obat');
    }

    public function test_obat_index_renders(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.obat.index'));
        $response->assertOk();
        $response->assertSee('Data obat');
        $response->assertSee('Paracetamol');
        $response->assertSee('OBT-002');
    }

    public function test_obat_index_shows_edit_form(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.obat.index', ['edit' => 'OBT-002']));
        $response->assertOk();
        $response->assertSee('Edit obat');
        $response->assertSee('Cetirizine');
        $response->assertSee('Simpan obat');
    }

    public function test_obat_store_redirects(): void
    {
        $response = $this->actingAs($this->apotekerUser)->post(route('apoteker.obat.store'), [
            'nama' => 'Cetirizine', 'kekuatan' => '10 mg', 'bentuk' => 'Tablet',
            'harga' => 5000, 'min' => 20, 'status' => 'Aktif',
        ]);
        $response->assertRedirect(route('apoteker.obat.index'));
    }

    public function test_stok_index_renders(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.stok.index'));
        $response->assertOk();
        $response->assertSee('Stok obat');
        $response->assertSee('Cetirizine 10 mg');
        $response->assertSee('Stok rendah');
    }

    public function test_stok_index_shows_mutasi_panel(): void
    {
        $response = $this->actingAs($this->apotekerUser)->get(route('apoteker.stok.index', ['mutasi' => 'CTZ-2608-B']));
        $response->assertOk();
        $response->assertSee('Mutasi');
        $response->assertSee('RX-20261006-023');
    }

    public function test_stok_store_redirects(): void
    {
        $response = $this->actingAs($this->apotekerUser)->post(route('apoteker.stok.store'), [
            'obat' => 'Cetirizine 10 mg', 'jumlah' => 100,
            'batch' => 'CTZ-2610-C', 'kadaluwarsa' => '2027-10-31', 'dokumen' => 'PO-001',
        ]);
        $response->assertRedirect(route('apoteker.stok.index'));
    }

    public function test_unauthorized_user_cannot_access(): void
    {
        $response = $this->actingAs($this->otherUser)->get(route('apoteker.index'));
        $response->assertStatus(403);
    }
}
