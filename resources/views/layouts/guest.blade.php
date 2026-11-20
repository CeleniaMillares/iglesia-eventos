<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Iglesia Asamblea de Dios Boliviana') }}</title>
    @include('layouts.partials.head-assets')
</head>
<body class="auth-shell">
    <main class="container-fluid p-0">
        <div class="row g-0 min-vh-100">
            <aside class="col-lg-6 d-none d-lg-flex auth-brand-panel">
                <div class="auth-brand-inner d-flex flex-column position-relative w-100">
                    <x-church-brand light />
                    <div class="auth-brand-content">
                        <span class="badge rounded-pill text-bg-warning mb-3">Portal institucional</span>
                        <h1 class="display-5 fw-bold lh-sm">Organizados para servir. Unidos para crecer.</h1>
                        <p class="fs-5 text-white-50 mt-4 mb-0">
                            Coordina eventos, fortalece la comunicación y acompaña la vida de nuestra comunidad desde un solo lugar.
                        </p>
                    </div>
                    <p class="small text-white-50 mb-0">Iglesia Asamblea de Dios Boliviana</p>
                </div>
            </aside>

            <section class="col-12 col-lg-6 auth-side">
                <div class="auth-form-container w-100 mx-auto">
                    <div class="d-lg-none text-center mb-4">
                        <x-church-brand />
                    </div>
                    <div class="auth-card mx-auto p-4 p-sm-5">
                        {{ $slot }}
                    </div>
                    <p class="text-center small text-secondary mt-4 mb-0">Acceso seguro para miembros y equipos autorizados.</p>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
