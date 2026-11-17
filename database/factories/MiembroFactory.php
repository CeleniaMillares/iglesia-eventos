<?php

namespace Database\Factories;

use App\Models\Miembro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Miembro>
 */
class MiembroFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'telefono' => fake()->phoneNumber(),
            'direccion' => fake()->address(),
            'estado_miembro' => 'activo',
            'tipo_sangre' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'numero_referencia' => fake()->unique()->numerify('########'),
            'contacto_emergencia' => fake()->name(),
            'telefono_emergencia' => fake()->phoneNumber(),
            'estado_civil' => fake()->randomElement(['soltero', 'casado', 'divorciado', 'viudo']),
            'conyuge' => null,
            'hijos' => 0,
            'profesion' => fake()->jobTitle(),
        ];
    }
}
