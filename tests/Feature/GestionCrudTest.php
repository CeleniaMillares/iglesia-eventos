<?php

namespace Tests\Feature;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GestionCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaria_gestiona_el_crud_de_miembros_y_admin_tambien_puede_acceder(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $admin = $this->userWithRole('Administrador');
        $member = $this->userWithRole('Miembro');
        $perfil = Miembro::factory()->create(['user_id' => $member->id]);

        $this->actingAs($secretaria)
            ->get(route('miembros.show', $member))
            ->assertOk()
            ->assertSee($member->nombre)
            ->assertSee($perfil->numero_referencia);

        $this->actingAs($secretaria)
            ->get(route('miembros.edit', $member))
            ->assertOk()
            ->assertSee('Editar miembro');

        $this->put(route('miembros.update', $member), [
            'nombre' => 'Miembro Actualizado',
            'email' => 'miembro-actualizado@example.test',
            'telefono' => '77778888',
            'numero_referencia' => $perfil->numero_referencia,
        ])->assertRedirect(route('miembros.index'));

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'nombre' => 'Miembro Actualizado',
            'email' => 'miembro-actualizado@example.test',
        ]);

        $this->patch(route('miembros.estado', $member))->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $member->id, 'estado' => 'inactivo']);
        $this->assertDatabaseHas('miembros', ['id' => $perfil->id, 'estado_miembro' => 'inactivo']);

        $this->actingAs($member->fresh())
            ->get(route('dashboard'))
            ->assertRedirect(route('auth.pending'));

        $this->actingAs($admin)->get(route('miembros.index'))->assertOk();

        $otroMiembro = $this->userWithRole('Miembro');
        Miembro::factory()->create(['user_id' => $otroMiembro->id]);

        $this->actingAs($secretaria)
            ->delete(route('miembros.destroy', $otroMiembro))
            ->assertRedirect(route('miembros.index'));

        $this->assertDatabaseMissing('users', ['id' => $otroMiembro->id]);
        $this->assertDatabaseMissing('miembros', ['user_id' => $otroMiembro->id]);
    }

    public function test_administrador_gestiona_usuarios_y_protege_al_administrador_principal(): void
    {
        $admin = $this->userWithRole('Administrador');
        $principal = $this->userWithRole('Administrador');
        $principal->update(['email' => 'admin@iglesia.com']);
        $objetivo = $this->userWithRole('Miembro');
        $secretariaRole = Role::firstOrCreate(['nombre' => 'Secretaría']);

        $this->actingAs($admin)
            ->get(route('usuarios.show', $objetivo))
            ->assertOk()
            ->assertSee($objetivo->nombre)
            ->assertSee($objetivo->email)
            ->assertSee('Miembro');

        $this->get(route('usuarios.edit', $objetivo))
            ->assertOk()
            ->assertSee('Roles asignados');

        $this->put(route('usuarios.update', $objetivo), [
            'nombre' => 'Usuario Editado',
            'email' => 'usuario-editado@example.test',
            'roles' => [$secretariaRole->id],
        ])->assertRedirect(route('usuarios.index'));

        $this->assertDatabaseHas('users', [
            'id' => $objetivo->id,
            'nombre' => 'Usuario Editado',
            'email' => 'usuario-editado@example.test',
        ]);
        $this->assertTrue($objetivo->fresh()->tieneRol('Secretaría'));

        $this->patch(route('usuarios.estado', $objetivo))->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $objetivo->id, 'estado' => 'inactivo']);

        $this->actingAs($objetivo->fresh())
            ->get(route('dashboard'))
            ->assertRedirect(route('auth.pending'));

        $this->actingAs($admin)
            ->delete(route('usuarios.destroy', $principal))
            ->assertForbidden();

        $this->delete(route('usuarios.destroy', $admin))->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $principal->id]);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_administrador_ve_detalle_de_roles_y_no_puede_eliminar_roles_con_usuarios(): void
    {
        $admin = $this->userWithRole('Administrador');
        $usuario = $this->userWithRole('Miembro');
        $rol = Role::firstOrCreate(['nombre' => 'Coordinación']);
        $usuario->roles()->attach($rol);

        $this->actingAs($admin)
            ->get(route('roles.index'))
            ->assertOk()
            ->assertSee('Crear Nuevo Rol')
            ->assertSee('Ver');

        $this->get(route('roles.show', $rol))
            ->assertOk()
            ->assertSee('Coordinación')
            ->assertSee($usuario->nombre);

        $this->delete(route('roles.destroy', $rol))
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('roles', ['id' => $rol->id]);

        $usuario->roles()->detach($rol);

        $this->delete(route('roles.destroy', $rol))
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('roles', ['id' => $rol->id]);
    }

    public function test_secretaria_no_puede_administrar_usuarios_ni_roles(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $usuario = $this->userWithRole('Miembro');

        $this->actingAs($secretaria)
            ->get(route('usuarios.index'))
            ->assertForbidden();

        $this->get(route('roles.index'))->assertForbidden();
        $this->get(route('usuarios.show', $usuario))->assertForbidden();
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }
}
