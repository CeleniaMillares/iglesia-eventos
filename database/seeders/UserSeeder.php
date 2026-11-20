<?php

namespace Database\Seeders;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LogicException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            throw new LogicException('Development users cannot be seeded in production.');
        }

        $usuarios = [
            [
                'email' => 'admin@gmail.com',
                'nombre' => 'Administrador Sistema',
                'estado' => 'activo',
                'rol' => 'Administrador',
            ],
            [
                'email' => 'pastor@gmail.com',
                'nombre' => 'Pastor Principal',
                'estado' => 'activo',
                'rol' => 'Pastor Principal',
            ],
            [
                'email' => 'secretaria@gmail.com',
                'nombre' => 'Secretaria Iglesia',
                'estado' => 'activo',
                'rol' => 'Secretaría',
            ],
            [
                'email' => 'comite@gmail.com',
                'nombre' => 'Comité Eventos',
                'estado' => 'activo',
                'rol' => 'Comité de Eventos',
            ],
            [
                'email' => 'celenia@gmail.com',
                'nombre' => 'Celenia heredia',
                'estado' => 'activo',
                'rol' => 'Miembro',
            ],
        ];

        foreach ($usuarios as $data) {
            // Buscamos por email y actualizamos/creamos con 'nombre'
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nombre' => $data['nombre'],
                    'password' => Hash::make('password'),
                    'estado' => $data['estado'],
                ]
            );

            // Asignar el rol mediante la tabla pivote
            $rol = Role::where('nombre', $data['rol'])->first();
            if ($rol) {
                $user->roles()->syncWithoutDetaching([$rol->id]);
            }

            // Si el rol es Miembro, creamos su perfil en la tabla miembros
            if ($data['rol'] === 'Miembro') {
                Miembro::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'telefono' => '70000000',
                        'direccion' => 'Camiri',
                        'estado_miembro' => 'activo',
                    ]
                );
            }
        }
    }
}
