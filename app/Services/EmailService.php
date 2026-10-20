<?php

namespace App\Services;

use App\Models\Convocatoria;
use App\Models\Miembro;
use App\Mail\ConvocatoriaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function enviarConvocatoriaMasiva(Convocatoria $convocatoria)
    {
        // Buscamos a todos los miembros activos
        $miembros = Miembro::where('estado_miembro', 'activo')->with('user')->get();
        
        $enviados = 0;

        foreach ($miembros as $miembro) {
            if ($miembro->user && $miembro->user->email) {
                try {
                    Mail::to($miembro->user->email)->send(new ConvocatoriaMail($convocatoria));
                    $enviados++;
                } catch (\Exception $e) {
                    Log::error("Error enviando correo a {$miembro->user->email}: " . $e->getMessage());
                }
            }
        }

        return $enviados;
    }
}