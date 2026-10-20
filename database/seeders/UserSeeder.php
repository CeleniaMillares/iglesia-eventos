<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'email' => 'admin@iglesia.com',
                'nombre' => 'Administrador Sistema', // 👈 Cambiado
                'estado' => 'activo',
                'rol' => 'Administrador'
            ],
            [
                'email' => 'pastor@iglesia.com',
                'nombre' => 'Pastor Principal', // 👈 Cambiado
                'estado' => 'activo',
                'rol' => 'Pastor Principal'
            ],
            [
                'email' => 'secretaria@iglesia.com',
                'nombre' => 'Secretaria Iglesia', // 👈 Cambiado
                'estado' => 'activo',
                'rol' => 'Secretaría'
            ],
            [
                'email' => 'comite@iglesia.com',
                'nombre' => 'Comité Eventos', // 👈 Cambiado
                'estado' => 'activo',
                'rol' => 'Comité de Eventos'
            ],
            [
                'email' => 'miembro@iglesia.com',
                'nombre' => 'Luz Choipa', // 👈 Cambiado
                'estado' => 'activo',
                'rol' => 'Miembro'
            ]
        ];

        foreach ($usuarios as $data) {
            // Buscamos por email y actualizamos/creamos con 'nombre'
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'nombre' => $data['nombre'], // 👈 Cambiado
                    'password' => Hash::make('password'),
                    'estado' => $data['estado'],
                ]
            );

            // Asignar el rol mediante la tabla pivote
            $rol = Role::where('nombre', $data['rol'])->first();
            if ($rol) {
                $user->roles()->syncWithoutDetaching([$rol->id]);
            }
        }
    }
}