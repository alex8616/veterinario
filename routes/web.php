<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;

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

    //mascotas
    Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
    Route::get('/get-mascotas', [MascotaController::class, 'GetMascotas'])->name('mascotas.get');
    Route::post('/crear-mascotas', [MascotaController::class, 'CrearMascota'])->name('mascotas.store');
    Route::get('/get-mascota/{id}', [MascotaController::class, 'GetMascota'])->name('mascota.get');

    //citas
    Route::get('/citas/data', [CitaController::class, 'index'])->name('citas.data');
    Route::get('/citas', [CitaController::class, 'indexPage'])->name('citas.index');
    Route::get('/citas/mascotas', [CitaController::class, 'mascotas'])->name('citas.mascotas');
    Route::get('/citas/veterinarios', [CitaController::class, 'veterinarios'])->name('citas.veterinarios');
    Route::get('/citas/horarios-disponibles', [CitaController::class, 'horariosDisponibles'])->name('citas.horariosDisponibles');
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
});