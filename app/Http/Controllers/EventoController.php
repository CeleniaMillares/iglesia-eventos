<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    // Listar eventos con paginación (Evita consultas N+1 usando with)
    public function index()
    {
        $eventos = Evento::with('creador')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10); 
            
        return view('eventos.index', compact('eventos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        return view('eventos.create');
    }

    // Guardar evento (Validaciones estrictas contra XSS e Inyección SQL)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'fecha_inicio' => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'hora' => ['nullable', 'date_format:H:i'],
            'lugar' => ['required', 'string', 'max:100'],
            'objetivos' => ['nullable', 'string', 'max:1000'],
            'capacidad_maxima' => ['nullable', 'integer', 'min:0'],
        ]);

        try {
            DB::beginTransaction();

            $validatedData['estado'] = 'pendiente';
            $validatedData['user_creador_id'] = Auth::id();

            Evento::create($validatedData);

            DB::commit();

            return redirect()->route('eventos.index')
                ->with('success', 'Evento propuesto. Esperando aprobación del Pastor.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el evento: ' . $e->getMessage())->withInput();
        }
    }

        // Aprobar Evento (Solo Pastor)
    public function aprobar(Evento $evento)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Verificación de seguridad usando el método real
        if (!$user->tieneAlgunRol(['Pastor Principal', 'Administrador'])) {
             abort(403, 'No autorizado para aprobar eventos.');
        }

        try {
            $evento->update([
                'estado' => 'aprobado',
                'user_aprobador_id' => $user->id
            ]);

            return back()->with('success', 'Evento aprobado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar: ' . $e->getMessage());
        }
    }
}