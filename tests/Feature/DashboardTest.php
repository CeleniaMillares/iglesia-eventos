<?php

namespace Tests\Feature;

use App\Models\ConfirmacionAsistencia;
use App\Models\Convocatoria;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_uses_database_metrics_and_existing_actions(): void
    {
        $admin = $this->userWithRole('Administrador');
        Miembro::factory()->create(['estado_miembro' => 'activo']);
        Miembro::factory()->create(['estado_miembro' => 'pendiente']);
        Evento::factory()->create(['estado' => 'pendiente']);
        Evento::factory()->create(['estado' => 'aprobado']);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk()
            ->assertViewHas('metricas', function (array $metricas): bool {
                $metricas = collect($metricas)->keyBy('label');

                return $metricas['Total usuarios']['value'] === User::count()
                    && $metricas['Miembros activos']['value'] === 1
                    && $metricas['Total eventos']['value'] === 2
                    && $metricas['Eventos pendientes']['value'] === 1;
            })
            ->assertSee(route('usuarios.index'))
            ->assertSee(route('roles.index'))
            ->assertSee(route('profile.edit'));
    }

    public function test_pastor_dashboard_filters_pending_events_and_can_open_reports(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        Evento::factory()->create(['nombre' => 'Pendiente para pastor', 'estado' => 'pendiente']);
        Evento::factory()->create(['nombre' => 'Evento aprobado oculto', 'estado' => 'aprobado']);
        Evento::factory()->create(['nombre' => 'Evento planificado', 'estado' => 'planificado']);
        Miembro::factory()->create(['estado_miembro' => 'activo']);

        $response = $this->actingAs($pastor)->get(route('dashboard'));

        $response->assertOk()
            ->assertViewHas('metricas', function (array $metricas): bool {
                $metricas = collect($metricas)->keyBy('label');

                return $metricas['Eventos pendientes']['value'] === 1
                    && $metricas['Eventos aprobados y planificados']['value'] === 2
                    && $metricas['Miembros activos']['value'] === 1;
            })
            ->assertSee(route('eventos.index', ['estado' => 'pendiente']))
            ->assertSee(route('reportes.index'));

        $this->get(route('eventos.index', ['estado' => 'pendiente']))
            ->assertOk()
            ->assertSee('Pendiente para pastor')
            ->assertDontSee('Evento aprobado oculto');

        $this->get(route('reportes.index'))->assertOk();
    }

    public function test_secretaria_dashboard_counts_members_and_convocation_work(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $memberUser = $this->userWithRole('Miembro');
        Miembro::factory()->create(['user_id' => $memberUser->id, 'estado_miembro' => 'pendiente']);
        Miembro::factory()->create(['estado_miembro' => 'pendiente']);

        $eventoSinConvocatoria = Evento::factory()->create(['estado' => 'aprobado']);
        $eventoPlanificado = Evento::factory()->create(['estado' => 'planificado']);
        $eventoConvocado = Evento::factory()->create(['estado' => 'aprobado']);
        Convocatoria::create([
            'evento_id' => $eventoConvocado->id,
            'user_id' => $secretaria->id,
            'asunto' => 'Aviso',
            'mensaje' => 'Mensaje de prueba',
            'fecha_envio' => now(),
        ]);

        $response = $this->actingAs($secretaria)->get(route('dashboard'));

        $response->assertOk()
            ->assertViewHas('metricas', function (array $metricas): bool {
                $metricas = collect($metricas)->keyBy('label');

                return $metricas['Total miembros']['value'] === 1
                    && $metricas['Pendientes de verificación']['value'] === 2
                    && $metricas['Convocatorias enviadas']['value'] === 1
                    && $metricas['Eventos para convocar']['value'] === 2;
            })
            ->assertSee(route('miembros.index'))
            ->assertSee(route('miembros.create'))
            ->assertSee(route('eventos.index', ['para_convocar' => 1]))
            ->assertSee(route('convocatorias.index'));

        $this->get(route('eventos.index', ['para_convocar' => 1]))
            ->assertOk()
            ->assertSee($eventoSinConvocatoria->nombre)
            ->assertSee($eventoPlanificado->nombre)
            ->assertDontSee($eventoConvocado->nombre);

        $this->get(route('convocatorias.create', $eventoPlanificado))->assertOk();
    }

    public function test_member_dashboard_counts_confirmations_and_renders_the_next_event(): void
    {
        $memberUser = $this->userWithRole('Miembro');
        $miembro = Miembro::factory()->create(['user_id' => $memberUser->id]);
        $nextEvent = Evento::factory()->create([
            'nombre' => 'Liderazgo juvenil',
            'fecha_inicio' => today()->addDay()->toDateString(),
            'hora' => '18:30',
            'lugar' => 'Salón principal',
            'estado' => 'aprobado',
        ]);
        $confirmedFutureEvent = Evento::factory()->create([
            'fecha_inicio' => today()->addDays(2)->toDateString(),
            'estado' => 'aprobado',
        ]);
        $availableEvent = Evento::factory()->create([
            'fecha_inicio' => today()->addDays(3)->toDateString(),
            'estado' => 'planificado',
        ]);
        $pastEvent = Evento::factory()->create([
            'fecha_inicio' => today()->subDays(3)->toDateString(),
            'estado' => 'finalizado',
        ]);

        ConfirmacionAsistencia::create([
            'evento_id' => $confirmedFutureEvent->id,
            'miembro_id' => $miembro->id,
            'estado' => 'confirmada',
        ]);
        ConfirmacionAsistencia::create([
            'evento_id' => $pastEvent->id,
            'miembro_id' => $miembro->id,
            'estado' => 'confirmada',
        ]);

        $response = $this->actingAs($memberUser)->get(route('dashboard'));

        $response->assertOk()
            ->assertViewHas('metricas', function (array $metricas): bool {
                $metricas = collect($metricas)->keyBy('label');

                return $metricas['Eventos disponibles']['value'] === 2
                    && $metricas['Mis confirmaciones']['value'] === 2
                    && $metricas['Eventos este mes']['value'] === 3
                    && $metricas['Asistencia pasada']['value'] === 1;
            })
            ->assertSee('Liderazgo juvenil')
            ->assertSee('18:30')
            ->assertSee('Salón principal')
            ->assertSee(route('eventos.index').'#confirmed-events-title');

        $this->assertDatabaseHas('eventos', ['id' => $availableEvent->id]);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }
}
