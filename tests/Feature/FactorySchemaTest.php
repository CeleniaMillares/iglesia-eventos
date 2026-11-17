<?php

namespace Tests\Feature;

use App\Models\ConfirmacionAsistencia;
use App\Models\Convocatoria;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactorySchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_factories_use_current_fields_and_foreign_keys_follow_delete_rules(): void
    {
        $miembro = Miembro::factory()->create();
        $evento = Evento::factory()->create();
        $creador = $evento->creador;

        $this->assertSame('activo', $miembro->estado_miembro);
        $this->assertSame('activo', $miembro->user->estado);
        $this->assertNotEmpty($miembro->tipo_sangre);
        $this->assertNotEmpty($miembro->numero_referencia);
        $this->assertNotEmpty($miembro->foto);
        $this->assertNotNull($miembro->hijos);
        $this->assertNotEmpty($evento->hora);
        $this->assertNotEmpty($evento->fecha_fin);
        $this->assertNotEmpty($evento->duracion_dias);
        $this->assertNotEmpty($evento->tipo_evento);
        $this->assertNotEmpty($evento->responsable_principal_id);
        $this->assertNotEmpty($evento->cronograma);
        $this->assertNotEmpty($evento->user_creador_id);
        $this->assertNotEmpty($creador->telefono);
        $this->assertNotEmpty($creador->celular);
        $this->assertNotEmpty($creador->direccion);
        $this->assertNotEmpty($creador->ultimo_acceso);
        $this->assertNotEmpty($creador->foto);

        $creador->delete();

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'user_creador_id' => null,
        ]);

        ConfirmacionAsistencia::create([
            'evento_id' => $evento->id,
            'miembro_id' => $miembro->id,
        ]);
        Convocatoria::create([
            'evento_id' => $evento->id,
            'user_id' => $miembro->user_id,
            'asunto' => 'Invitación',
            'mensaje' => 'Mensaje de prueba',
        ]);

        $evento->delete();

        $this->assertDatabaseMissing('confirmaciones_asistencia', ['evento_id' => $evento->id]);
        $this->assertDatabaseMissing('convocatorias', ['evento_id' => $evento->id]);

        $unusedUser = User::factory()->create();
        $this->assertSame('activo', $unusedUser->estado);
    }

    public function test_event_seeder_populates_planning_fields(): void
    {
        $comite = User::factory()->create(['email' => 'comite@iglesia.com']);
        $pastor = User::factory()->create(['email' => 'pastor@iglesia.com']);

        $this->seed(\Database\Seeders\EventoSeeder::class);

        $evento = Evento::where('nombre', 'Vigilia de Oración')->firstOrFail();

        $this->assertSame($comite->id, $evento->user_creador_id);
        $this->assertSame($pastor->id, $evento->responsable_principal_id);
        $this->assertSame('Vigilia', $evento->tipo_evento);
        $this->assertNotEmpty($evento->fecha_fin);
        $this->assertNotEmpty($evento->duracion_dias);
        $this->assertNotEmpty($evento->cronograma);
    }
}
