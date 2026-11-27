<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivateAccountController extends Controller
{
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Tautan aktivasi tidak valid atau sudah kedaluwarsa. Hubungi Admin Klinik.',
            ]);
        }

        if (! $user->is_active || is_null($user->activated_at)) {
            $user->update([
                'is_active' => true,
                'activated_at' => $user->activated_at ?? now(),
            ]);
        }

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')->with(
            'success',
            'Akun Anda berhasil diaktifkan! Silakan login menggunakan email dan kata sandi awal dari email aktivasi.'
        );
    }
}
