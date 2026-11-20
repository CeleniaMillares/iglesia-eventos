<?php

namespace Database\Seeders;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class MiembroSeeder extends Seeder
{
    public const TOTAL = 20;

    public const REFERENCIA_PREFIX = 'MEM-';

    public function run(): void
    {
        $rol = Role::where('nombre', 'Miembro')->firstOrFail();

        for ($i = 0; $i < self::TOTAL; $i++) {
            $user = User::factory()->create([
                'estado' => 'activo',
            ]);

            $user->roles()->attach($rol);

            Miembro::create([
                'user_id' => $user->id,
                'telefono' => fake()->phoneNumber(),
                'direccion' => fake()->address(),
                'fecha_nacimiento' => fake()->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
                'estado_miembro' => 'activo',
                'tipo_sangre' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                'numero_referencia' => fake()->unique()->numerify(self::REFERENCIA_PREFIX.'######'),
                'foto' => 'miembros/'.fake()->uuid().'.jpg',
                'contacto_emergencia' => fake()->name(),
                'telefono_emergencia' => fake()->phoneNumber(),
                'estado_civil' => fake()->randomElement(['soltero', 'casado', 'divorciado', 'viudo']),
                'conyuge' => fake()->optional()->name(),
                'hijos' => fake()->numberBetween(0, 5),
                'profesion' => fake()->jobTitle(),
            ]);
        }
    }
}
