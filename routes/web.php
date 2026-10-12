<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PatientController::class, 'index'])->name('home');

Route::resource('patients', PatientController::class);
