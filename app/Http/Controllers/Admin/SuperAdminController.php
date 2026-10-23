<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'klinik'      => 3,  // ponytail: hardcoded sim data, replace with Klinik::count() when model exists
            'users'       => User::where('is_active', true)->count(),
            'roles'       => Role::count(),
            'pending'     => User::where('is_active', false)->count(),
        ];

        // Simulated clinic data until Klinik model exists
        $clinics = collect([
            ['name' => 'Sehat Sentosa', 'city' => 'Bandung', 'code' => 'KSS-01', 'users' => 24, 'status' => 'Aktif'],
            ['name' => 'Bina Sehat',    'city' => 'Cimahi',  'code' => 'KBS-02', 'users' => 14, 'status' => 'Aktif'],
            ['name' => 'Medika Utama',  'city' => 'Garut',   'code' => 'KMU-03', 'users' => 10, 'status' => 'Aktif'],
        ]);

        return view('admin.super.index', compact('stats', 'clinics'));
    }
}
