<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Evento;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $porPagina = Configuracion::limiteEventosPorPagina();

        if ($user->tieneRol('Miembro')) {
            $miembroId = $user->miembro->id;

            $disponibles = Evento::query()
                ->whereIn('estado', Evento::ESTADOS_CONFIRMABLES)
                ->whereDoesntHave('confirmaciones', function ($query) use ($miembroId): void {
                    $query->where('miembro_id', $miembroId)
                        ->where('estado', 'confirmada');
                })
                ->orderBy('fecha_inicio', 'desc')
                ->paginate($porPagina, ['*'], 'disponibles_page');

            return view('eventos.index', compact('disponibles'));
        }

        $eventos = Evento::query()->with('creador');

        if ($user->tieneRol('Secretaría')) {
            $eventos->with('actas.generadoPor');
        }

        if ($user->tieneRol('Pastor Principal')) {
            if ($request->query('estado') === 'aprobado') {
                $eventos->whereIn('estado', ['aprobado', 'planificado']);
            } else {
                $eventos->where('estado', 'pendiente');
            }
        } elseif ($user->tieneRol('Secretaría')) {
            $eventos->whereIn('estado', ['aprobado', 'planificado', 'finalizado']);

            if ($request->query('estado') === 'programados') {
                $eventos->whereIn('estado', Evento::ESTADOS_CONFIRMABLES);
            }

            if ($request->boolean('para_convocar')) {
                $eventos->whereDoesntHave('convocatorias');
            }
        } elseif ($user->tieneRol('Comité de Eventos')) {
            $eventos->where('user_creador_id', $user->id);

            if ($request->query('estado') === 'pendiente') {
                $eventos->where('estado', 'pendiente');
            }
        } elseif ($request->query('estado') === 'pendiente') {
            $eventos->where('estado', 'pendiente');
        }

        $eventos = $eventos->orderBy('fecha_inicio', 'desc')->paginate($porPagina)->withQueryString();

        return view('eventos.index', compact('eventos'));
    }

    public function show(Evento $evento): View
    {
        $evento->load(['creador', 'responsablePrincipal', 'actas.generadoPor']);

        return view('eventos.show', compact('evento'));
    }

    public function create()
    {
        return view('eventos.create', [
            'responsables' => $this->responsables(),
        ]);
    }

    public function edit(Evento $evento): View
    {
        Gate::authorize('update', $evento);

        return view('eventos.edit', [
            'evento' => $evento,
            'responsables' => $this->responsables(),
        ]);
    }

    public function update(Request $request, Evento $evento): RedirectResponse
    {
        Gate::authorize('update', $evento);

        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'hora' => ['nullable', 'date_format:H:i'],
            'lugar' => ['required', 'string', 'max:100'],
            'objetivos' => ['required', 'string', 'max:1000'],
            'capacidad_maxima' => ['nullable', 'integer', 'min:0'],
            'tipo_evento' => ['nullable', 'string', 'max:100'],
            'duracion_dias' => ['nullable', 'integer', 'min:1'],
            'responsable_principal_id' => ['nullable', 'integer', Rule::in($this->responsables()->modelKeys())],
            'cronograma' => ['nullable', 'string'],
        ]);

        $evento->update($validatedData);

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado exitosamente.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'hora' => ['nullable', 'date_format:H:i'],
            'lugar' => ['required', 'string', 'max:100'],
            'objetivos' => ['required', 'string', 'max:1000'], // RN-02: Obligatorio
            'capacidad_maxima' => ['nullable', 'integer', 'min:0'],
            'tipo_evento' => ['nullable', 'string', 'max:100'],
            'duracion_dias' => ['nullable', 'integer', 'min:1'],
            'responsable_principal_id' => ['nullable', 'integer', Rule::in($this->responsables()->modelKeys())],
            'cronograma' => ['nullable', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $validatedData['estado'] = 'pendiente';
            $validatedData['user_creador_id'] = Auth::id();

            Evento::create($validatedData);

            DB::commit();

            return redirect()->route('eventos.index')
                ->with('success', 'Evento propuesto exitosamente. Esperando aprobación del Pastor.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al crear el evento: '.$e->getMessage())->withInput();
        }
    }

    public function aprobar(Evento $evento)
    {
        if (! Auth::user()->tieneAlgunRol(['Pastor Principal'])) {
            abort(403, 'No autorizado.');
        }

        if ($evento->estado !== 'pendiente') {
            abort(409, 'Transición de estado no permitida.');
        }

        $evento->update([
            'estado' => 'aprobado',
            'user_aprobador_id' => Auth::id(),
        ]);

        app(EmailService::class)->notificarDecisionComite($evento->fresh());

        return back()->with('success', 'Evento aprobado. El Comité ya puede planificar.');
    }

    public function rechazar(Request $request, Evento $evento)
    {
        if (! Auth::user()->tieneAlgunRol(['Pastor Principal'])) {
            abort(403, 'No autorizado.');
        }

        if ($evento->estado !== 'pendiente') {
            abort(409, 'Transición de estado no permitida.');
        }

        $request->validate(['motivo_rechazo' => 'required|string|max:500']);

        $evento->update([
            'estado' => 'rechazado',
            'motivo_rechazo' => $request->motivo_rechazo,
            'user_aprobador_id' => Auth::id(),
        ]);

        app(EmailService::class)->notificarDecisionComite($evento->fresh());

        return back()->with('success', 'Evento rechazado y notificado al Comité.');
    }

    public function planificar(Evento $evento)
    {
        if (! Auth::user()->tieneRol('Comité de Eventos')) {
            abort(403, 'No autorizado.');
        }

        if ($evento->estado !== 'aprobado') {
            return back()->with('error', 'Solo se pueden planificar eventos aprobados.');
        }

        $evento->update(['estado' => 'planificado']);

        return back()->with('success', 'Evento planificado. Ahora la Secretaría puede enviar convocatorias.');
    }

    public function finalizar(Evento $evento)
    {
        if (! Auth::user()->tieneRol('Secretaría')) {
            abort(403);
        }

        if (! in_array($evento->estado, ['aprobado', 'planificado'], true)) {
            abort(409, 'Transición de estado no permitida.');
        }

        $fechaReferencia = $evento->fecha_fin ?? $evento->fecha_inicio;

        if ($fechaReferencia?->isFuture()) {
            abort(409, 'Transición de estado no permitida.');
        }

        $evento->update(['estado' => 'finalizado']);

        return back()->with('success', 'Evento finalizado. El acta en PDF ya está disponible.');
    }

    private function responsables()
    {
        return User::query()
            ->whereHas('roles', function ($query): void {
                $query->whereIn('nombre', ['Pastor Principal', 'Comité de Eventos', 'Secretaría']);
            })
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
    }
}
