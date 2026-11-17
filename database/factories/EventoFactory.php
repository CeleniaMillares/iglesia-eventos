<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evento>
 */
class EventoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->optional()->paragraph(),
            'fecha_inicio' => now()->addDays(1)->toDateString(),
            'fecha_fin' => null,
            'hora' => fake()->time('H:i'),
            'duracion_dias' => 1,
            'tipo_evento' => fake()->word(),
            'lugar' => fake()->streetAddress(),
            'objetivos' => fake()->sentence(),
            'estado' => 'pendiente',
            'capacidad_maxima' => 0,
            'user_creador_id' => User::factory(),
            'responsable_principal_id' => null,
        ];
    }
}
