<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = auth()->user();

        if ($user?->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user?->hasRole('pendaftaran')) {
            return redirect()->route('pendaftaran.dashboard');
        }

        if ($user?->hasRole('dokter')) {
            return redirect()->route('dokter.index');
        }

        if ($user?->hasRole('perawat')) {
            return redirect()->route('perawat.index');
        }

        if ($user?->can('patient.view')) {
            return redirect()->route('patients.index');
        }

        abort(403, 'Akun Anda belum memiliki akses ke modul aplikasi.');
    }
}
