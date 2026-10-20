<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user() || !$request->user()->tieneAlgunRol($roles)) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}