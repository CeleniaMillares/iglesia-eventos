<x-guest-layout>
    <div class="mb-4">
        <span class="metric-icon metric-icon-primary mb-3"><i class="bi bi-key" aria-hidden="true"></i></span>
        <h1 class="h2 fw-bold mb-2">Recuperar contraseña</h1>
        <p class="text-secondary mb-0">Indica el correo asociado a tu cuenta y te enviaremos un enlace para restablecerla.</p>
    </div>

    <x-auth-session-status class="alert alert-success py-2" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope" aria-hidden="true"></i></span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control @error('email') is-invalid @enderror">
            </div>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-send me-2" aria-hidden="true"></i>Enviar enlace de recuperación
        </button>
    </form>
</x-guest-layout>
