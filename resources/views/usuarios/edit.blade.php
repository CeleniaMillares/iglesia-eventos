<x-app-layout>
    <div class="surface-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1">Editar usuario</h2>
                <p class="text-secondary mb-0">Actualiza el nombre, correo y roles asignados.</p>
            </div>
            <a href="{{ route('usuarios.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver
            </a>
        </div>

        @if($errors->any())
            <div role="alert" class="alert alert-danger">Revisa los campos indicados.</div>
        @endif

        @php($rolesSeleccionados = array_map('intval', old('roles', $user->roles->pluck('id')->all())))
        <form action="{{ route('usuarios.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input id="nombre" name="nombre" type="text" required maxlength="100" value="{{ old('nombre', $user->nombre) }}" class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico *</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <fieldset class="mb-4">
                <legend class="h6">Roles asignados *</legend>
                <div class="row g-2">
                    @forelse($roles as $rol)
                        <div class="col-sm-6 col-lg-4">
                            <div class="form-check border rounded p-2">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $rol->id }}" id="rol-{{ $rol->id }}" @checked(in_array($rol->id, $rolesSeleccionados, true))>
                                <label class="form-check-label" for="rol-{{ $rol->id }}">{{ $rol->nombre }}</label>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-secondary">No hay roles registrados.</div>
                    @endforelse
                </div>
                @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                @error('roles.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </fieldset>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</x-app-layout>
