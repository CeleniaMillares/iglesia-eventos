<x-guest-layout>
    <a href="{{ url('/') }}" class="small fw-semibold text-secondary">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver al inicio
    </a>

    <div class="mt-4 mb-4">
        <p class="small fw-bold text-primary text-uppercase mb-2 tracking-label">Acceso al sistema</p>
        <h1 class="h2 fw-bold mb-2">Iniciar sesión</h1>
        <p class="text-secondary mb-0">Ingresa tus credenciales para continuar.</p>
    </div>

    <x-auth-session-status class="alert alert-success py-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nombre@iglesia.com" class="form-control @error('email') is-invalid @enderror">
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <label for="password" class="form-label fw-semibold mb-2">Contraseña</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small fw-semibold text-primary mb-2">¿Olvidaste tu contraseña?</a>
                @endif
            </div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña" class="form-control @error('password') is-invalid @enderror">
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
            <label for="remember_me" class="form-check-label text-secondary">Recuérdame</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Entrar al sistema
        </button>
    </form>

    @if(Route::has('register'))
        <p class="text-center text-secondary mt-4 mb-0">
            ¿Aún no tienes cuenta? <a href="{{ route('register') }}" class="fw-bold text-primary">Crear cuenta</a>
        </p>
    @endif
</x-guest-layout>
