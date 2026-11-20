<p class="text-secondary mb-4">Usa una contraseña larga y aleatoria para proteger el acceso a tu cuenta.</p>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-3">
        <div class="col-12">
            <label for="update_password_current_password" class="form-label fw-semibold">Contraseña Actual</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
            @error('current_password', 'updatePassword')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="update_password_password" class="form-label fw-semibold">Nueva Contraseña</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="form-control @error('password', 'updatePassword') is-invalid @enderror">
            @error('password', 'updatePassword')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirmar Contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror">
            @error('password_confirmation', 'updatePassword')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-3 border-top pt-3 mt-4">
        <button type="submit" class="btn btn-primary px-4">Guardar</button>

        @if (session('status') === 'password-updated')
            <span class="text-success fw-semibold small" role="status">Guardado</span>
        @endif
    </div>
</form>
