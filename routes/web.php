<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\HistoriaClinicaController;
use App\Http\Controllers\NoticiaController;

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
    Route::patch('/citas/{cita}/cancelar',[CitaController::class,'cancelar'])->name('citas.cancelar');

    //cliente
    Route::get('/perfil',[PerfilController::class,'index'])->name('perfil');
    Route::get('/perfil/data',[PerfilController::class,'data'])->name('perfil.data');
    Route::put('/perfil',[PerfilController::class,'update'])->name('perfil.update');

    //historial clinico
    Route::get('/historia-clinica',[HistoriaClinicaController::class,'index'])->name('historia-clinica');
    Route::get('/historia-clinica/mascotas',[HistoriaClinicaController::class,'mascotas'])->name('historia-clinica.mascotas');
    Route::get('/historia-clinica/consultas/{mascotaId}',[HistoriaClinicaController::class,'consultas'])->name('historia-clinica.consultas');
    Route::get('/historia-clinica/vacunas/{mascotaId}',[HistoriaClinicaController::class,'vacunas'])->name('historia-clinica.vacunas');
    Route::get('/historia-clinica/desparasitaciones/{mascotaId}',[HistoriaClinicaController::class,'desparasitaciones'])->name('historia-clinica.desparasitaciones');
    Route::get('/historia-clinica/tratamientos/{mascotaId}',[HistoriaClinicaController::class,'tratamientos'])->name('historia-clinica.tratamientos');

    //noticias
    Route::get('/noticias/data',[NoticiaController::class,'data'])->name('noticias.data');
    Route::get('/noticias',[NoticiaController::class,'index'])->name('noticias.index');
    Route::get('/noticias/{noticia}',[NoticiaController::class,'show'])->name('noticias.show');
    Route::post('/noticias/{noticia}/like',[NoticiaController::class,'like'])->name('noticias.like');
    Route::get('/noticias/{noticia}/comentarios',[NoticiaController::class,'comentarios'])->name('noticias.comentarios');
    Route::post('/noticias/{noticia}/comentarios',[NoticiaController::class,'guardarComentario'])->name('noticias.comentarios.guardar');
    Route::delete('/noticias/{noticia}/comentarios/{comentario}',[NoticiaController::class,'eliminarComentario'])->name('noticias.comentarios.eliminar');
});