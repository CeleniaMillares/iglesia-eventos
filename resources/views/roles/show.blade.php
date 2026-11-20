<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Detalle del rol</h2>
                <p class="text-muted mb-0">Nombre del rol y usuarios que lo tienen asignado.</p>
            </div>
            <div class="table-actions">
                <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                </a>
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver
                </a>
            </div>
        </div>

        <dl class="row mb-4">
            <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9 fw-semibold">{{ $role->nombre }}</dd>
            <dt class="col-sm-3">Descripción</dt><dd class="col-sm-9">{{ $role->descripcion ?: '—' }}</dd>
            <dt class="col-sm-3">Usuarios asignados</dt><dd class="col-sm-9">{{ $role->users->count() }}</dd>
        </dl>

        <h3 class="h6 text-uppercase text-secondary">Usuarios con este rol</h3>
        <div class="data-table table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th scope="col">Nombre</th><th scope="col">Correo</th><th scope="col">Estado</th></tr>
                </thead>
                <tbody>
                    @forelse($role->users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->nombre }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->estado) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">Este rol todavía no tiene usuarios asignados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
