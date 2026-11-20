<?php

namespace App\Services;

use App\Models\Convocatoria;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\User;
use App\Mail\ConvocatoriaMail;
use App\Mail\EventoAprobadoRechazadoMail;
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

    public function notificarDecisionComite(Evento $evento): int
    {
        $comite = User::query()
            ->whereHas('roles', function ($query): void {
                $query->where('nombre', 'Comité de Eventos');
            })
            ->whereNotNull('email')
            ->get();

        $enviados = 0;

        foreach ($comite as $usuario) {
            try {
                Mail::to($usuario->email)->send(new EventoAprobadoRechazadoMail(
                    $evento,
                    $evento->estado === 'aprobado',
                    $evento->motivo_rechazo
                ));
                $enviados++;
            } catch (\Exception $e) {
                Log::error("Error enviando decisión del evento a {$usuario->email}: ".$e->getMessage());
            }
        }

        return $enviados;
    }
}