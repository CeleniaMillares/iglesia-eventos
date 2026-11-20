<?php

namespace Database\Seeders;

use App\Models\Convocatoria;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class ConvocatoriaSeeder extends Seeder
{
    public function run(): void
    {
        $secretaria = User::whereHas('roles', fn ($query) => $query->where('nombre', 'Secretaría'))->first()
            ?? User::where('email', 'secretaria@gmail.com')->first();

        if (! $secretaria instanceof User) {
            throw new RuntimeException('No se encontró el usuario Secretaría (secretaria@gmail.com).');
        }

        foreach (Evento::whereIn('estado', Evento::ESTADOS_CONFIRMABLES)->get() as $evento) {
            Convocatoria::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'user_id' => $secretaria->id,
                ],
                [
                    'asunto' => 'Convocatoria: '.$evento->nombre,
                    'mensaje' => 'Estimada congregación: los invitamos a participar de '.$evento->nombre.', que se realizará el '.$evento->fecha_inicio->format('d/m/Y').' a las '.$evento->hora->format('H:i').' en '.$evento->lugar.'.',
                    'fecha_envio' => now()->subDays(fake()->numberBetween(1, 3))->setTime(fake()->numberBetween(8, 17), fake()->numberBetween(0, 59)),
                ]
            );
        }
    }
}
