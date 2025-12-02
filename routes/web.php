<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('gas.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de gestión de gas
    Route::prefix('gas')->name('gas.')->group(function () {
        Route::get('/', App\Livewire\Gas\Dashboard::class)->name('dashboard');
        Route::get('/install', App\Livewire\Gas\InstallBottle::class)->name('install');
        Route::get('/history', App\Livewire\Gas\History::class)->name('history');
        Route::get('/purchases', App\Livewire\Gas\Purchases::class)->name('purchases');
    });
});

require __DIR__ . '/auth.php';
