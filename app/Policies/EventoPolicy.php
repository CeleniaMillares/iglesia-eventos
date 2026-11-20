<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    public function update(User $user, Evento $evento): bool
    {
        if ($evento->estado === Evento::ESTADO_ARCHIVADO || $evento->estado !== 'pendiente') {
            return false;
        }

        if ($user->tieneRol('Administrador')) {
            return true;
        }

        return $user->id === $evento->user_creador_id
            && $user->tieneAlgunRol(['Comité de Eventos', 'Secretaría']);
    }

    public function confirmar(User $user, Evento $evento): bool
    {
        if ($evento->estado === Evento::ESTADO_ARCHIVADO) {
            return false;
        }

        return $user->estado === 'activo'
            && $user->miembro?->estado_miembro === 'activo'
            && in_array($evento->estado, Evento::ESTADOS_CONFIRMABLES, true);
    }
}
