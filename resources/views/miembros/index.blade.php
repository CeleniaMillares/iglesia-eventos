<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Gestión de miembros</h2>
                <p class="text-secondary mb-0">Consulta, edita y administra el estado de los miembros.</p>
            </div>
            <a href="{{ route('miembros.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Nuevo miembro
            </a>
        </div>

        @if(session('success'))
            <div role="status" class="alert alert-success"><i class="bi bi-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>{{ session('error') }}</div>
        @endif

        <div class="data-table table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo electrónico</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($miembros as $miembro)
                        @php($activo = $miembro->estado === 'activo' && $miembro->miembro?->estado_miembro === 'activo')
                        <tr>
                            <td class="fw-semibold">{{ $miembro->nombre }}</td>
                            <td>{{ $miembro->email }}</td>
                            <td>{{ $miembro->miembro->telefono ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $activo ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('miembros.show', $miembro) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Ver Detalles
                                    </a>
                                    <a href="{{ route('miembros.edit', $miembro) }}" class="btn btn-sm btn-outline-warning" title="Editar miembro">
                                        <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                                    </a>
                                    <form action="{{ route('miembros.estado', $miembro) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $activo ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            <i class="bi {{ $activo ? 'bi-person-x' : 'bi-person-check' }} me-1" aria-hidden="true"></i>
                                            {{ $activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('miembros.destroy', $miembro) }}" method="POST" onsubmit="return confirm('¿Eliminar este miembro y su usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">No hay miembros para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $miembros->links('pagination::bootstrap-5') }}</div>
    </div>
</x-app-layout>
