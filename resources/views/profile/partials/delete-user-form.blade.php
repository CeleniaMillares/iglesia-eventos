<p class="text-secondary mb-3">Esta acción es permanente. Eliminará tu usuario, tus datos personales y todo el historial asociado a tu cuenta.</p>

@if ($errors->userDeletion->isNotEmpty())
    <div class="alert alert-danger py-2" role="alert">No se pudo eliminar la cuenta. Revisa la contraseña e inténtalo nuevamente.</div>
@endif

<button type="button" class="btn btn-danger px-4" data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">
    <i class="bi bi-trash me-2" aria-hidden="true"></i>Eliminar Cuenta
</button>

<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-labelledby="modalEliminarCuentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header border-danger">
                <h2 class="modal-title h5 fw-bold" id="modalEliminarCuentaLabel">¿Estás seguro de que quieres eliminar tu cuenta?</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary mb-3">Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.</p>

                <label for="password" class="form-label fw-semibold">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Ingresa tu contraseña" class="form-control @error('password', 'userDeletion') is-invalid @enderror" required>
                @error('password', 'userDeletion')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="modal-footer border-danger">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Sí, Eliminar Cuenta</button>
            </div>
        </form>
    </div>
</div>
