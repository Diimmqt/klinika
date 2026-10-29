<?php

use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

// ── Auth ─────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('patients', PatientController::class);
    Route::resource('visits', VisitController::class);

    Route::get('queues', [QueueController::class, 'index'])->name('queues.index');
    Route::patch('queues/{queue}/status', [QueueController::class, 'updateStatus'])->name('queues.update-status');

    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [PendaftaranController::class, 'dashboard'])->name('dashboard');
    });

    Route::prefix('perawat')->name('perawat.')->group(function () {
        Route::get('/', [NurseController::class, 'index'])->name('index');
        Route::get('queue', [NurseController::class, 'queue'])->name('queue');
        Route::get('examination/{queue}', [NurseController::class, 'examination'])->name('examination');
        Route::post('examination/{queue}', [NurseController::class, 'storeExamination'])->name('examination.store');
    });

    Route::prefix('dokter')->name('dokter.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('queue', [DoctorController::class, 'queue'])->name('queue');
        Route::get('examination/{queue}', [DoctorController::class, 'examination'])->name('examination');
        Route::post('examination/{queue}', [DoctorController::class, 'storeExamination'])->name('examination.store');
        Route::get('patient/{patient}/rme', [DoctorController::class, 'rme'])->name('rme');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:super_admin')->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');
        Route::get('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
        Route::get('users/{user}/reset-password', [UserController::class, 'showResetPassword'])->name('users.reset-password');
        Route::post('users/{user}/reset-password', [UserController::class, 'sendResetPassword'])->name('users.send-reset-password');
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::get('audits', [AuditController::class, 'index'])->name('audits.index');
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
