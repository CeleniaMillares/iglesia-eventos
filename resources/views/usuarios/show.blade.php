<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Detalle del usuario</h2>
                <p class="text-secondary mb-0">Información de la cuenta y roles asignados.</p>
            </div>
            <div class="table-actions">
                <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                </a>
                <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver
                </a>
            </div>
        </div>

        <dl class="row mb-0">
            <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9">{{ $user->nombre }}</dd>
            <dt class="col-sm-3">Correo electrónico</dt><dd class="col-sm-9">{{ $user->email }}</dd>
            <dt class="col-sm-3">Rol actual</dt>
            <dd class="col-sm-9">
                @forelse($user->roles as $rol)
                    <span class="badge bg-primary-subtle text-primary-emphasis me-1">{{ $rol->nombre }}</span>
                @empty
                    <span class="text-secondary">Sin roles</span>
                @endforelse
            </dd>
            <dt class="col-sm-3">Estado</dt>
            <dd class="col-sm-9"><span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->estado) }}</span></dd>
            <dt class="col-sm-3">Fecha de registro</dt><dd class="col-sm-9">{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
            <dt class="col-sm-3">Último acceso</dt><dd class="col-sm-9">{{ $user->ultimo_acceso?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
        </dl>
    </div>
</x-app-layout>
