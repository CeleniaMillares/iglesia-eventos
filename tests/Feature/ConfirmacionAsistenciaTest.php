<?php

namespace Tests\Feature;

use App\Models\ConfirmacionAsistencia;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class ConfirmacionAsistenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_list_renders_attendance_confirmation_form(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();

        $this->actingAs($user)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertSee(route('confirmaciones.store'))
            ->assertSee('name="evento_id"', false)
            ->assertSee('value="'.$evento->id.'"', false)
            ->assertSee('Confirmar');
    }

    public function test_member_can_confirm_attendance(): void
    {
        [$user, $miembro, $evento] = $this->createMemberAndEvent();

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect()
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->assertDatabaseHas('confirmaciones_asistencia', [
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
            'estado' => 'confirmada',
        ]);
    }

    public function test_member_can_confirm_a_planned_event(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();
        $evento->update(['estado' => 'planificado']);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect()
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->assertDatabaseCount('confirmaciones_asistencia', 1);
    }

    public function test_miembro_puede_cancelar_asistencia(): void
    {
        [$user, $miembro, $evento] = $this->createMemberAndEvent();
        $evento->update(['capacidad_maxima' => 1]);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->assertFalse($evento->fresh()->tieneCupo());

        $this->delete(route('confirmaciones.destroy', $evento))
            ->assertRedirect()
            ->assertSessionHas('success', 'Asistencia cancelada correctamente');

        $this->assertDatabaseHas('confirmaciones_asistencia', [
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
            'estado' => 'cancelada',
        ]);
        $this->assertTrue($evento->fresh()->tieneCupo());
    }

    public function test_cancelled_member_can_confirm_attendance_again(): void
    {
        [$user, $miembro, $evento] = $this->createMemberAndEvent();

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->delete(route('confirmaciones.destroy', $evento))
            ->assertSessionHas('success', 'Asistencia cancelada correctamente');

        $this->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->assertDatabaseHas('confirmaciones_asistencia', [
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
            'estado' => 'confirmada',
        ]);
        $this->assertDatabaseCount('confirmaciones_asistencia', 1);
    }

    public function test_cancel_button_is_shown_in_mis_confirmaciones(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertSessionHas('success');

        $this->get(route('confirmaciones.index'))
            ->assertOk()
            ->assertSee(route('confirmaciones.destroy', $evento))
            ->assertSee('Cancelar Asistencia')
            ->assertDontSee('Confirmar Asistencia');

        $this->get(route('eventos.index'))
            ->assertOk()
            ->assertDontSee($evento->nombre)
            ->assertDontSee(route('confirmaciones.destroy', $evento));
    }

    public function test_member_sees_available_events_and_confirmations_in_separate_views(): void
    {
        [$user, , $confirmedEvent] = $this->createMemberAndEvent();
        $availableEvent = $this->createEvent($user);
        $availableEvent->update(['estado' => 'planificado']);
        $pendingEvent = $this->createEvent($user);
        $pendingEvent->update(['estado' => 'pendiente']);
        $rejectedEvent = $this->createEvent($user);
        $rejectedEvent->update(['estado' => 'rechazado']);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $confirmedEvent->id])
            ->assertSessionHas('success');

        $this->get(route('eventos.index'))
            ->assertOk()
            ->assertSee('Eventos Disponibles')
            ->assertSee($availableEvent->nombre)
            ->assertDontSee($confirmedEvent->nombre)
            ->assertDontSee($pendingEvent->nombre)
            ->assertDontSee($rejectedEvent->nombre)
            ->assertDontSee('Aprobado')
            ->assertDontSee('Pendiente')
            ->assertDontSee('Rechazado');

        $this->get(route('confirmaciones.index'))
            ->assertOk()
            ->assertSee('Mis Confirmaciones')
            ->assertSee($confirmedEvent->nombre)
            ->assertDontSee($availableEvent->nombre)
            ->assertDontSee($pendingEvent->nombre)
            ->assertDontSee($rejectedEvent->nombre)
            ->assertDontSee('Confirmar Asistencia');
    }

    public function test_miembro_no_ve_la_confirmacion_de_otro_miembro(): void
    {
        [$userA, $miembroA, $eventoA] = $this->createMemberAndEvent();
        [$userB, $miembroB] = $this->createMember();
        $eventoB = $this->createEvent($userB);

        ConfirmacionAsistencia::create([
            'evento_id' => $eventoA->id,
            'miembro_id' => $miembroA->id,
        ]);
        ConfirmacionAsistencia::create([
            'evento_id' => $eventoB->id,
            'miembro_id' => $miembroB->id,
        ]);

        $this->actingAs($userA)
            ->get(route('confirmaciones.index'))
            ->assertOk()
            ->assertSee($eventoA->nombre)
            ->assertDontSee($eventoB->nombre);

        $this->actingAs($userB)
            ->get(route('confirmaciones.index'))
            ->assertOk()
            ->assertSee($eventoB->nombre)
            ->assertDontSee($eventoA->nombre);
    }

    public function test_evento_confirmado_desaparece_de_eventos_disponibles(): void
    {
        [$user, , $eventoConfirmado] = $this->createMemberAndEvent();
        $eventoDisponible = $this->createEvent($user);

        $this->actingAs($user)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertSee($eventoConfirmado->nombre)
            ->assertSee($eventoDisponible->nombre);

        $this->post(route('confirmaciones.store'), ['evento_id' => $eventoConfirmado->id])
            ->assertSessionHas('success');

        $this->get(route('eventos.index'))
            ->assertOk()
            ->assertDontSee($eventoConfirmado->nombre)
            ->assertSee($eventoDisponible->nombre)
            ->assertDontSee(route('confirmaciones.destroy', $eventoConfirmado));

        $this->get(route('confirmaciones.index'))
            ->assertOk()
            ->assertSee($eventoConfirmado->nombre)
            ->assertSee(route('confirmaciones.destroy', $eventoConfirmado));
    }

    public function test_member_cannot_cancel_attendance_for_non_confirmable_event(): void
    {
        [$user, $miembro, $evento] = $this->createMemberAndEvent();
        ConfirmacionAsistencia::create([
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
        ]);
        $evento->update(['estado' => 'finalizado']);

        $this->actingAs($user)
            ->delete(route('confirmaciones.destroy', $evento))
            ->assertForbidden();

        $this->assertDatabaseHas('confirmaciones_asistencia', [
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
            'estado' => 'confirmada',
        ]);
    }

    public function test_non_member_cannot_confirm_attendance(): void
    {
        $user = User::create([
            'nombre' => 'Usuario no miembro',
            'email' => 'no-miembro@example.test',
            'password' => 'password',
        ]);
        $evento = $this->createEvent($user);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertForbidden();

        $this->assertDatabaseCount('confirmaciones_asistencia', 0);
    }

    public function test_member_cannot_confirm_an_event_outside_the_confirmable_states(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();
        $evento->update(['estado' => 'finalizado']);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertForbidden();

        $this->assertDatabaseCount('confirmaciones_asistencia', 0);
    }

    public function test_user_with_inactive_status_cannot_confirm_attendance(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();
        $user->update(['estado' => 'pendiente']);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect(route('auth.pending'));

        $this->assertDatabaseCount('confirmaciones_asistencia', 0);
    }

    public function test_member_with_inactive_status_cannot_confirm_attendance(): void
    {
        [$user, $miembro, $evento] = $this->createMemberAndEvent();
        $miembro->update(['estado_miembro' => 'pendiente']);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect(route('auth.pending'));

        $this->assertDatabaseCount('confirmaciones_asistencia', 0);
    }

    public function test_confirmation_form_is_hidden_for_non_confirmable_events(): void
    {
        [$user, , $evento] = $this->createMemberAndEvent();
        $evento->update(['estado' => 'pendiente']);

        $this->actingAs($user)
            ->get(route('eventos.index'))
            ->assertOk()
            ->assertDontSee(route('confirmaciones.store'));
    }

    public function test_member_cannot_confirm_when_event_capacity_is_reached(): void
    {
        [$member, , $evento] = $this->createMemberAndEvent();
        $evento->update(['capacidad_maxima' => 1]);

        $otherRole = Role::where('nombre', 'Miembro')->firstOrFail();
        $otherUser = User::create([
            'nombre' => 'Otro miembro',
            'email' => 'otro-miembro@example.test',
            'password' => 'password',
        ]);
        $otherUser->roles()->attach($otherRole);
        $otherMember = Miembro::create([
            'user_id' => $otherUser->id,
            'estado_miembro' => 'activo',
        ]);
        ConfirmacionAsistencia::create([
            'evento_id' => $evento->id,
            'miembro_id' => $otherMember->id,
        ]);

        $this->actingAs($member)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect()
            ->assertSessionHas('error', 'El evento ha alcanzado su capacidad máxima.');

        $this->assertDatabaseCount('confirmaciones_asistencia', 1);
    }

    public function test_member_without_profile_is_redirected_to_pending_notice(): void
    {
        $role = Role::create(['nombre' => 'Miembro']);
        $user = User::create([
            'nombre' => 'Usuario sin perfil',
            'email' => 'sin-perfil@example.test',
            'password' => 'password',
        ]);
        $user->roles()->attach($role);
        $evento = $this->createEvent($user);

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), ['evento_id' => $evento->id])
            ->assertRedirect(route('auth.pending'));

        $this->assertDatabaseCount('confirmaciones_asistencia', 0);
    }

    public function test_duplicate_confirmation_is_reported_on_postgresql(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            $this->markTestSkipped('SQLSTATE 23505 is specific to PostgreSQL.');
        }

        [$user, , $evento] = $this->createMemberAndEvent();
        $payload = ['evento_id' => $evento->id];

        $this->actingAs($user)
            ->post(route('confirmaciones.store'), $payload)
            ->assertSessionHas('success', 'Asistencia confirmada exitosamente.');

        $this->from(route('eventos.index'))
            ->post(route('confirmaciones.store'), $payload)
            ->assertRedirect(route('eventos.index'))
            ->assertSessionHas('error', 'Ya has confirmado tu asistencia a este evento.');
    }

    private function createMember(): array
    {
        $role = Role::firstOrCreate(['nombre' => 'Miembro']);
        $user = User::factory()->create();
        $user->roles()->attach($role);
        $miembro = Miembro::factory()->create([
            'user_id' => $user->id,
            'estado_miembro' => 'activo',
        ]);

        return [$user, $miembro];
    }

    private function createMemberAndEvent(): array
    {
        $suffix = (string) Str::uuid();
        $role = Role::create(['nombre' => 'Miembro']);
        $user = User::create([
            'nombre' => 'Miembro QA '.$suffix,
            'email' => 'miembro.'.$suffix.'@example.test',
            'password' => 'password',
            'estado' => 'activo',
        ]);
        $user->roles()->attach($role);
        $miembro = Miembro::create([
            'user_id' => $user->id,
            'estado_miembro' => 'activo',
        ]);

        return [$user, $miembro, $this->createEvent($user, $suffix)];
    }

    private function createEvent(User $user, ?string $suffix = null): Evento
    {
        return Evento::create([
            'nombre' => 'Evento QA '.($suffix ?? (string) Str::uuid()),
            'fecha_inicio' => now()->toDateString(),
            'lugar' => 'Pruebas',
            'objetivos' => 'Probar confirmación de asistencia',
            'user_creador_id' => $user->id,
            'estado' => 'aprobado',
        ]);
    }
}
