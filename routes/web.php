<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PatientController::class, 'index'])->name('home');

Route::resource('patients', PatientController::class);
Route::resource('visits', VisitController::class);

Route::get('queues', [QueueController::class, 'index'])->name('queues.index');
Route::patch('queues/{queue}/status', [QueueController::class, 'updateStatus'])->name('queues.update-status');
