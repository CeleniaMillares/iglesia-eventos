<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Convocatoria;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConvocatoriaController extends Controller
{
    public function create(Evento $evento)
    {
        // Validación estricta según RN-03 y tu documento original
        if ($evento->estado !== 'aprobado') {
            return redirect()->route('eventos.index')
                ->with('error', 'No se puede enviar convocatoria. El evento está en estado: ' . $evento->estado . '. Solo se permite en estado Aprobado.');
        }
        return view('convocatorias.create', compact('evento'));
    }

    public function store(Request $request, Evento $evento, EmailService $emailService)
    {
        // Validación de seguridad extra
        if ($evento->estado !== 'aprobado') {
            return redirect()->route('eventos.index')->with('error', 'El evento no está aprobado.');
        }

        $request->validate([
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $convocatoria = Convocatoria::create([
            'evento_id' => $evento->id,
            'user_id' => Auth::id(),
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'fecha_envio' => now(),
        ]);

        $cantidad = $emailService->enviarConvocatoriaMasiva($convocatoria);

        return redirect()->route('eventos.index')
            ->with('success', "Convocatoria procesada. Se enviaron {$cantidad} correos a los miembros activos.");
    }

        public function index()
    {
        $convocatorias = \App\Models\Convocatoria::with(['evento', 'user'])
            ->orderBy('fecha_envio', 'desc')
            ->paginate(10);
            
        return view('convocatorias.index', compact('convocatorias'));
    }
}
