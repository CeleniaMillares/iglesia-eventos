<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Gestión de roles</h2>
                <p class="text-muted mb-0">Administra los roles y consulta sus usuarios asignados.</p>
            </div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Crear Nuevo Rol
            </a>
        </div>

        @if(session('success'))
            <div role="status" class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="data-table table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Usuarios asignados</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        @php
                            $esRolBase = in_array($role->nombre, ['Administrador', 'Pastor Principal', 'Secretaría', 'Comité de Eventos', 'Miembro'], true);
                            $noSePuedeEliminar = $role->users_count > 0;
                        @endphp
                        <tr>
                            <td class="fw-semibold">
                                {{ $role->nombre }}
                                @if($esRolBase)<span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">Base</span>@endif
                            </td>
                            <td>{{ $role->descripcion ?: '—' }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-outline-primary" title="Ver rol">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Ver
                                    </a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-warning" title="Editar rol">
                                        <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                                    </a>
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('¿Eliminar este rol?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @disabled($noSePuedeEliminar) title="{{ $noSePuedeEliminar ? 'El rol tiene usuarios asignados' : 'Eliminar rol' }}" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No hay roles registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $roles->links('pagination::bootstrap-5') }}</div>
    </div>
</x-app-layout>
