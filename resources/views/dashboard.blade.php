<x-app-layout>
    <div class="d-grid gap-4">
        <section class="dashboard-hero p-4 p-lg-5 shadow-sm">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="small fw-semibold text-white-50 mb-2">Panel de la congregación</p>
                    <h2 class="display-6 fw-bold mb-2">¡Bienvenido, {{ Auth::user()->nombre }}!</h2>
                    <p class="text-white-50 mb-0">
                        @if(Auth::user()->tieneRol('Miembro'))
                            Consulta los eventos disponibles y administra tus asistencias confirmadas.
                        @else
                            Consulta el estado de los eventos y accede rápidamente a las tareas de tu equipo.
                        @endif
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('eventos.index') }}" class="btn btn-accent fw-semibold">
                        <i class="bi bi-calendar-event me-2" aria-hidden="true"></i>Ver eventos
                    </a>
                </div>
            </div>
        </section>

        @if(count($metricas) > 0)
            <section aria-labelledby="dashboard-stats-title">
                <div class="mb-3">
                    <h3 id="dashboard-stats-title" class="h5 fw-bold mb-1">Resumen: {{ $rolDashboard }}</h3>
                    <p class="text-secondary small mb-0">Indicadores actualizados desde los registros del sistema.</p>
                </div>
                <div class="row g-3">
                    @foreach($metricas as $metrica)
                        @php
                            $label = strtolower($metrica['label']);
                            $visual = match (true) {
                                str_contains($label, 'pendiente') => ['bi-hourglass-split', 'metric-icon-warning'],
                                str_contains($label, 'miembro') || str_contains($label, 'usuario') => ['bi-people-fill', 'metric-icon-success'],
                                str_contains($label, 'confirm') || str_contains($label, 'asistencia') => ['bi-check2-circle', 'metric-icon-success'],
                                str_contains($label, 'convocatoria') => ['bi-envelope-paper', 'metric-icon-info'],
                                str_contains($label, 'evento') => ['bi-calendar-event', 'metric-icon-primary'],
                                default => ['bi-graph-up', 'metric-icon-accent'],
                            };
                        @endphp
                        <div class="col-sm-6 col-xl-3">
                            <article class="dashboard-metric">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <p class="small fw-semibold text-secondary mb-2">{{ $metrica['label'] }}</p>
                                        <p class="h2 fw-bold mb-0">{{ number_format($metrica['value']) }}</p>
                                    </div>
                                    <span class="metric-icon {{ $visual[1] }}" aria-hidden="true"><i class="bi {{ $visual[0] }}"></i></span>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="surface-card p-4 p-lg-5" aria-labelledby="next-event-title">
            <div class="d-flex align-items-start gap-3">
                <span class="metric-icon metric-icon-primary"><i class="bi bi-calendar2-check" aria-hidden="true"></i></span>
                <div class="flex-grow-1">
                    <p class="small fw-bold text-primary text-uppercase mb-1">Agenda</p>
                    <h3 id="next-event-title" class="h5 fw-bold mb-3">Próximo evento</h3>
                    @if($proximoEvento)
                        <p class="h4 fw-bold mb-2">{{ $proximoEvento->nombre }}</p>
                        <div class="d-flex flex-wrap gap-3 text-secondary">
                            <span><i class="bi bi-calendar3 me-1" aria-hidden="true"></i><time datetime="{{ $proximoEvento->fecha_inicio->toDateString() }}">{{ $proximoEvento->fecha_inicio->format('d/m/Y') }}</time></span>
                            @if($proximoEvento->hora)
                                <span><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $proximoEvento->hora->format('H:i') }}</span>
                            @endif
                            <span><i class="bi bi-geo-alt me-1" aria-hidden="true"></i>{{ $proximoEvento->lugar }}</span>
                        </div>
                    @else
                        <p class="text-secondary mb-0">No hay eventos aprobados o planificados próximos.</p>
                    @endif
                </div>
            </div>
        </section>

        <section class="surface-card p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h3 class="h5 fw-bold mb-1">Accesos rápidos</h3>
                    <p class="text-secondary small mb-0">Continúa con la gestión de las actividades.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($accesosRapidos as $acceso)
                        <a href="{{ $acceso['url'] }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-up-right-circle me-1" aria-hidden="true"></i>{{ $acceso['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
