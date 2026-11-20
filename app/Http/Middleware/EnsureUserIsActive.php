<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->estado !== 'activo') {
            return redirect()->route('auth.pending');
        }

        if (
            $user
            && $user->tieneRol('Miembro')
            && $user->miembro?->estado_miembro !== 'activo'
        ) {
            return redirect()->route('auth.pending');
        }

        return $next($request);
    }
}
