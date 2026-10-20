<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo al sistema con todos los privilegios'],
            ['nombre' => 'Pastor Principal', 'descripcion' => 'Líder máximo de la congregación'],
            ['nombre' => 'Secretaría', 'descripcion' => 'Administra miembros y documentación'],
            ['nombre' => 'Comité de Eventos', 'descripcion' => 'Encargado de planificar eventos'],
            ['nombre' => 'Miembro', 'descripcion' => 'Miembro activo de la congregación'],
        ];

        foreach ($roles as $rol) {
            Role::updateOrCreate(
                ['nombre' => $rol['nombre']],
                $rol
            );
        }
    }
}