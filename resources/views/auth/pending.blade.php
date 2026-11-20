<x-guest-layout>
    <div class="text-center">
        <span class="metric-icon metric-icon-warning mb-3"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <h1 class="h3 fw-bold">Cuenta en revisión</h1>
        <p class="text-secondary">
            Tu registro está pendiente de verificación por Secretaría. Cuando tu cuenta y perfil de miembro estén activos, podrás acceder al portal y confirmar asistencias.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
