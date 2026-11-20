<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    private const CAMPOS = [
        'nombre_iglesia' => 'Nombre oficial de la iglesia',
        'correo_contacto' => 'Correo de contacto de la iglesia',
        'limite_eventos_por_pagina' => 'Límite de eventos por página',
    ];

    private const VALORES_POR_DEFECTO = [
        'nombre_iglesia' => 'Iglesia Asamblea de Dios Boliviana',
        'correo_contacto' => 'contacto@iglesia.com',
        'limite_eventos_por_pagina' => '15',
    ];

    public function index(): View
    {
        abort_unless(Auth::user()?->tieneRol('Administrador'), 403);

        $configuraciones = Configuracion::query()
            ->whereIn('clave', array_keys(self::CAMPOS))
            ->get()
            ->keyBy('clave');

        $valores = collect(self::VALORES_POR_DEFECTO)
            ->map(fn (string $porDefecto, string $clave): string => (string) ($configuraciones->get($clave)?->valor ?? $porDefecto));

        return view('configuraciones.index', compact('configuraciones', 'valores'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()?->tieneRol('Administrador'), 403);

        $validated = $request->validate([
            'nombre_iglesia' => ['required', 'string', 'max:100'],
            'correo_contacto' => ['required', 'email', 'max:255'],
            'limite_eventos_por_pagina' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        foreach ($validated as $clave => $valor) {
            Configuracion::updateOrCreate(
                ['clave' => $clave],
                [
                    'valor' => (string) $valor,
                    'descripcion' => self::CAMPOS[$clave],
                ]
            );
        }

        return redirect()->route('configuraciones.index')
            ->with('success', 'Configuración actualizada exitosamente.');
    }
}
