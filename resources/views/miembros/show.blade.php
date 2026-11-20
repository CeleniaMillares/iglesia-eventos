<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Detalle del miembro</h2>
                <p class="text-secondary mb-0">Información personal y de contacto registrada.</p>
            </div>
            <div class="table-actions">
                <a href="{{ route('miembros.edit', $user) }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil me-1" aria-hidden="true"></i>Editar
                </a>
                <a href="{{ route('miembros.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver
                </a>
            </div>
        </div>

        @php($miembro = $user->miembro)
        <div class="row g-4">
            <div class="col-lg-6">
                <h3 class="h6 text-uppercase text-secondary">Datos de la cuenta</h3>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nombre</dt><dd class="col-sm-8">{{ $user->nombre }}</dd>
                    <dt class="col-sm-4">Correo</dt><dd class="col-sm-8">{{ $user->email }}</dd>
                    <dt class="col-sm-4">Estado</dt>
                    <dd class="col-sm-8"><span class="badge {{ $user->estado === 'activo' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($user->estado) }}</span></dd>
                    <dt class="col-sm-4">Roles</dt>
                    <dd class="col-sm-8">
                        @forelse($user->roles as $rol)
                            <span class="badge bg-primary-subtle text-primary-emphasis me-1">{{ $rol->nombre }}</span>
                        @empty
                            <span class="text-secondary">Sin roles</span>
                        @endforelse
                    </dd>
                    <dt class="col-sm-4">Registro</dt><dd class="col-sm-8">{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</dd>
                </dl>
            </div>

            <div class="col-lg-6">
                <h3 class="h6 text-uppercase text-secondary">Datos personales</h3>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Teléfono</dt><dd class="col-sm-7">{{ $miembro?->telefono ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Celular</dt><dd class="col-sm-7">{{ $user->celular ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Dirección</dt><dd class="col-sm-7">{{ $miembro?->direccion ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Fecha de nacimiento</dt><dd class="col-sm-7">{{ $miembro?->fecha_nacimiento?->format('d/m/Y') ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Tipo de sangre</dt><dd class="col-sm-7">{{ $miembro?->tipo_sangre ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Número de referencia</dt><dd class="col-sm-7">{{ $miembro?->numero_referencia ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Estado civil</dt><dd class="col-sm-7">{{ $miembro?->estado_civil ? ucfirst($miembro->estado_civil) : 'N/A' }}</dd>
                    <dt class="col-sm-5">Cónyuge</dt><dd class="col-sm-7">{{ $miembro?->conyuge ?? 'N/A' }}</dd>
                    <dt class="col-sm-5">Hijos</dt><dd class="col-sm-7">{{ $miembro?->hijos ?? 0 }}</dd>
                    <dt class="col-sm-5">Profesión</dt><dd class="col-sm-7">{{ $miembro?->profesion ?? 'N/A' }}</dd>
                </dl>
            </div>

            <div class="col-12">
                <h3 class="h6 text-uppercase text-secondary">Contacto de emergencia</h3>
                <dl class="row mb-0">
                    <dt class="col-sm-3">Contacto</dt><dd class="col-sm-9">{{ $miembro?->contacto_emergencia ?? 'N/A' }}</dd>
                    <dt class="col-sm-3">Teléfono</dt><dd class="col-sm-9">{{ $miembro?->telefono_emergencia ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
