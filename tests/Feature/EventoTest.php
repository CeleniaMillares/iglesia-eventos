<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_evento_finalizado_no_puede_ser_aprobado(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $creador = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($creador, 'finalizado');

        $this->actingAs($pastor)
            ->post(route('eventos.aprobar', $evento))
            ->assertStatus(409);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'finalizado',
            'user_aprobador_id' => null,
        ]);
    }

    public function test_un_evento_rechazado_no_puede_ser_aprobado(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $creador = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($creador, 'rechazado');

        $this->actingAs($pastor)
            ->post(route('eventos.aprobar', $evento))
            ->assertStatus(409);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'rechazado',
        ]);
    }

    public function test_no_se_puede_rechazar_un_evento_ya_aprobado(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $creador = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($creador, 'aprobado');

        $this->actingAs($pastor)
            ->post(route('eventos.rechazar', $evento), ['motivo_rechazo' => 'No procede'])
            ->assertStatus(409);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'aprobado',
            'motivo_rechazo' => null,
        ]);
    }

    public function test_pastor_ve_solo_eventos_aprobados_en_la_vista_de_aprobados(): void
    {
        $pastor = $this->userWithRole('Pastor Principal');
        $creador = $this->userWithRole('Comité de Eventos');
        $aprobado = $this->createEvent($creador, 'aprobado', null, 'Evento aprobado');
        $planificado = $this->createEvent($creador, 'planificado', null, 'Evento planificado');
        $pendiente = $this->createEvent($creador, 'pendiente', null, 'Evento pendiente');
        $rechazado = $this->createEvent($creador, 'rechazado', null, 'Evento rechazado');

        $this->actingAs($pastor)
            ->get(route('eventos.index', ['estado' => 'aprobado']))
            ->assertOk()
            ->assertSee('Eventos Aprobados')
            ->assertSee($aprobado->nombre)
            ->assertSee($planificado->nombre)
            ->assertDontSee($pendiente->nombre)
            ->assertDontSee($rechazado->nombre)
            ->assertDontSee(route('eventos.aprobar', $aprobado))
            ->assertDontSee(route('eventos.rechazar', $aprobado))
            ->assertSee(route('eventos.show', ['evento' => $aprobado, 'estado' => 'aprobado']));

        $this->get(route('eventos.index'))
            ->assertOk()
            ->assertSee('Eventos Pendientes')
            ->assertSee($pendiente->nombre)
            ->assertDontSee($aprobado->nombre)
            ->assertDontSee($planificado->nombre);

        $this->get(route('eventos.show', ['evento' => $aprobado, 'estado' => 'aprobado']))
            ->assertOk()
            ->assertSee(route('eventos.index', ['estado' => 'aprobado']));
    }

    public function test_un_usuario_no_puede_editar_el_evento_de_otro(): void
    {
        $rol = Role::firstOrCreate(['nombre' => 'Comité de Eventos']);
        $creador = User::factory()->create();
        $creador->roles()->attach($rol);
        $otroComite = User::factory()->create();
        $otroComite->roles()->attach($rol);

        $evento = $this->createEvent($creador, 'pendiente');

        $this->actingAs($otroComite)
            ->get(route('eventos.edit', $evento))
            ->assertForbidden();

        $this->put(route('eventos.update', $evento), [
            'nombre' => 'Nombre manipulado',
            'fecha_inicio' => now()->addDays(3)->toDateString(),
            'lugar' => 'Otro lugar',
            'objetivos' => 'Objetivos manipulados',
        ])->assertForbidden();

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Evento de prueba',
            'lugar' => 'Camiri',
        ]);
    }

    public function test_un_administrador_si_esta_autorizado_por_la_policy_para_editar(): void
    {
        $administrador = $this->userWithRole('Administrador');
        $creador = $this->userWithRole('Comité de Eventos');
        $evento = $this->createEvent($creador, 'pendiente');

        $this->assertTrue(Gate::forUser($administrador)->allows('update', $evento));
    }

    public function test_finalizar_rechaza_un_evento_pendiente(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $evento = $this->createEvent($secretaria, 'pendiente', now()->subDay()->toDateString());

        $this->actingAs($secretaria)
            ->post(route('eventos.finalizar', $evento))
            ->assertStatus(409);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'pendiente',
        ]);
    }

    public function test_finalizar_rechaza_un_evento_con_fecha_futura(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $evento = $this->createEvent($secretaria, 'planificado', now()->addDays(3)->toDateString());

        $this->actingAs($secretaria)
            ->post(route('eventos.finalizar', $evento))
            ->assertStatus(409);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'planificado',
        ]);
    }

    public function test_finalizar_permite_un_evento_planificado_ya_concluido(): void
    {
        $secretaria = $this->userWithRole('Secretaría');
        $evento = $this->createEvent($secretaria, 'planificado', now()->subDay()->toDateString());

        $this->actingAs($secretaria)
            ->post(route('eventos.finalizar', $evento))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'estado' => 'finalizado',
        ]);
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(['nombre' => $roleName]);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        return $user;
    }

    private function createEvent(
        User $creador,
        string $estado = 'pendiente',
        ?string $fechaInicio = null,
        string $nombre = 'Evento de prueba'
    ): Evento {
        return Evento::create([
            'nombre' => $nombre,
            'fecha_inicio' => $fechaInicio ?? now()->addDays(2)->toDateString(),
            'lugar' => 'Camiri',
            'objetivos' => 'Objetivos del evento',
            'user_creador_id' => $creador->id,
            'estado' => $estado,
        ]);
    }
}
