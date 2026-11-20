<?php

namespace Tests\Feature;

use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiembroActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_secretaria_activates_user_and_member_profile_together(): void
    {
        $secretariaRole = Role::create(['nombre' => 'Secretaría']);
        $secretaria = User::create([
            'nombre' => 'Secretaría',
            'email' => 'secretaria@example.test',
            'password' => 'password',
            'estado' => 'activo',
        ]);
        $secretaria->roles()->attach($secretariaRole);

        $memberRole = Role::create(['nombre' => 'Miembro']);
        $user = User::create([
            'nombre' => 'Miembro pendiente',
            'email' => 'miembro-pendiente@example.test',
            'password' => 'password',
            'estado' => 'pendiente',
        ]);
        $user->roles()->attach($memberRole);
        $miembro = Miembro::create([
            'user_id' => $user->id,
            'estado_miembro' => 'pendiente',
        ]);

        $this->actingAs($secretaria)
            ->get(route('miembros.index'))
            ->assertOk()
            ->assertSee('Activar');

        $this->actingAs($secretaria)
            ->post(route('miembros.verificar', $user))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'estado' => 'activo',
        ]);
        $this->assertDatabaseHas('miembros', [
            'id' => $miembro->id,
            'estado_miembro' => 'activo',
        ]);

        $this->actingAs($user->fresh())
            ->get(route('eventos.index'))
            ->assertOk();
    }
}
