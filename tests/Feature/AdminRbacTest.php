<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminRbacTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RbacSeeder::class);
        $this->superAdmin = User::where('email', 'superadmin@klinika.id')->firstOrFail();
        $this->actingAs($this->superAdmin);
    }

    public function test_admin_indexes_return_ok(): void
    {
        $this->get(route('admin.users.index'))->assertOk()->assertSee('Akun pengguna');
        $this->get(route('admin.roles.index'))->assertOk()->assertSee('Role dan hak akses');
        $this->get(route('admin.permissions.index'))->assertOk()->assertSee('Kelola Permission');
    }

    public function test_can_create_user_with_role_via_invitation(): void
    {
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Petugas Baru',
            'email' => 'petugas@klinika.id',
            'roles' => ['pendaftaran'],
            'send_invitation' => '1',
        ]);

        $user = User::where('email', 'petugas@klinika.id')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('pendaftaran'));
        $this->assertFalse($user->is_active, 'Akun baru menunggu aktivasi, belum aktif');
        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('created_user');
    }

    public function test_user_validation_fails_without_required_fields(): void
    {
        $this->post(route('admin.users.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'roles']);
    }

    public function test_can_create_role_with_permissions(): void
    {
        $response = $this->post(route('admin.roles.store'), [
            'name' => 'auditor',
            'description' => 'Role audit internal',
            'permissions' => ['patient.view', 'report.view'],
        ]);

        $role = Role::findByName('auditor', 'web');
        $this->assertTrue($role->hasPermissionTo('patient.view'));
        $this->assertTrue($role->hasPermissionTo('report.view'));
        $response->assertRedirect(route('admin.roles.show', $role));
    }

    public function test_can_edit_role_and_update_permissions(): void
    {
        $role = Role::create(['name' => 'Koordinator Pendaftaran', 'guard_name' => 'web', 'description' => 'Awal']);
        $role->givePermissionTo('queue.view');

        $this->get(route('admin.roles.edit', $role))->assertOk()
            ->assertSee('Koordinator Pendaftaran')
            ->assertSee('Izin role • draf perubahan')
            ->assertSee('Dampak perubahan izin');

        $this->put(route('admin.roles.update', $role), [
            'name' => 'Koordinator Pendaftaran',
            'description' => 'Koordinasi operasional loket pendaftaran klinik.',
            'permissions' => ['queue.view', 'queue.cancel'],
        ])->assertRedirect(route('admin.roles.show', $role));

        $role->refresh();
        $this->assertEquals('Koordinasi operasional loket pendaftaran klinik.', $role->description);
        $this->assertTrue($role->hasPermissionTo('queue.cancel'));
    }

    public function test_can_create_and_update_permission(): void
    {
        $this->post(route('admin.permissions.store'), [
            'name' => 'audit.view',
            'description' => 'Melihat audit log',
        ])->assertRedirect(route('admin.permissions.index'));

        $permission = Permission::findByName('audit.view', 'web');
        $this->assertEquals('Melihat audit log', $permission->description);

        $this->put(route('admin.permissions.update', $permission), [
            'name' => 'audit.view',
            'description' => 'Lihat log audit',
        ])->assertRedirect(route('admin.permissions.index'));

        $this->assertEquals('Lihat log audit', $permission->fresh()->description);
    }

    public function test_cannot_deactivate_last_active_super_admin(): void
    {
        $super = User::where('email', 'superadmin@klinika.id')->first();
        $this->assertNotNull($super);

        $this->delete(route('admin.users.destroy', $super))
            ->assertSessionHas('error');

        $this->assertTrue($super->fresh()->is_active);
    }

    public function test_seeded_super_admin_has_full_role(): void
    {
        $user = User::where('email', 'superadmin@klinika.id')->first();
        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertTrue($user->can('user.view'));
        $this->assertTrue($user->can('role.update'));
    }

    public function test_non_super_admin_cannot_access_admin_routes(): void
    {
        $staff = User::factory()->create();
        $staff->assignRole('pendaftaran');

        $this->actingAs($staff);

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('admin.users.index'))->assertForbidden();
        $this->get(route('admin.roles.index'))->assertForbidden();
        $this->get(route('admin.permissions.index'))->assertForbidden();
        $this->get(route('admin.settings.index'))->assertForbidden();
        $this->get(route('admin.audits.index'))->assertForbidden();
    }

    public function test_super_admin_can_view_audits(): void
    {
        $this->get(route('admin.audits.index'))->assertOk()
            ->assertSee('Audit aktivitas')
            ->assertSee('Pencarian dan filter aktivitas')
            ->assertSee('AUD-SIM-004')
            ->assertSee('Detail aktivitas • AUD-SIM-004')
            ->assertSee('kontak.kss@klinika.test');
    }

    public function test_can_render_and_update_system_settings(): void
    {
        $this->get(route('admin.settings.index'))->assertOk()
            ->assertSee('Konfigurasi sistem')
            ->assertSee('Umum')
            ->assertSee('Autentikasi dan sesi')
            ->assertSee('Akses seluruh modul');

        $response = $this->post(route('admin.settings.update'), [
            'app_name' => 'Klinika Cloud RME',
            'app_locale' => 'Bahasa Indonesia',
            'app_timezone' => 'WIB • Asia/Jakarta',
            'app_date_format' => 'DD MMMM YYYY',
            'session_idle_timeout' => '30 menit',
            'session_max_lifetime' => '8 jam',
            'password_min_length' => '12 karakter',
            'admin_2fa_enabled' => 'Diaktifkan',
            'invitation_expiry' => '24 jam',
            'password_reset_expiry' => '60 menit',
            'notify_access_request' => 'Dalam aplikasi dan email',
            'notify_role_change' => 'Dalam aplikasi',
            'security_digest' => 'Harian • 17.00 WIB',
            'admin_email' => 'admin@klinika.id',
        ]);

        $response->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('success');

        $this->get(route('admin.settings.index'))
            ->assertSee('Klinika Cloud RME')
            ->assertSee('admin@klinika.id');
    }
}
