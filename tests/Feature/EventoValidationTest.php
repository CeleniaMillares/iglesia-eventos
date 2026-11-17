<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventoValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_creation_rejects_past_dates_and_renders_field_errors(): void
    {
        $role = Role::create(['nombre' => 'Comité de Eventos']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->actingAs($user)
            ->from(route('eventos.create'))
            ->post(route('eventos.store'), [
                'nombre' => '',
                'fecha_inicio' => now()->subDay()->toDateString(),
                'lugar' => 'Templo',
                'objetivos' => 'Propósito de prueba',
            ])
            ->assertRedirect(route('eventos.create'))
            ->assertSessionHasErrors(['nombre', 'fecha_inicio']);

        $this->get(route('eventos.create'))
            ->assertOk()
            ->assertSee('The nombre field is required.')
            ->assertSee('after or equal to today');
    }
}
