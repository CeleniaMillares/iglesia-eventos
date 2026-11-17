<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventoAprobacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_pastor_principal_can_approve_an_event(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $evento = $this->createPendingEvent($pastor);

        $this->actingAs($pastor)
            ->post(route('eventos.aprobar', $evento))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'aprobado',
            'user_aprobador_id' => $pastor->id,
        ]);
    }

    public function test_pastor_principal_can_reject_an_event(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $evento = $this->createPendingEvent($pastor);

        $this->actingAs($pastor)
            ->post(route('eventos.rechazar', $evento), ['motivo_rechazo' => 'No procede'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'rechazado',
            'motivo_rechazo' => 'No procede',
            'user_aprobador_id' => $pastor->id,
        ]);
    }

    public function test_administrator_cannot_approve_or_reject_an_event(): void
    {
        $administrator = $this->userWithRole('Administrador');
        $evento = $this->createPendingEvent($administrator);
        $payload = ['motivo_rechazo' => 'No procede'];

        $this->actingAs($administrator)
            ->post(route('eventos.aprobar', $evento))
            ->assertForbidden();

        $this->post(route('eventos.rechazar', $evento), $payload)
            ->assertForbidden();

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'pendiente',
            'user_aprobador_id' => null,
        ]);
    }

    public function test_administrator_does_not_see_approval_actions(): void
    {
        $administrator = $this->userWithRole('Administrador');
        $evento = $this->createPendingEvent($administrator);

        $this->actingAs($administrator)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertDontSee(route('eventos.aprobar', $evento))
            ->assertDontSee(route('eventos.rechazar', $evento));
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::create(['nombre' => $roleName]);
        $suffix = (string) Str::uuid();
        $user = User::create([
            'nombre' => $roleName,
            'email' => $suffix.'@example.test',
            'password' => 'password',
        ]);
        $user->roles()->attach($role);

        return $user;
    }

    private function createPendingEvent(User $creator): Evento
    {
        return Evento::create([
            'nombre' => 'Evento pendiente',
            'fecha_inicio' => now()->toDateString(),
            'lugar' => 'Camiri',
            'objetivos' => 'Objetivos del evento',
            'user_creador_id' => $creator->id,
            'estado' => 'pendiente',
        ]);
    }
}
