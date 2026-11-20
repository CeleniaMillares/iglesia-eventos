<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h2 class="h4 fw-bold mb-1">Mis Confirmaciones</h2>
                <p class="text-secondary small mb-0">Eventos a los que confirmaste tu asistencia.</p>
            </div>
            <a href="{{ route('eventos.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>Ver eventos disponibles
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body p-3 p-md-4">
            @if (session('success'))
                <div role="status" class="alert alert-success py-2">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div role="alert" class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif

            <div class="data-table table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Evento</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Hora</th>
                            <th scope="col">Lugar</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($confirmaciones as $confirmacion)
                            <tr>
                                <td class="fw-semibold">
                                    <a href="{{ route('eventos.show', $confirmacion->evento) }}" class="text-decoration-none">
                                        {{ $confirmacion->evento->nombre }}
                                    </a>
                                </td>
                                <td>{{ $confirmacion->evento->fecha_inicio?->format('d/m/Y') }}</td>
                                <td>{{ $confirmacion->evento->hora?->format('H:i') ?? '—' }}</td>
                                <td>{{ $confirmacion->evento->lugar }}</td>
                                <td>
                                    <form action="{{ route('confirmaciones.destroy', $confirmacion->evento) }}" method="POST" onsubmit="return confirm('¿Cancelar tu asistencia a este evento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle me-1" aria-hidden="true"></i>Cancelar Asistencia
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Todavía no tienes asistencias confirmadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
