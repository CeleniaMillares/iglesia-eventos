<x-guest-layout>
    <div class="mb-4">
        <span class="metric-icon metric-icon-primary mb-3"><i class="bi bi-envelope-check" aria-hidden="true"></i></span>
        <h1 class="h3 fw-bold">Verifica tu correo</h1>
        <p class="text-secondary mb-0">¡Gracias por registrarte! Antes de comenzar, verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no recibiste el mensaje, con gusto te enviaremos otro.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success py-2">
            Enviamos un nuevo enlace de verificación al correo electrónico que proporcionaste durante el registro.
        </div>
    @endif

    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar correo de verificación</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
