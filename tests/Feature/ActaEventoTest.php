<?php

namespace Tests\Feature;

use App\Models\ActaEvento;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActaEventoTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_has_persisted_minutes_and_member_children_default_to_zero(): void
    {
        $evento = Evento::factory()->create([
            'cronograma' => '09:00 Apertura; 10:00 Mensaje',
        ]);
        $acta = ActaEvento::create([
            'evento_id' => $evento->id,
            'archivo' => 'actas/evento.pdf',
        ]);

        $miembro = Miembro::create([
            'user_id' => User::factory()->create()->id,
            'estado_miembro' => 'activo',
        ]);

        $this->assertSame($evento->id, $acta->evento->id);
        $this->assertSame($acta->id, $evento->fresh()->actas->sole()->id);
        $this->assertSame(0, $miembro->fresh()->hijos);
    }
}
