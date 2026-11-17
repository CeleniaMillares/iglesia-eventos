<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionAsistencia;
use App\Models\Evento;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ConfirmacionAsistenciaController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->tieneRol('Miembro'), 403);

        $validated = $request->validate([
            'evento_id' => ['required', 'integer', 'exists:eventos,id'],
        ]);

        $miembro = $request->user()->miembro;

        if (!$miembro) {
            return back()->with('error', 'No se encontró un perfil de miembro asociado a tu usuario.');
        }

        try {
            return DB::transaction(function () use ($validated, $miembro): RedirectResponse {
                $evento = Evento::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['evento_id']);

                Gate::authorize('confirmar', $evento);

                if ($evento->confirmaciones()->where('miembro_id', $miembro->id)->exists()) {
                    return back()->with('error', 'Ya has confirmado tu asistencia a este evento.');
                }

                if (!$evento->tieneCupo()) {
                    return back()->with('error', 'El evento ha alcanzado su capacidad máxima.');
                }

                ConfirmacionAsistencia::create([
                    'evento_id' => $evento->id,
                    'miembro_id' => $miembro->id,
                ]);

                return back()->with('success', 'Asistencia confirmada exitosamente.');
            });
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23505') {
                return back()->with('error', 'Ya has confirmado tu asistencia a este evento.');
            }

            throw $exception;
        }
    }
}
