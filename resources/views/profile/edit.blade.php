<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h2 class="h4 fw-bold mb-1">Mi perfil</h2>
                <p class="text-secondary small mb-0">Administra tus datos personales, tu contraseña y el acceso a tu cuenta.</p>
            </div>
            <span class="badge bg-primary-subtle text-primary-emphasis">Bienvenido, {{ $user->nombre }}</span>
        </div>
    </x-slot>

    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-person-badge me-2 text-primary" aria-hidden="true"></i>Información del Perfil
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bi bi-shield-lock me-2 text-primary" aria-hidden="true"></i>Actualizar Contraseña
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-danger border-2 shadow-sm">
                <div class="card-header bg-danger-subtle border-danger py-3">
                    <h5 class="card-title fw-bold text-danger mb-0">
                        <i class="bi bi-exclamation-triangle me-2" aria-hidden="true"></i>Eliminar Cuenta
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
