<x-app-layout>
    @php
        $esPastor = auth()->user()->hasRole('Pastor Principal');
        $verAprobados = $esPastor && request('estado') === 'aprobado';
        $filtroEventos = request()->only('estado');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-700">Agenda de la iglesia</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    {{ $esPastor ? ($verAprobados ? 'Eventos Aprobados' : 'Eventos Pendientes') : 'Eventos' }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">Consulta actividades y gestiona las acciones disponibles para tu rol.</p>

                @if($esPastor)
                    <div class="btn-group mt-3" role="group" aria-label="Filtrar eventos">
                        <a href="{{ route('eventos.index') }}" class="btn btn-sm btn-outline-primary {{ $verAprobados ? '' : 'active' }}">
                            <i class="bi bi-hourglass-split me-1" aria-hidden="true"></i>Pendientes
                        </a>
                        <a href="{{ route('eventos.index', ['estado' => 'aprobado']) }}" class="btn btn-sm btn-outline-primary {{ $verAprobados ? 'active' : '' }}">
                            <i class="bi bi-calendar2-check me-1" aria-hidden="true"></i>Aprobados
                        </a>
                    </div>
                @endif
            </div>

        @if(auth()->user()->hasRole('Comité de Eventos'))
            <a href="{{ route('eventos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Crear Nuevo Evento
            </a>
        @endif

        @if(auth()->user()->hasRole('Miembro'))
            <a href="{{ route('confirmaciones.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-check2-square me-1" aria-hidden="true"></i>Mis Confirmaciones
            </a>
        @endif
        </div>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if(auth()->user()->tieneRol('Miembro'))
            <section aria-labelledby="available-events-title">
                <div class="mb-4">
                    <h3 id="available-events-title" class="h4 fw-bold text-slate-900">Eventos Disponibles</h3>
                    <p class="mt-1 small text-secondary">Confirma tu asistencia a las actividades que te interesen.</p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @forelse($disponibles as $evento)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h4 class="font-semibold text-slate-900">{{ $evento->nombre }}</h4>
                            <p class="mt-2 small text-secondary">{{ $evento->fecha_inicio->format('d/m/Y') }}</p>
                            @if($evento->hora)
                                <p class="mt-1 small text-secondary">{{ $evento->hora->format('H:i') }}</p>
                            @endif
                            <p class="mt-1 small text-secondary">{{ $evento->lugar }}</p>
                            <form class="mt-4" action="{{ route('confirmaciones.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="evento_id" value="{{ $evento->id }}">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check2-circle me-1" aria-hidden="true"></i>Confirmar Asistencia
                                </button>
                            </form>
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-300 bg-white p-5 small text-secondary">
                            No hay nuevos eventos disponibles por el momento
                        </p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $disponibles->links('pagination::bootstrap-5') }}</div>
            </section>
        @else
        <div class="data-table overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nombre</th>
                            <th scope="col" class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Fecha</th>
                            <th scope="col" class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Lugar</th>
                            <th scope="col" class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Estado</th>
                            <th scope="col" class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventos as $evento)
                            <tr class="align-top transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ $evento->nombre }}</p>
                                    <p class="mt-1 text-xs text-slate-500">Creado por {{ $evento->creador->nombre ?? 'N/A' }}</p>
                                    @if($evento->estado === 'rechazado' && $evento->motivo_rechazo)
                                        <p class="mt-2 max-w-xs text-xs text-red-700">Motivo: {{ $evento->motivo_rechazo }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                    {{ $evento->fecha_inicio->format('d/m/Y') }}
                                    @if($evento->hora)
                                        <span class="mt-1 block text-xs text-slate-500">{{ $evento->hora->format('H:i') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-sm text-slate-700">{{ $evento->lugar }}</td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    @if($evento->estado === 'pendiente')
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Pendiente</span>
                                    @elseif($evento->estado === 'aprobado')
                                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-800">Aprobado</span>
                                    @elseif($evento->estado === 'planificado')
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-800">Planificado</span>
                                    @elseif($evento->estado === 'finalizado')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">Finalizado</span>
                                    @elseif($evento->estado === 'archivado')
                                        <span class="inline-flex rounded-full bg-secondary px-3 py-1 text-xs font-semibold text-white">Archivado</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-800">Rechazado</span>
                                    @endif
                                </td>
                                <td class="min-w-64 px-5 py-4">
                                    <div class="flex flex-wrap items-start gap-2">
                                        @if(auth()->user()->hasRole('Administrador') || $esPastor)
                                            <a href="{{ route('eventos.show', ['evento' => $evento] + $filtroEventos) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1" aria-hidden="true"></i>Ver Detalles
                                            </a>
                                        @endif

                                        @if($evento->estado === 'pendiente' && auth()->user()->hasRole('Comité de Eventos'))
                                            <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar evento
                                            </a>
                                        @endif

                                        @if($evento->estado === 'pendiente' && ! $verAprobados && auth()->user()->hasRole('Pastor Principal'))
                                            <form action="{{ route('eventos.aprobar', $evento->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg me-1" aria-hidden="true"></i>Aprobar
                                                </button>
                                            </form>

                                            <details class="relative">
                                                <summary class="btn btn-sm btn-outline-danger cursor-pointer">
                                                    <i class="bi bi-x-lg me-1" aria-hidden="true"></i>Rechazar
                                                </summary>
                                                <form action="{{ route('eventos.rechazar', $evento->id) }}" method="POST" class="mt-2 min-w-56 space-y-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                                    @csrf
                                                    <label for="motivo-rechazo-{{ $evento->id }}" class="block text-xs font-medium text-slate-700">Motivo del rechazo</label>
                                                    <textarea id="motivo-rechazo-{{ $evento->id }}" name="motivo_rechazo" rows="2" maxlength="500" required class="form-control form-control-sm"></textarea>
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Confirmar rechazo
                                                    </button>
                                                </form>
                                            </details>
                                        @endif

                                        @if($evento->estado === 'aprobado' && auth()->user()->hasRole('Comité de Eventos'))
                                            <form action="{{ route('eventos.planificar', $evento->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-calendar-plus me-1" aria-hidden="true"></i>Planificar
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($evento->estado, $evento::ESTADOS_CONFIRMABLES, true) && auth()->user()->hasRole('Secretaría'))
                                            <a href="{{ route('convocatorias.create', $evento) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-envelope-plus me-1" aria-hidden="true"></i>Enviar Convocatoria
                                            </a>
                                        @endif

                                        @if($evento->estado === 'planificado' && auth()->user()->hasRole('Secretaría'))
                                            <form action="{{ route('eventos.finalizar', $evento->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-dark">
                                                    <i class="bi bi-flag me-1" aria-hidden="true"></i>Finalizar evento
                                                </button>
                                            </form>
                                        @endif

                                        @if($evento->estado === 'finalizado' && auth()->user()->hasRole('Secretaría'))
                                            <a href="{{ route('eventos.acta', $evento) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-file-earmark-text me-1" aria-hidden="true"></i>Generar Acta PDF
                                            </a>
                                        @endif

                                        @if($evento->estado === 'finalizado' && auth()->user()->hasRole('Secretaría'))
                                            <details class="relative">
                                                <summary class="btn btn-sm btn-outline-secondary cursor-pointer">
                                                    <i class="bi bi-clock-history me-1" aria-hidden="true"></i>Ver Historial de Actas
                                                </summary>
                                                <div class="mt-2 min-w-64 space-y-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                                    @forelse($evento->actas as $acta)
                                                        <div class="border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                                                            <p class="text-xs font-semibold text-slate-800">
                                                                {{ $acta->generadoPor->nombre ?? 'Usuario eliminado' }}
                                                            </p>
                                                            <p class="text-xs text-slate-500">
                                                                {{ $acta->fecha_generacion?->format('d/m/Y H:i') ?? $acta->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                                            </p>
                                                        </div>
                                                    @empty
                                                        <p class="text-xs text-slate-500">Aún no hay actas generadas.</p>
                                                    @endforelse
                                                </div>
                                            </details>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center">
                                    <p class="font-semibold text-slate-800">
                                        {{ $esPastor ? ($verAprobados ? 'Todavía no hay eventos aprobados' : 'Todavía no hay eventos pendientes') : 'Todavía no hay eventos' }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">Cuando se registren actividades, aparecerán aquí.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $eventos->links() }}</div>
        @endif
    </div>
</x-app-layout>
