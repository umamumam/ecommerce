<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/email/verification-status', function () {
        return response()->json([
            'verified' => auth()->user()->hasVerifiedEmail(),
        ]);
    })->name('verification.status');

    Route::get('/account', [\App\Http\Controllers\AccountController::class, 'index'])->name('account');
    Route::get('/account/profile', [\App\Http\Controllers\AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [\App\Http\Controllers\AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/address', [\App\Http\Controllers\AccountController::class, 'address'])->name('account.address');
    Route::post('/account/address', [\App\Http\Controllers\AccountController::class, 'updateAddress'])->name('account.address.update');
});

require __DIR__.'/auth.php';
