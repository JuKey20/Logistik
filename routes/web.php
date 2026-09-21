<?php

use App\Enums\UserRole;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KaryawanStatusController;
use App\Http\Controllers\OperationalFunctionController;
use App\Http\Controllers\OperationalFunctionStatusController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::bind('karyawan', function (string $value): User {
    return User::query()
        ->whereKey($value)
        ->where('role', UserRole::Karyawan)
        ->firstOrFail();
});

Route::inertia('/', 'welcome')->name('home');

Route::middleware('auth')->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::patch('karyawan/{karyawan}/deactivate', [KaryawanStatusController::class, 'deactivate'])
        ->name('karyawan.deactivate');
    Route::patch('karyawan/{karyawan}/reactivate', [KaryawanStatusController::class, 'reactivate'])
        ->name('karyawan.reactivate');
    Route::resource('karyawan', KaryawanController::class)
        ->except(['show', 'destroy']);

    Route::patch('operational-functions/{operational_function}/deactivate', [OperationalFunctionStatusController::class, 'deactivate'])
        ->name('operational-functions.deactivate');
    Route::patch('operational-functions/{operational_function}/reactivate', [OperationalFunctionStatusController::class, 'reactivate'])
        ->name('operational-functions.reactivate');
    Route::resource('operational-functions', OperationalFunctionController::class)
        ->parameters(['operational-functions' => 'operational_function'])
        ->except(['show', 'destroy']);
});

require __DIR__.'/settings.php';
