<?php

namespace Database\Seeders;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $comite = $this->usuarioPorRol('Comité de Eventos', 'comite@gmail.com');
        $pastor = $this->usuarioPorRol('Pastor Principal', 'pastor@gmail.com');

        $eventos = [
            [
                'nombre' => 'Retiro de Jóvenes 2026',
                'descripcion' => 'Fin de semana de formación espiritual, alabanza y compañerismo para los jóvenes de la congregación.',
                'fecha_inicio' => now()->addDays(18)->toDateString(),
                'fecha_fin' => now()->addDays(20)->toDateString(),
                'hora' => '08:00:00',
                'lugar' => 'Campamento El Mirador',
                'objetivos' => 'Fortalecer la fe de los jóvenes y fomentar el compañerismo cristiano.',
                'estado' => 'pendiente',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 40,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => null,
                'duracion_dias' => 3,
                'tipo_evento' => 'Retiro',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '08:00 Llegada; 09:00 Devocional; 12:00 Almuerzo; 18:00 Cierre',
            ],
            [
                'nombre' => 'Vigilia de Alabanza',
                'descripcion' => 'Encuentro nocturno de alabanza e intercesión por las familias de la iglesia.',
                'fecha_inicio' => now()->addDays(25)->toDateString(),
                'fecha_fin' => now()->addDays(25)->toDateString(),
                'hora' => '20:00:00',
                'lugar' => 'Templo Principal',
                'objetivos' => 'Interceder por la congregación y celebrar juntos la fidelidad de Dios.',
                'estado' => 'pendiente',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 80,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => null,
                'duracion_dias' => 1,
                'tipo_evento' => 'Vigilia',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '20:00 Alabanza; 21:30 Intercesión; 23:00 Cierre',
            ],
            [
                'nombre' => 'Vigilia de Oración',
                'descripcion' => 'Una noche de oración y alabanza para toda la congregación.',
                'fecha_inicio' => now()->addDays(5)->toDateString(),
                'fecha_fin' => now()->addDays(6)->toDateString(),
                'hora' => '20:00:00',
                'lugar' => 'Templo Principal',
                'objetivos' => 'Buscar la presencia de Dios y unir a la iglesia.',
                'estado' => 'aprobado',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 60,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 2,
                'tipo_evento' => 'Vigilia',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '20:00 Inicio; 22:00 Oración; 06:00 Cierre',
            ],
            [
                'nombre' => 'Concierto de Música Cristiana',
                'descripcion' => 'Concierto con artistas invitados y alabanza congregacional.',
                'fecha_inicio' => now()->addDays(12)->toDateString(),
                'fecha_fin' => now()->addDays(12)->toDateString(),
                'hora' => '18:00:00',
                'lugar' => 'Patio de la Iglesia',
                'objetivos' => 'Compartir el evangelio a través de la música y la alabanza.',
                'estado' => 'aprobado',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 120,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 1,
                'tipo_evento' => 'Concierto',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '18:00 Apertura; 19:00 Concierto; 21:00 Cierre',
            ],
            [
                'nombre' => 'Conferencia de Familia',
                'descripcion' => 'Conferencia práctica sobre matrimonio, crianza y vida familiar cristiana.',
                'fecha_inicio' => now()->addDays(21)->toDateString(),
                'fecha_fin' => now()->addDays(22)->toDateString(),
                'hora' => '09:00:00',
                'lugar' => 'Salón Multiuso',
                'objetivos' => 'Equipar a las familias con herramientas bíblicas para su vida diaria.',
                'estado' => 'planificado',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 50,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 2,
                'tipo_evento' => 'Conferencia',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '09:00 Registro; 10:00 Conferencia; 15:00 Talleres; 17:00 Cierre',
            ],
            [
                'nombre' => 'Campaña Evangelística en la Plaza',
                'descripcion' => 'Actividad de evangelismo abierta en la plaza principal de la ciudad.',
                'fecha_inicio' => now()->addDays(9)->toDateString(),
                'fecha_fin' => now()->addDays(9)->toDateString(),
                'hora' => '15:00:00',
                'lugar' => 'Plaza Principal',
                'objetivos' => 'Llevar el mensaje del evangelio a personas fuera de la congregación.',
                'estado' => 'rechazado',
                'motivo_rechazo' => 'El presupuesto solicitado excede lo disponible este mes.',
                'capacidad_maxima' => 200,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 1,
                'tipo_evento' => 'Evangelismo',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '15:00 Presentación; 16:00 Predicación; 18:00 Cierre',
            ],
            [
                'nombre' => 'Culto de Acción de Gracias',
                'descripcion' => 'Culto congregacional de gratitud por las bendiciones recibidas durante el año.',
                'fecha_inicio' => now()->subDays(15)->toDateString(),
                'fecha_fin' => now()->subDays(15)->toDateString(),
                'hora' => '10:00:00',
                'lugar' => 'Templo Principal',
                'objetivos' => 'Dar gracias a Dios por su fidelidad y compartir testimonios de la congregación.',
                'estado' => 'finalizado',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 150,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 1,
                'tipo_evento' => 'Culto',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '10:00 Alabanza; 11:00 Testimonios; 12:30 Cierre',
            ],
            [
                'nombre' => 'Bautizo de Nuevos Creyentes',
                'descripcion' => 'Celebración del bautismo de hermanos que han entregado su vida a Cristo.',
                'fecha_inicio' => now()->subDays(40)->toDateString(),
                'fecha_fin' => now()->subDays(40)->toDateString(),
                'hora' => '16:00:00',
                'lugar' => 'Río Camiri',
                'objetivos' => 'Testificar públicamente la fe de los nuevos creyentes y edificar a la congregación.',
                'estado' => 'finalizado',
                'motivo_rechazo' => null,
                'capacidad_maxima' => 70,
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
                'duracion_dias' => 1,
                'tipo_evento' => 'Bautizo',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '16:00 Bienvenida; 16:30 Bautismos; 18:00 Cierre',
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::updateOrCreate(
                ['nombre' => $evento['nombre']],
                $evento
            );
        }
    }

    private function usuarioPorRol(string $rol, string $email): User
    {
        $usuario = User::whereHas('roles', fn ($query) => $query->where('nombre', $rol))->first()
            ?? User::where('email', $email)->first();

        if (! $usuario instanceof User) {
            throw new RuntimeException("No se encontró el usuario {$rol} ({$email}).");
        }

        return $usuario;
    }
}
