<?php

namespace App\Http\Controllers;

use App\Models\ActaEvento;
use App\Models\Evento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function acta(Evento $evento)
    {
        $nombreArchivo = 'acta-'.str_replace(' ', '-', $evento->nombre).'.pdf';

        ActaEvento::create([
            'evento_id' => $evento->id,
            'user_id' => Auth::id(),
            'archivo_pdf' => $nombreArchivo,
            'archivo' => $nombreArchivo,
            'fecha_generacion' => now(),
        ]);

        $pdf = Pdf::loadView('reportes.acta', compact('evento'));

        return $pdf->download($nombreArchivo);
    }

    public function historial(Evento $evento): View
    {
        $evento->load('actas.generadoPor');

        return view('reportes.historial-actas', compact('evento'));
    }

    public function index(): View
    {
        $eventos = Evento::where('estado', 'finalizado')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('reportes.index', compact('eventos'));
    }
}
