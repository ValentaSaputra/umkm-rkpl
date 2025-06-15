<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

use App\Http\Controllers\HppCalculatorController;

Route::get('/kalkulator-hpp', [HppCalculatorController::class, 'index'])->name('kalkulator.hpp');
Route::post('/kalkulator-hpp', [HppCalculatorController::class, 'calculate'])->name('kalkulator.hpp.calculate');



use App\Http\Controllers\SalesTargetController;

// Sales Target Routes
Route::prefix('sales-target')->name('sales-target.')->group(function () {
    Route::get('/', [SalesTargetController::class, 'index'])->name('index');
    Route::post('/update-target', [SalesTargetController::class, 'updateTarget'])->name('update');
    Route::post('/update-achievement', [SalesTargetController::class, 'updateAchievement'])->name('update-achievement');
    Route::get('/data', [SalesTargetController::class, 'getTargetData'])->name('data');
    Route::get('/history', [SalesTargetController::class, 'history'])->name('history');
});

// Alternative routes (untuk kompatibilitas dengan kode lama)
Route::get('/target-penjualan', [SalesTargetController::class, 'index'])->name('target-penjualan');
Route::post('/update-target', [SalesTargetController::class, 'updateTarget'])->name('updateTarget');

// Route untuk menampilkan dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route untuk mengupdate target penjualan
Route::post('/update-target', [DashboardController::class, 'updateTarget'])->name('updateTarget');


use App\Http\Controllers\OrderController;

Route::middleware(['web'])->group(function () {
    Route::get('/input-pesanan', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/input-pesanan', [OrderController::class, 'store'])->name('orders.store');
});
