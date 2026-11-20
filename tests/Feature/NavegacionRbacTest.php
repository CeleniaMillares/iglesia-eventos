<?php

namespace Tests\Feature;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavegacionRbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_muestra_solo_las_opciones_correspondientes_a_cada_rol(): void
    {
        $this->assertSidebar(
            $this->userWithRole('Administrador'),
            [route('dashboard'), route('usuarios.index'), route('roles.index'), route('configuracion.index'), 'Mi Perfil'],
            [route('eventos.index'), route('reportes.index'), route('confirmaciones.index')]
        );

        $this->assertSidebar(
            $this->userWithRole('Pastor Principal'),
            [route('eventos.index'), route('reportes.index'), 'Mi Perfil'],
            [route('usuarios.index'), route('roles.index'), route('configuraciones.index'), route('confirmaciones.index')]
        );

        $this->assertSidebar(
            $this->userWithRole('Secretaría'),
            [
                route('eventos.index'),
                route('miembros.index'),
                route('convocatorias.index'),
                route('eventos.index', ['estado' => 'programados']),
                'Mi Perfil',
            ],
            [route('usuarios.index'), route('roles.index'), route('configuraciones.index'), route('reportes.index'), route('confirmaciones.index')]
        );

        $this->assertSidebar(
            $this->userWithRole('Comité de Eventos'),
            [route('eventos.index'), 'Mi Perfil'],
            [
                route('usuarios.index'),
                route('roles.index'),
                route('configuraciones.index'),
                route('reportes.index'),
                route('miembros.index'),
                route('convocatorias.index'),
                route('confirmaciones.index'),
            ]
        );

        $this->assertSidebar(
            $this->userWithRole('Miembro'),
            [route('eventos.index'), route('confirmaciones.index'), 'Mis Confirmaciones', 'Mi Perfil'],
            [
                route('usuarios.index'),
                route('roles.index'),
                route('configuraciones.index'),
                route('reportes.index'),
                route('miembros.index'),
                route('convocatorias.index'),
            ]
        );
    }

    public function test_administrador_no_puede_acceder_a_la_creacion_de_eventos(): void
    {
        $admin = $this->userWithRole('Administrador');

        $this->actingAs($admin)
            ->get(route('eventos.create'))
            ->assertForbidden();

        $this->post(route('eventos.store'), [
            'nombre' => 'Evento de prueba',
            'fecha_inicio' => now()->addDay()->toDateString(),
            'lugar' => 'Templo',
            'objetivos' => 'Objetivos de prueba',
        ])->assertForbidden();
    }

    private function assertSidebar(User $user, array $expected, array $unexpected): void
    {
        $this->actingAs($user);
        $sidebar = view('layouts.partials.sidebar-menu')->render();

        foreach ($expected as $value) {
            $this->assertStringContainsString($value, $sidebar);
        }

        foreach ($unexpected as $value) {
            $this->assertStringNotContainsString($value, $sidebar);
        }
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        if ($roleName === 'Miembro') {
            Miembro::factory()->create(['user_id' => $user->id]);
        }

        return $user;
    }
}
