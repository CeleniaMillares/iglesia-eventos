<?php

namespace App\Http\Controllers;

use App\Models\ConfirmacionAsistencia;
use App\Models\Convocatoria;
use App\Models\Evento;
use App\Models\Miembro;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $metricas = [];
        $rolDashboard = 'Usuario';

        if ($user->tieneRol('Administrador')) {
            $rolDashboard = 'Administrador';
            $conteoEventos = Evento::query()
                ->selectRaw(
                    'COUNT(*) as total, COUNT(CASE WHEN estado = ? THEN 1 END) as pendientes',
                    ['pendiente']
                )
                ->first();
            $metricas = [
                ['label' => 'Total usuarios', 'value' => User::count()],
                ['label' => 'Miembros activos', 'value' => Miembro::where('estado_miembro', 'activo')->count()],
                ['label' => 'Total eventos', 'value' => (int) $conteoEventos->total],
                ['label' => 'Eventos pendientes', 'value' => (int) $conteoEventos->pendientes],
            ];
            $accesosRapidos = [
                ['label' => 'Configuración general', 'url' => route('configuraciones.index')],
                ['label' => 'Descargar último backup', 'url' => route('backups.ultimo')],
                ['label' => 'Gestionar usuarios', 'url' => route('usuarios.index')],
                ['label' => 'Configuración del perfil', 'url' => route('profile.edit')],
            ];

            if (Route::has('roles.index')) {
                $accesosRapidos[] = ['label' => 'Gestionar roles', 'url' => route('roles.index')];
            }
        } elseif ($user->tieneRol('Pastor Principal')) {
            $rolDashboard = 'Pastor Principal';
            $conteoEventos = Evento::query()
                ->selectRaw(
                    'COUNT(CASE WHEN estado = ? THEN 1 END) as pendientes,
                     COUNT(CASE WHEN estado IN (?, ?) THEN 1 END) as aprobados,
                     COUNT(CASE WHEN fecha_inicio >= ? THEN 1 END) as proximos',
                    ['pendiente', ...Evento::ESTADOS_CONFIRMABLES, today()->toDateString()]
                )
                ->first();
            $metricas = [
                ['label' => 'Eventos pendientes', 'value' => (int) $conteoEventos->pendientes],
                ['label' => 'Eventos aprobados y planificados', 'value' => (int) $conteoEventos->aprobados],
                ['label' => 'Miembros activos', 'value' => Miembro::where('estado_miembro', 'activo')->count()],
                ['label' => 'Próximos eventos', 'value' => (int) $conteoEventos->proximos],
            ];
            $accesosRapidos = [
                ['label' => 'Bandeja de eventos pendientes', 'url' => route('eventos.index', ['estado' => 'pendiente'])],
                ['label' => 'Reportes', 'url' => route('reportes.index')],
            ];
        } elseif ($user->tieneRol('Secretaría')) {
            $rolDashboard = 'Secretaría';
            $metricas = [
                [
                    'label' => 'Total miembros',
                    'value' => User::whereHas('roles', fn ($query) => $query->where('nombre', 'Miembro'))->count(),
                ],
                ['label' => 'Pendientes de verificación', 'value' => Miembro::where('estado_miembro', 'pendiente')->count()],
                ['label' => 'Convocatorias enviadas', 'value' => Convocatoria::count()],
                [
                    'label' => 'Eventos para convocar',
                    'value' => Evento::whereIn('estado', Evento::ESTADOS_CONFIRMABLES)
                        ->whereDoesntHave('convocatorias')
                        ->count(),
                ],
            ];
            $accesosRapidos = [
                ['label' => 'Nuevo miembro', 'url' => route('miembros.create')],
                ['label' => 'Verificar miembros', 'url' => route('miembros.index')],
                ['label' => 'Enviar convocatorias', 'url' => route('eventos.index', ['para_convocar' => 1])],
                ['label' => 'Historial de convocatorias', 'url' => route('convocatorias.index')],
            ];
        } elseif ($user->tieneRol('Miembro')) {
            $rolDashboard = 'Miembro';
            $miembroId = $user->miembro?->id;
            $confirmaciones = ConfirmacionAsistencia::query()
                ->where('confirmaciones_asistencia.miembro_id', $miembroId)
                ->where('confirmaciones_asistencia.estado', 'confirmada');
            $conteoConfirmaciones = (clone $confirmaciones)
                ->join('eventos', 'eventos.id', '=', 'confirmaciones_asistencia.evento_id')
                ->selectRaw(
                    'COUNT(DISTINCT confirmaciones_asistencia.id) as total,
                     COUNT(CASE WHEN eventos.fecha_inicio < ? THEN 1 END) as pasadas',
                    [today()->toDateString()]
                )
                ->first();
            $eventosEsteMes = $miembroId
                ? Evento::whereIn('estado', Evento::ESTADOS_CONFIRMABLES)
                    ->whereBetween('fecha_inicio', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                    ->count()
                : 0;

            $metricas = [
                [
                    'label' => 'Eventos disponibles',
                    'value' => $miembroId
                        ? Evento::whereIn('estado', Evento::ESTADOS_CONFIRMABLES)
                            ->whereDoesntHave('confirmaciones', function ($query) use ($miembroId): void {
                                $query->where('miembro_id', $miembroId)->where('estado', 'confirmada');
                            })
                            ->count()
                        : 0,
                ],
                ['label' => 'Mis confirmaciones', 'value' => (int) $conteoConfirmaciones->total],
                ['label' => 'Eventos este mes', 'value' => $eventosEsteMes],
                ['label' => 'Asistencia pasada', 'value' => (int) $conteoConfirmaciones->pasadas],
            ];
            $accesosRapidos = [
                ['label' => 'Ver eventos disponibles', 'url' => route('eventos.index')],
                ['label' => 'Mis confirmaciones', 'url' => route('eventos.index').'#confirmed-events-title'],
            ];
        } elseif ($user->tieneRol('Comité de Eventos')) {
            $rolDashboard = 'Comité de Eventos';
            $conteoMisEventos = Evento::where('user_creador_id', $user->id)
                ->selectRaw(
                    'COUNT(*) as total, COUNT(CASE WHEN estado = ? THEN 1 END) as pendientes,
                     COUNT(CASE WHEN estado IN (?, ?) THEN 1 END) as aprobados',
                    ['pendiente', ...Evento::ESTADOS_CONFIRMABLES]
                )
                ->first();
            $metricas = [
                ['label' => 'Mis eventos', 'value' => (int) $conteoMisEventos->total],
                ['label' => 'Mis propuestas pendientes', 'value' => (int) $conteoMisEventos->pendientes],
                ['label' => 'Mis eventos aprobados', 'value' => (int) $conteoMisEventos->aprobados],
            ];
            $accesosRapidos = [
                ['label' => 'Crear evento', 'url' => route('eventos.create')],
                ['label' => 'Ver mis eventos', 'url' => route('eventos.index')],
            ];
        } else {
            $accesosRapidos = [
                ['label' => 'Ver eventos', 'url' => route('eventos.index')],
            ];
        }

        $proximoEvento = Evento::query()
            ->whereIn('estado', Evento::ESTADOS_CONFIRMABLES)
            ->where('fecha_inicio', '>=', today()->toDateString())
            ->orderBy('fecha_inicio')
            ->orderBy('hora')
            ->first(['id', 'nombre', 'fecha_inicio', 'hora', 'lugar']);

        return view('dashboard', compact('metricas', 'rolDashboard', 'accesosRapidos', 'proximoEvento'));
    }
}
