<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SettingController extends Controller
{
    public const DEFAULT_SETTINGS = [
        'app_name' => 'Klinika RME',
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
        'admin_email' => 'admin@klinika.test',
    ];

    public function index(): View
    {
        $settings = Cache::get('system_settings', self::DEFAULT_SETTINGS);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'app_locale' => ['required', 'string'],
            'app_timezone' => ['required', 'string'],
            'app_date_format' => ['required', 'string'],
            'session_idle_timeout' => ['required', 'string'],
            'session_max_lifetime' => ['required', 'string'],
            'password_min_length' => ['required', 'string'],
            'admin_2fa_enabled' => ['required', 'string'],
            'invitation_expiry' => ['required', 'string'],
            'password_reset_expiry' => ['required', 'string'],
            'notify_access_request' => ['required', 'string'],
            'notify_role_change' => ['required', 'string'],
            'security_digest' => ['required', 'string'],
            'admin_email' => ['required', 'email', 'max:255'],
        ]);

        Cache::forever('system_settings', $validated);

        Log::info('System configuration updated by Super Admin', [
            'user_id' => auth()->id(),
            'settings' => $validated,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Konfigurasi sistem berhasil disimpan.');
    }
}
