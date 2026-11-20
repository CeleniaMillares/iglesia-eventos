<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function descargarUltimo(): RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        abort_unless(Auth::user()?->tieneRol('Administrador'), 403);

        $archivos = collect(Storage::disk('backups')->files())
            ->filter(fn (string $archivo): bool => str_ends_with($archivo, '.sql'))
            ->sortByDesc(fn (string $archivo): int => Storage::disk('backups')->lastModified($archivo))
            ->values();

        if ($archivos->isEmpty()) {
            return redirect()->route('configuraciones.index')
                ->with('error', 'Todavía no hay backups disponibles.');
        }

        $archivo = $archivos->first();

        return Storage::disk('backups')->download($archivo, basename($archivo), [
            'Content-Type' => 'application/sql',
        ]);
    }
}
