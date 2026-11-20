<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConfirmacionAsistenciaController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('/cuenta-pendiente', 'auth.pending')
    ->middleware('auth')
    ->name('auth.pending');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'active'])
    ->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Eventos
    Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
    Route::get('/mis-confirmaciones', [ConfirmacionAsistenciaController::class, 'index'])
        ->middleware('role:Miembro')
        ->name('confirmaciones.index');
    Route::post('/confirmaciones', [ConfirmacionAsistenciaController::class, 'store'])->name('confirmaciones.store');
    Route::delete('/confirmaciones/{evento}', [ConfirmacionAsistenciaController::class, 'destroy'])->name('confirmaciones.destroy');

    Route::middleware(['role:Comité de Eventos'])->group(function () {
        Route::get('/eventos/crear', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
        Route::post('/eventos/{evento}/planificar', [EventoController::class, 'planificar'])->name('eventos.planificar');
    });

    Route::get('/eventos/{evento}', [EventoController::class, 'show'])->name('eventos.show');

    Route::middleware(['role:Comité de Eventos,Secretaría'])->group(function () {
        Route::get('/eventos/{evento}/editar', [EventoController::class, 'edit'])->name('eventos.edit');
        Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    });

    Route::middleware(['role:Pastor Principal'])->group(function () {
        Route::post('/eventos/{evento}/aprobar', [EventoController::class, 'aprobar'])->name('eventos.aprobar');
        Route::post('/eventos/{evento}/rechazar', [EventoController::class, 'rechazar'])->name('eventos.rechazar');
    });

    // Rutas de Secretaría
    Route::middleware(['role:Secretaría'])->group(function () {
        Route::post('/eventos/{evento}/finalizar', [EventoController::class, 'finalizar'])->name('eventos.finalizar');
        Route::get('/eventos/{evento}/acta', [ReporteController::class, 'acta'])->name('eventos.acta');
        Route::get('/eventos/{evento}/actas', [ReporteController::class, 'historial'])->name('eventos.actas.historial');

        Route::get('/convocatorias', [ConvocatoriaController::class, 'index'])->name('convocatorias.index');
        Route::get('/eventos/{evento}/convocatoria', [ConvocatoriaController::class, 'create'])->name('convocatorias.create');
        Route::post('/eventos/{evento}/convocatoria', [ConvocatoriaController::class, 'store'])->name('convocatorias.store');

    });

    Route::middleware(['role:Secretaría,Administrador'])->group(function () {
        Route::get('/miembros', [MiembroController::class, 'index'])->name('miembros.index');
        Route::get('/miembros/create', [MiembroController::class, 'create'])->name('miembros.create');
        Route::post('/miembros', [MiembroController::class, 'store'])->name('miembros.store');
        Route::get('/miembros/{user}', [MiembroController::class, 'show'])->name('miembros.show');
        Route::get('/miembros/{user}/editar', [MiembroController::class, 'edit'])->name('miembros.edit');
        Route::put('/miembros/{user}', [MiembroController::class, 'update'])->name('miembros.update');
        Route::patch('/miembros/{user}/estado', [MiembroController::class, 'cambiarEstado'])->name('miembros.estado');
        Route::delete('/miembros/{user}', [MiembroController::class, 'destroy'])->name('miembros.destroy');
        Route::post('/miembros/{user}/verificar', [MiembroController::class, 'verificar'])->name('miembros.verificar');
    });

    Route::get('/reportes', [ReporteController::class, 'index'])
        ->middleware('role:Pastor Principal,Secretaría')
        ->name('reportes.index');

    // Rutas para Usuarios (Solo Admin)
    Route::middleware(['role:Administrador'])->group(function () {
        Route::resource('roles', RoleController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
        Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{user}/estado', [UserController::class, 'cambiarEstado'])->name('usuarios.estado');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        Route::get('/configuraciones', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
        Route::match(['put', 'patch'], '/configuraciones', [ConfiguracionController::class, 'update'])->name('configuraciones.update');

        Route::get('/backups/ultimo', [BackupController::class, 'descargarUltimo'])->name('backups.ultimo');
        Route::get('/configuraciones/backups/ultimo', [BackupController::class, 'descargarUltimo'])->name('backups.descargar');
    });

});

require __DIR__.'/auth.php';
