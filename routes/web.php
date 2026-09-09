<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DelegadoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// --- Rutas Públicas de Cobertura Deportiva ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/clasificacion', [HomeController::class, 'clasificacion'])->name('clasificacion');
Route::get('/equipos', [HomeController::class, 'equipos'])->name('equipos');
Route::get('/campeones', [HomeController::class, 'campeones'])->name('campeones');
Route::get('/d/{slug}', [HomeController::class, 'disciplina'])->name('disciplina.show');

// --- Autenticación Unificada (Delegados y Admin) ---
Route::get('/entrar', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/entrar', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Portal Restringido de Delegados de UGEL ---
Route::middleware(['auth', 'delegado'])->prefix('delegado')->name('delegado.')->group(function () {
    Route::get('/', [DelegadoController::class, 'index'])->name('index');
    Route::post('/marcador', [DelegadoController::class, 'actualizarMarcador'])->name('marcador');
    Route::post('/atletas', [DelegadoController::class, 'guardarAtleta'])->name('atleta.guardar');
    Route::delete('/atletas/{id}', [DelegadoController::class, 'eliminarAtleta'])->name('atleta.eliminar');
});

// --- Suite de Administración General (Acceso directo por URL o login de admin) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::post('/torneo', [AdminController::class, 'actualizarTorneo'])->name('torneo.update');
    Route::post('/deportes', [AdminController::class, 'guardarDeporte'])->name('deporte.guardar');
    Route::delete('/deportes/{id}', [AdminController::class, 'eliminarDeporte'])->name('deporte.eliminar');
    Route::post('/delegaciones', [AdminController::class, 'guardarDelegacion'])->name('delegacion.guardar');
    Route::delete('/delegaciones/{id}', [AdminController::class, 'eliminarDelegacion'])->name('delegacion.eliminar');
    Route::post('/partidos', [AdminController::class, 'guardarPartido'])->name('partido.guardar');
    Route::post('/marcadores', [AdminController::class, 'actualizarMarcador'])->name('marcador.update');
    Route::delete('/partidos/{id}', [AdminController::class, 'eliminarPartido'])->name('partido.eliminar');
    Route::post('/delegados', [AdminController::class, 'guardarDelegado'])->name('delegado.guardar');
    Route::delete('/delegados/{id}', [AdminController::class, 'eliminarDelegado'])->name('delegado.eliminar');
    Route::post('/reiniciar', [AdminController::class, 'reiniciarBd'])->name('reiniciar');
});
