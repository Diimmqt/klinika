<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'user.view' => 'Melihat daftar dan detail user',
            'user.create' => 'Membuat user baru',
            'user.update' => 'Mengubah data user',
            'user.delete' => 'Menonaktifkan / menghapus user',
            'role.view' => 'Melihat daftar dan detail role',
            'role.create' => 'Membuat role baru',
            'role.update' => 'Mengubah role dan permission-nya',
            'role.delete' => 'Menghapus role',
            'permission.view' => 'Melihat daftar permission',
            'permission.create' => 'Membuat permission baru',
            'permission.update' => 'Mengubah permission',
            'permission.delete' => 'Menghapus permission',
            'patient.view' => 'Melihat data pasien',
            'patient.create' => 'Mendaftarkan pasien baru',
            'patient.update' => 'Mengubah data pasien',
            'patient.delete' => 'Menghapus data pasien',
            'visit.view' => 'Melihat kunjungan',
            'visit.create' => 'Membuat kunjungan',
            'visit.update' => 'Mengubah kunjungan',
            'visit.delete' => 'Menghapus kunjungan',
            'visit.cancel' => 'Membatalkan kunjungan',
            'queue.view' => 'Melihat antrean',
            'queue.create' => 'Membuat antrean',
            'queue.call' => 'Memanggil antrean',
            'queue.cancel' => 'Membatalkan antrean',
            'vital.view' => 'Melihat tanda vital',
            'vital.create' => 'Mengisi tanda vital',
            'vital.update' => 'Mengubah tanda vital',
            'vital.finalize' => 'Finalisasi tanda vital',
            'medical_record.create' => 'Membuat rekam medis',
            'prescription.create' => 'Membuat resep',
            'medicine.view' => 'Melihat obat',
            'medicine.create' => 'Menambah data obat',
            'medicine.dispense' => 'Menyerahkan obat',
            'medicine.export' => 'Ekspor data obat',
            'invoice.create' => 'Membuat tagihan',
            'payment.create' => 'Memproses pembayaran',
            'master.view' => 'Melihat master data',
            'master.create' => 'Menambah master data',
            'master.update' => 'Mengubah master data',
            'master.delete' => 'Menghapus master data',
            'report.view' => 'Melihat laporan',
        ];

        foreach ($permissions as $name => $description) {
            Permission::findOrCreate($name, 'web');
            Permission::where('name', $name)->where('guard_name', 'web')
                ->update(['description' => $description]);
        }

        $roles = [
            'super_admin' => 'Super Admin — akses penuh sistem',
            'admin_klinik' => 'Admin Klinik — master data & operasional',
            'pendaftaran' => 'Petugas Pendaftaran',
            'perawat' => 'Perawat — pemeriksaan awal',
            'dokter' => 'Dokter — pemeriksaan & resep',
            'apoteker' => 'Apoteker — farmasi',
            'kasir' => 'Kasir — pembayaran',
            'manajemen' => 'Manajemen — laporan (read-only)',
        ];

        foreach ($roles as $name => $description) {
            $role = Role::findOrCreate($name, 'web');
            $role->update(['description' => $description]);
        }

        Role::findByName('super_admin', 'web')
            ->syncPermissions(array_keys($permissions));

        Role::findByName('pendaftaran', 'web')->syncPermissions([
            'patient.view', 'patient.create', 'patient.update',
            'visit.view', 'visit.create',
            'queue.view', 'queue.create',
        ]);

        Role::findByName('perawat', 'web')->syncPermissions([
            'patient.view', 'visit.view', 'queue.view', 'queue.call', 'vital.create',
        ]);

        Role::findByName('dokter', 'web')->syncPermissions([
            'patient.view', 'visit.view', 'queue.view', 'queue.call',
            'medical_record.create', 'prescription.create',
        ]);

        Role::findByName('apoteker', 'web')->syncPermissions([
            'patient.view', 'visit.view', 'medicine.dispense',
        ]);

        Role::findByName('kasir', 'web')->syncPermissions([
            'patient.view', 'visit.view', 'invoice.create', 'payment.create',
        ]);

        Role::findByName('manajemen', 'web')->syncPermissions([
            'patient.view', 'visit.view', 'queue.view', 'report.view',
        ]);

        Role::findByName('admin_klinik', 'web')->syncPermissions([
            'user.view', 'user.create', 'user.update',
            'role.view', 'permission.view',
            'patient.view', 'patient.create', 'patient.update',
            'visit.view', 'visit.create',
            'queue.view', 'queue.create',
            'report.view',
        ]);

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@klinika.id'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'is_active' => true,
            ]
        );

        $superAdmin->syncRoles(['super_admin']);
    }
}
