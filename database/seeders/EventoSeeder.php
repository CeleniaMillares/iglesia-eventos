<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evento;
use App\Models\User;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos a los usuarios para asignarles los eventos
        $comite = User::where('email', 'comite@iglesia.com')->first();
        $pastor = User::where('email', 'pastor@iglesia.com')->first();

        // Si por alguna razón no existen, evitamos que falle el seeder
        if (!$comite || !$pastor) {
            return;
        }

        $eventos = [
            [
                'nombre' => 'Vigilia de Oración',
                'descripcion' => 'Una noche de oración y alabanza para toda la congregación.',
                'fecha_inicio' => now()->addDays(5)->format('Y-m-d'), // En 5 días
                'fecha_fin' => now()->addDays(6)->format('Y-m-d'),
                'hora' => '20:00:00',
                'duracion_dias' => 2,
                'tipo_evento' => 'Vigilia',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '20:00 Inicio; 22:00 Oración; 06:00 Cierre',
                'lugar' => 'Templo Principal',
                'objetivos' => 'Buscar la presencia de Dios y unir a la iglesia.',
                'capacidad_maxima' => 100,
                'estado' => 'aprobado', // 👈 LISTO PARA QUE EL MIEMBRO CONFIRME
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
            ],
            [
                'nombre' => 'Retiro de Jóvenes',
                'descripcion' => 'Retiro espiritual de fin de semana en las afueras de la ciudad.',
                'fecha_inicio' => now()->addDays(20)->format('Y-m-d'),
                'fecha_fin' => now()->addDays(22)->format('Y-m-d'),
                'hora' => '08:00:00',
                'duracion_dias' => 2,
                'tipo_evento' => 'Retiro',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '08:00 Llegada; 09:00 Actividades; 18:00 Cierre',
                'lugar' => 'Campamento El Mirador',
                'objetivos' => 'Fortalecer la fe de la juventud y crear lazos de compañerismo.',
                'capacidad_maxima' => 30, // 👈 Cupo estricto para probar el límite
                'estado' => 'pendiente', // 👈 ESPERANDO APROBACIÓN DEL PASTOR
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => null,
            ],
            [
                'nombre' => 'Concierto de Música',
                'descripcion' => 'Concierto con artistas invitados.',
                'fecha_inicio' => now()->addDays(2)->format('Y-m-d'),
                'fecha_fin' => now()->addDays(2)->format('Y-m-d'),
                'hora' => '18:00:00',
                'duracion_dias' => 1,
                'tipo_evento' => 'Concierto',
                'responsable_principal_id' => $pastor->id,
                'cronograma' => '18:00 Apertura; 19:00 Concierto; 21:00 Cierre',
                'lugar' => 'Patio de la Iglesia',
                'objetivos' => 'Evangelismo.',
                'capacidad_maxima' => 0, // 👈 0 significa SIN LÍMITE
                'estado' => 'rechazado',
                'motivo_rechazo' => 'El presupuesto solicitado excede lo disponible este mes.',
                'user_creador_id' => $comite->id,
                'user_aprobador_id' => $pastor->id,
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::updateOrCreate(
                ['nombre' => $evento['nombre']], // Evita duplicados si corres el seeder 2 veces
                $evento
            );
        }
    }
}