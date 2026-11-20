<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    public static function valor(string $clave, ?string $porDefecto = null): ?string
    {
        try {
            return static::query()->where('clave', $clave)->value('valor') ?? $porDefecto;
        } catch (\Throwable) {
            return $porDefecto;
        }
    }

    public static function limiteEventosPorPagina(): int
    {
        $limite = (int) static::valor('limite_eventos_por_pagina', '15');

        return $limite > 0 && $limite <= 100 ? $limite : 15;
    }
}
