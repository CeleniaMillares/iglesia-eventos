<?php

namespace Database\Seeders;

use App\Models\ConfirmacionAsistencia;
use App\Models\Evento;
use App\Models\Miembro;
use Illuminate\Database\Seeder;
use RuntimeException;

class ConfirmacionSeeder extends Seeder
{
    public function run(): void
    {
        $miembros = Miembro::query()
            ->where('estado_miembro', 'activo')
            ->where('numero_referencia', 'like', MiembroSeeder::REFERENCIA_PREFIX.'%')
            ->get();

        if ($miembros->isEmpty()) {
            throw new RuntimeException('No hay miembros generados por MiembroSeeder.');
        }

        $porcentajes = [
            'Vigilia de Oración' => 0.85,
            'Concierto de Música Cristiana' => 0.35,
            'Conferencia de Familia' => 0.60,
        ];

        foreach ($porcentajes as $nombre => $porcentaje) {
            $evento = Evento::where('nombre', $nombre)->firstOrFail();

            if (! in_array($evento->estado, Evento::ESTADOS_CONFIRMABLES, true)) {
                continue;
            }

            $capacidad = $evento->capacidad_maxima > 0
                ? $evento->capacidad_maxima
                : $miembros->count();

            $objetivo = (int) ceil($miembros->count() * $porcentaje);
            $cantidad = min($objetivo, $capacidad, $miembros->count());

            foreach ($miembros->shuffle()->take($cantidad) as $miembro) {
                ConfirmacionAsistencia::updateOrCreate(
                    [
                        'evento_id' => $evento->id,
                        'miembro_id' => $miembro->id,
                    ],
                    [
                        'estado' => 'confirmada',
                        'fecha_confirmacion' => now()
                            ->subDays(fake()->numberBetween(1, 5))
                            ->setTime(fake()->numberBetween(8, 20), fake()->numberBetween(0, 59)),
                    ]
                );
            }
        }
    }
}
