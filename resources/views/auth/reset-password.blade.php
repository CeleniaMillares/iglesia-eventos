<x-guest-layout>
    <div class="mb-4">
        <span class="metric-icon metric-icon-primary mb-3"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
        <h1 class="h2 fw-bold mb-2">Nueva contraseña</h1>
        <p class="text-secondary mb-0">Elige una contraseña segura para recuperar el acceso a tu cuenta.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="form-control @error('email') is-invalid @enderror">
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Nueva contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                <input id="password" name="password" type="password" required autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="form-control @error('password_confirmation') is-invalid @enderror">
            </div>
            @error('password_confirmation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-check2-circle me-2" aria-hidden="true"></i>Restablecer contraseña
        </button>
    </form>
</x-guest-layout>
