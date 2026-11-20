<?php

namespace Tests\Feature;

use App\Models\Evento;
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
            ->assertSee('El campo nombre es obligatorio.')
            ->assertSee('posterior o igual a hoy');
    }

    public function test_event_planning_fields_are_saved_and_responsible_is_limited_to_internal_roles(): void
    {
        $committeeRole = Role::create(['nombre' => 'Comité de Eventos']);
        $pastorRole = Role::create(['nombre' => 'Pastor Principal']);
        $memberRole = Role::create(['nombre' => 'Miembro']);

        $creator = User::factory()->create();
        $creator->roles()->attach($committeeRole);

        $pastor = User::factory()->create(['nombre' => 'Pastor Responsable']);
        $pastor->roles()->attach($pastorRole);

        $member = User::factory()->create(['nombre' => 'Miembro Sin Permiso']);
        $member->roles()->attach($memberRole);

        $this->actingAs($creator)
            ->get(route('eventos.create'))
            ->assertOk()
            ->assertSee('Pastor Responsable')
            ->assertDontSee('Miembro Sin Permiso');

        $eventData = [
            'nombre' => 'Retiro de prueba',
            'fecha_inicio' => now()->addDays(3)->toDateString(),
            'fecha_fin' => now()->addDays(4)->toDateString(),
            'hora' => '09:30',
            'lugar' => 'Templo',
            'objetivos' => 'Objetivos del retiro',
            'tipo_evento' => 'Retiro',
            'duracion_dias' => 2,
            'responsable_principal_id' => $member->id,
            'cronograma' => '09:00 Apertura',
        ];

        $this->post(route('eventos.store'), $eventData)
            ->assertSessionHasErrors('responsable_principal_id');

        $eventData['responsable_principal_id'] = $pastor->id;

        $this->post(route('eventos.store'), $eventData)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('eventos.index'));

        $evento = Evento::where('nombre', 'Retiro de prueba')->firstOrFail();

        $this->assertSame('Retiro', $evento->tipo_evento);
        $this->assertSame(2, $evento->duracion_dias);
        $this->assertSame($pastor->id, $evento->responsable_principal_id);
        $this->assertSame('09:00 Apertura', $evento->cronograma);

        $eventData['nombre'] = 'Retiro actualizado';
        $eventData['responsable_principal_id'] = $pastor->id;

        $this->put(route('eventos.update', $evento), $eventData)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('eventos.index'));

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Retiro actualizado',
            'tipo_evento' => 'Retiro',
            'duracion_dias' => 2,
            'responsable_principal_id' => $pastor->id,
            'cronograma' => '09:00 Apertura',
        ]);
    }
}
