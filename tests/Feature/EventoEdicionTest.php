<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventoEdicionTest extends TestCase
{
    use RefreshDatabase;

    public function test_committee_can_edit_and_update_a_pending_event(): void
    {
        $user = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($user);

        $this->actingAs($user)
            ->get(route('eventos.edit', $evento))
            ->assertOk()
            ->assertSee('Editar Evento');

        $this->put(route('eventos.update', $evento), $this->validEventData())
            ->assertRedirect(route('eventos.index'))
            ->assertSessionHas('success', 'Evento actualizado exitosamente.');

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Evento actualizado',
            'estado' => 'pendiente',
        ]);
    }

    public function test_secretaria_can_edit_a_pending_event(): void
    {
        $user = $this->userWithRole('Secretaría');
        $evento = $this->createEvent($user);

        $this->actingAs($user)
            ->get(route('eventos.edit', $evento))
            ->assertOk();
    }

    public function test_administrator_cannot_edit_a_pending_event(): void
    {
        $user = $this->userWithRole('Administrador');
        $evento = $this->createEvent($user);

        $this->actingAs($user)
            ->get(route('eventos.edit', $evento))
            ->assertForbidden();

        $this->put(route('eventos.update', $evento), $this->validEventData())
            ->assertForbidden();
    }

    public function test_pending_event_cannot_be_edited_after_approval(): void
    {
        $user = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($user, 'aprobado');

        $this->actingAs($user)
            ->get(route('eventos.edit', $evento))
            ->assertForbidden();

        $this->put(route('eventos.update', $evento), $this->validEventData())
            ->assertForbidden();

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Evento original',
            'estado' => 'aprobado',
        ]);
    }

    public function test_edit_link_is_shown_only_to_authorized_roles_for_pending_events(): void
    {
        $committee = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($committee);

        $this->actingAs($committee)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertSee(route('eventos.edit', $evento));

        $administrator = $this->userWithRole('Administrador');
        $this->actingAs($administrator)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertDontSee(route('eventos.edit', $evento));
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::create(['nombre' => $roleName]);
        $user = User::create([
            'nombre' => $roleName,
            'email' => Str::uuid().'@example.test',
            'password' => 'password',
        ]);
        $user->roles()->attach($role);

        return $user;
    }

    private function createEvent(User $creator, string $state = 'pendiente'): Evento
    {
        return Evento::create([
            'nombre' => 'Evento original',
            'fecha_inicio' => now()->addDays(2)->toDateString(),
            'lugar' => 'Camiri',
            'objetivos' => 'Objetivos originales',
            'user_creador_id' => $creator->id,
            'estado' => $state,
        ]);
    }

    private function validEventData(): array
    {
        return [
            'nombre' => 'Evento actualizado',
            'fecha_inicio' => now()->addDays(3)->toDateString(),
            'lugar' => 'Nuevo lugar',
            'objetivos' => 'Objetivos actualizados',
            'capacidad_maxima' => 100,
        ];
    }
}
