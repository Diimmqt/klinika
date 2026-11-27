<?php

namespace Tests\Feature;

use App\Mail\AccountActivationMail;
use App\Models\User;
use Database\Seeders\RbacSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        Mail::fake();

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
        $this->assertNull($user->activated_at);
        $this->assertNull($user->password_changed_at);
        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('created_user');

        $capturedMail = null;
        Mail::assertSent(AccountActivationMail::class, function (AccountActivationMail $mail) use (&$capturedMail) {
            $capturedMail = $mail;

            return $mail->hasTo('petugas@klinika.id')
                && $mail->user->name === 'Petugas Baru'
                && $mail->roleLabel === 'Pendaftaran'
                && strlen($mail->initialPassword) >= 8;
        });

        $this->assertNotNull($capturedMail);
        $this->assertTrue(Hash::check($capturedMail->initialPassword, $user->password));
        $renderedHtml = $capturedMail->render();
        $this->assertStringContainsString('Petugas Baru', $renderedHtml);
        $this->assertStringContainsString('Pendaftaran', $renderedHtml);
        $this->assertStringContainsString($capturedMail->initialPassword, $renderedHtml);

        // Logout dari sesi Super Admin untuk menguji login & aktivasi sebagai calon user
        $this->post(route('logout'));

        // Sebelum aktivasi: tidak bisa login walaupun password awal benar
        $this->post(route('login'), [
            'email' => 'petugas@klinika.id',
            'password' => $capturedMail->initialPassword,
        ])->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // Tautan tanpa signature valid ditolak
        $this->get(route('account.activate', ['user' => $user->id]))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email']);
        $this->assertFalse($user->fresh()->is_active);

        // Klik tautan aktivasi dari email -> akun aktif & diarahkan ke halaman login
        $this->get($capturedMail->activationUrl)
            ->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->is_active);
        $this->assertNotNull($user->activated_at);
        $this->assertNull($user->password_changed_at);

        // Login pertama menggunakan email & password awal dari email
        $this->post(route('login'), [
            'email' => 'petugas@klinika.id',
            'password' => $capturedMail->initialPassword,
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
        $this->assertNull($user->fresh()->password_changed_at);
    }

    public function test_can_render_edit_user_page_with_password_reset_option(): void
    {
        $user = User::factory()->create(['name' => 'Sinta Rahma', 'email' => 'sinta@klinika.test']);
        $user->assignRole('pendaftaran');

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSee('Edit akun pengguna')
            ->assertSee('Sinta Rahma')
            ->assertSee('Reset password (opsional)')
            ->assertSee('Kata sandi baru')
            ->assertSee('Konfirmasi kata sandi baru')
            ->assertSee('Batas perubahan akses')
            ->assertSee('Validasi sebelum simpan');
    }

    public function test_can_update_user_and_reset_password(): void
    {
        $user = User::factory()->create(['name' => 'Sinta Rahma', 'email' => 'sinta@klinika.test']);
        $user->assignRole('pendaftaran');

        $res = $this->put(route('admin.users.update', $user), [
            'name' => 'Sinta Rahma Updated',
            'email' => 'sinta.updated@klinika.test',
            'roles' => ['pendaftaran'],
            'is_active' => '1',
            'audit_note' => 'Perbarui informasi akun dan ganti password.',
            'password' => 'NewSecretPassword123!',
            'password_confirmation' => 'NewSecretPassword123!',
        ]);

        $res->assertRedirect(route('admin.users.show', $user));
        $user->refresh();

        $this->assertEquals('Sinta Rahma Updated', $user->name);
        $this->assertEquals('sinta.updated@klinika.test', $user->email);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewSecretPassword123!', $user->password));
    }

    public function test_update_user_fails_if_password_confirmation_mismatches(): void
    {
        $user = User::factory()->create(['name' => 'Sinta Rahma', 'email' => 'sinta@klinika.test']);
        $user->assignRole('pendaftaran');

        $res = $this->put(route('admin.users.update', $user), [
            'name' => 'Sinta Rahma',
            'email' => 'sinta@klinika.test',
            'roles' => ['pendaftaran'],
            'password' => 'NewSecretPassword123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $res->assertSessionHasErrors(['password']);
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
