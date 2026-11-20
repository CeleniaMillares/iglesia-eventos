<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_administrator_role_cannot_access_role_management(): void
    {
        $user = $this->userWithRole('Secretaría');

        $this->actingAs($user)->get(route('roles.index'))->assertForbidden();
    }

    public function test_administrator_can_create_update_and_delete_a_role(): void
    {
        $admin = $this->userWithRole('Administrador');

        $this->actingAs($admin)
            ->post(route('roles.store'), [
                'nombre' => 'Coordinación',
                'descripcion' => 'Coordina equipos de trabajo.',
            ])
            ->assertRedirect(route('roles.index'));

        $role = Role::where('nombre', 'Coordinación')->firstOrFail();

        $this->put(route('roles.update', $role), [
            'nombre' => 'Coordinación General',
            'descripcion' => 'Coordina los equipos.',
        ])->assertRedirect(route('roles.index'));

        $role->refresh();
        $this->assertSame('Coordinación General', $role->nombre);

        $this->delete(route('roles.destroy', $role))->assertRedirect(route('roles.index'));
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_base_role_cannot_be_renamed(): void
    {
        $admin = $this->userWithRole('Administrador');
        $baseRole = Role::firstOrCreate(['nombre' => 'Pastor Principal']);

        $this->actingAs($admin)
            ->put(route('roles.update', $baseRole), [
                'nombre' => 'Pastor General',
                'descripcion' => 'Intento de renombrado.',
            ])
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('error', 'No se puede renombrar un rol base del sistema.');

        $this->assertDatabaseHas('roles', [
            'id' => $baseRole->id,
            'nombre' => 'Pastor Principal',
        ]);
    }

    public function test_base_role_description_can_be_updated(): void
    {
        $admin = $this->userWithRole('Administrador');
        $baseRole = Role::firstOrCreate(['nombre' => 'Pastor Principal']);

        $this->actingAs($admin)
            ->put(route('roles.update', $baseRole), [
                'nombre' => 'Pastor Principal',
                'descripcion' => 'Descripción actualizada.',
            ])
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('roles', [
            'id' => $baseRole->id,
            'nombre' => 'Pastor Principal',
            'descripcion' => 'Descripción actualizada.',
        ]);
    }

    public function test_base_role_with_assigned_users_cannot_be_deleted(): void
    {
        $admin = $this->userWithRole('Administrador');
        $member = $this->userWithRole('Miembro');
        $baseRole = Role::where('nombre', 'Miembro')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('roles.destroy', $baseRole))
            ->assertRedirect(route('roles.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('roles', ['id' => $baseRole->id, 'nombre' => 'Miembro']);
        $this->assertTrue($member->fresh()->tieneRol('Miembro'));
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }
}
