<x-guest-layout>
    <div class="mb-4">
        <span class="metric-icon metric-icon-primary mb-3"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
        <h1 class="h3 fw-bold">Confirma tu contraseña</h1>
        <p class="text-secondary mb-0">Esta es un área segura de la aplicación. Confirma tu contraseña antes de continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Confirmar</button>
    </form>
</x-guest-layout>
