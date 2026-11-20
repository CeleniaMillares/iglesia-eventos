<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Usuarios del sistema</h2>
                <p class="text-secondary mb-0">Administra cuentas, roles y estados de acceso.</p>
            </div>
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
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $user)
                        @php($protegido = $user->is(auth()->user()) || $user->email === 'admin@iglesia.com')
                        <tr>
                            <td class="fw-semibold">{{ $user->nombre }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse($user->roles as $rol)
                                    <span class="badge bg-primary-subtle text-primary-emphasis me-1">{{ $rol->nombre }}</span>
                                @empty
                                    <span class="text-secondary">Sin rol</span>
                                @endforelse
                            </td>
                            <td>
                                <span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($user->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('usuarios.show', $user) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                        <i class="bi bi-eye me-1" aria-hidden="true"></i>Ver Detalles
                                    </a>
                                    <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Editar usuario">
                                        <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                                    </a>
                                    @unless($protegido)
                                        <form action="{{ route('usuarios.estado', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $user->estado === 'activo' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                <i class="bi {{ $user->estado === 'activo' ? 'bi-person-x' : 'bi-person-check' }} me-1" aria-hidden="true"></i>
                                                {{ $user->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('usuarios.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1" aria-hidden="true"></i>Eliminar
                                            </button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">No hay usuarios registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
    </div>
</x-app-layout>
