<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $miembroRole = Role::create(['nombre' => 'Miembro']);

        $response = $this->post('/register', [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'estado' => 'pendiente',
        ]);
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertDatabaseHas('miembros', [
            'user_id' => $user->id,
            'estado_miembro' => 'pendiente',
        ]);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $miembroRole->id,
        ]);
    }

    public function test_registration_creates_the_member_role_if_it_has_not_been_seeded(): void
    {
        $this->post('/register', [
            'nombre' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('roles', ['nombre' => 'Miembro']);
        $this->assertDatabaseHas('miembros', ['estado_miembro' => 'pendiente']);
    }
}
