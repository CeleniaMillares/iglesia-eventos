<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function acta(Evento $evento)
    {
        // Genera el PDF con los datos del evento
        $pdf = Pdf::loadView('reportes.acta', compact('evento'));
        
        // Descarga el PDF con el nombre del evento
        return $pdf->download('acta-' . str_replace(' ', '-', $evento->nombre) . '.pdf');
    }
        public function index()
    {
        // Solo mostramos eventos que ya terminaron para descargar sus actas
        $eventos = Evento::where('estado', 'finalizado')->orderBy('fecha_inicio', 'desc')->paginate(10);
        return view('reportes.index', compact('eventos'));
    }
}