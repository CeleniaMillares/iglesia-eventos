<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    public function confirmar(User $user, Evento $evento): bool
    {
        return $user->estado === 'activo'
            && $user->miembro?->estado_miembro === 'activo'
            && in_array($evento->estado, Evento::ESTADOS_CONFIRMABLES, true);
    }
}
