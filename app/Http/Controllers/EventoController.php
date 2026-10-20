<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Si es Comité, solo ve sus propios eventos creados
        if ($user->tieneAlgunRol(['Comité de Eventos'])) {
            $eventos = Evento::where('user_creador_id', $user->id)->orderBy('fecha_inicio', 'desc')->paginate(10);
        } else {
            // Pastor, Secretaría y Admin ven todos
            $eventos = Evento::with('creador')->orderBy('fecha_inicio', 'desc')->paginate(10);
        }

        return view('eventos.index', compact('eventos'));
    }

    public function create()
    {
        return view('eventos.create');
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
            return back()->with('error', 'Error al crear el evento: ' . $e->getMessage())->withInput();
        }
    }

    public function aprobar(Evento $evento)
    {
        if (!Auth::user()->tieneAlgunRol(['Pastor Principal', 'Administrador'])) {
             abort(403, 'No autorizado.');
        }

        $evento->update([
            'estado' => 'aprobado',
            'user_aprobador_id' => Auth::id()
        ]);

        return back()->with('success', 'Evento aprobado. El Comité ya puede planificar.');
    }

    public function rechazar(Request $request, Evento $evento)
    {
        if (!Auth::user()->tieneAlgunRol(['Pastor Principal', 'Administrador'])) {
             abort(403, 'No autorizado.');
        }

        $request->validate(['motivo_rechazo' => 'required|string|max:500']);

        $evento->update([
            'estado' => 'rechazado',
            'motivo_rechazo' => $request->motivo_rechazo,
            'user_aprobador_id' => Auth::id()
        ]);

        return back()->with('success', 'Evento rechazado y notificado al Comité.');
    }

    public function planificar(Evento $evento)
    {
        if (!Auth::user()->tieneAlgunRol(['Comité de Eventos', 'Administrador'])) {
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
        if (!Auth::user()->tieneAlgunRol(['Secretaría', 'Administrador'])) {
             abort(403);
        }
        
        $evento->update(['estado' => 'finalizado']);
        return back()->with('success', 'Evento finalizado. El acta en PDF ya está disponible.');
    }

}