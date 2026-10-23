<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat semua permission sesuai ERD
        $permissions = [
            'patient.view', 'patient.create', 'patient.update', 'patient.delete',
            'visit.view', 'visit.create', 'visit.update',
            'queue.view', 'queue.create', 'queue.call',
            'vital.create', 'vital.view',
            'medical_record.view', 'medical_record.create',
            'prescription.view', 'prescription.create',
            'medicine.view', 'medicine.manage', 'medicine.dispense',
            'invoice.view', 'invoice.create',
            'payment.view', 'payment.create',
            'report.view',
            'user.view', 'user.create', 'user.update', 'user.delete',
            'role.view', 'role.manage',
            'permission.view', 'permission.manage',
            'poli.view', 'poli.manage',
            'practitioner.view', 'practitioner.manage',
            'schedule.view', 'schedule.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Role super-admin — bypass semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Buat user super admin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@klinika.test'],
            [
                'name'      => 'Super Admin',
                'password'  => bcrypt('password'),
                'is_active' => true,
            ]
        );

        $user->syncRoles([$superAdmin]);

        $this->command->info('Super Admin berhasil dibuat.');
        $this->command->info('Email   : superadmin@klinika.test');
        $this->command->info('Password: password');
        $this->command->warn('Ganti password setelah login pertama!');
    }
}
