<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $attemptCredentials = [
            $field     => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_active')) {
            $attemptCredentials['is_active'] = true;
        }

        if (Auth::attempt($attemptCredentials, $remember)) {
            $request->session()->regenerate();

            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'last_login_at')) {
                Auth::user()->update(['last_login_at' => now()]);
            }

            return redirect()->intended('/dashboard');
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
