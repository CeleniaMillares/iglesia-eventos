<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Eventos
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

    Route::middleware(['role:Comité de Eventos,Administrador'])->group(function () {
        Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
        Route::post('/eventos/{evento}/planificar', [EventoController::class, 'planificar'])->name('eventos.planificar');
    });

    Route::middleware(['role:Pastor Principal,Administrador'])->group(function () {
        Route::post('/eventos/{evento}/aprobar', [EventoController::class, 'aprobar'])->name('eventos.aprobar');
        Route::post('/eventos/{evento}/rechazar', [EventoController::class, 'rechazar'])->name('eventos.rechazar');
    });
    
       // Rutas para Miembros (Secretaría y Admin)
    Route::middleware(['role:Secretaría,Administrador'])->group(function () {
        Route::get('/miembros', [\App\Http\Controllers\MiembroController::class, 'index'])->name('miembros.index');
        Route::post('/miembros/{user}/verificar', [\App\Http\Controllers\MiembroController::class, 'verificar'])->name('miembros.verificar');
    });

    // Rutas para Usuarios (Solo Admin)
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('usuarios.index');
    });
    
   
    // Solo el Comité puede crear eventos
    Route::middleware(['role:Comité de Eventos,Administrador'])->group(function () {
        Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    });

    // Solo el Pastor puede aprobar eventos
    Route::middleware(['role:Pastor Principal,Administrador'])->group(function () {
        Route::post('/eventos/{evento}/aprobar', [EventoController::class, 'aprobar'])->name('eventos.aprobar');
    });
});

require __DIR__.'/auth.php';