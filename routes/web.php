<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;

Route::get('/', function () {
    return view('welcome');
});

// Registro
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Dashboard
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
    Route::get('/get-mascotas', [MascotaController::class, 'GetMascotas'])->name('mascotas.get');
    Route::post('/crear-mascotas', [MascotaController::class, 'CrearMascota'])->name('mascotas.store');
});