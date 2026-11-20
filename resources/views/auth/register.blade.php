<x-guest-layout>
    <div class="mb-4">
        <p class="small fw-bold text-primary text-uppercase mb-2 tracking-label">Únete a la comunidad</p>
        <h1 class="h2 fw-bold mb-2">Crear cuenta</h1>
        <p class="text-secondary mb-0">Registra tus datos para solicitar acceso al portal.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">Nombre</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person" aria-hidden="true"></i></span>
                <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required autofocus autocomplete="name" class="form-control @error('nombre') is-invalid @enderror">
            </div>
            @error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="form-control @error('email') is-invalid @enderror">
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Contraseña</label>
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
            <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear cuenta
        </button>
    </form>

    <p class="text-center text-secondary mt-4 mb-0">
        ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="fw-bold text-primary">Iniciar sesión</a>
    </p>
</x-guest-layout>
