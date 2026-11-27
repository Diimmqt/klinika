<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Support login via email atau nama
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {
            $request->session()->regenerate();

            Auth::user()->update(['last_login_at' => now()]);

            return redirect()->intended('/dashboard');
        }

        $pendingUser = User::where($field, $credentials['email'])->first();
        if ($pendingUser && ! $pendingUser->is_active && is_null($pendingUser->activated_at) && Hash::check($credentials['password'], $pendingUser->password)) {
            return back()->withErrors([
                'email' => 'Akun Anda belum diaktifkan. Silakan klik tautan aktivasi yang dikirim ke email Anda terlebih dahulu.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email/nama atau kata sandi salah, atau akun tidak aktif.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
