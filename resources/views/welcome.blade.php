<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema de Gestión de Eventos | Iglesia Asamblea de Dios Boliviana</title>
    @include('layouts.partials.head-assets')
</head>
<body>
    <header class="bg-white border-bottom">
        <div class="container py-3 d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <x-church-brand />
            <nav class="d-flex align-items-center gap-2" aria-label="Navegación principal">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-grid-1x2 me-1" aria-hidden="true"></i>Ir al panel</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light">Iniciar sesión</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Crear cuenta</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="container py-5">
            <div class="row align-items-center g-5 py-lg-5">
                <div class="col-lg-7">
                    <span class="badge rounded-pill text-bg-warning mb-3"><i class="bi bi-stars me-1" aria-hidden="true"></i>Organización y servicio</span>
                    <h1 class="display-4 fw-bold lh-sm mb-3">
                        Sistema de Gestión de Eventos
                        <span class="d-block text-primary">Iglesia Asamblea de Dios Boliviana</span>
                    </h1>
                    <p class="lead text-secondary mb-4">
                        Una plataforma para coordinar actividades, confirmar asistencias y mantener organizada la comunicación de nuestra comunidad.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg"><i class="bi bi-grid-1x2 me-2" aria-hidden="true"></i>Abrir el panel</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Iniciar sesión</a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg"><i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear cuenta</a>
                            @endif
                        @endauth
                    </div>
                    <p class="small text-secondary mt-4 mb-0"><i class="bi bi-shield-check me-1 text-success" aria-hidden="true"></i>Acceso para miembros y equipos autorizados.</p>
                </div>
                <div class="col-lg-5">
                    <div class="dashboard-hero p-4 p-md-5 shadow-lg">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <p class="text-white-50 text-uppercase small fw-bold mb-2 tracking-wide">Al servicio de la comunidad</p>
                                <h2 class="h3 fw-bold">Todo el trabajo de eventos, en un solo lugar.</h2>
                            </div>
                            <span class="metric-icon bg-white bg-opacity-10 text-warning"><i class="bi bi-calendar2-heart fs-4" aria-hidden="true"></i></span>
                        </div>
                        <div class="mt-4 d-grid gap-3">
                            <div class="d-flex gap-3 rounded-3 bg-white bg-opacity-10 p-3">
                                <i class="bi bi-calendar-event fs-4 text-warning" aria-hidden="true"></i>
                                <div><h3 class="h6 fw-bold mb-1">Gestión de eventos</h3><p class="small text-white-50 mb-0">Planifica y acompaña cada actividad.</p></div>
                            </div>
                            <div class="d-flex gap-3 rounded-3 bg-white bg-opacity-10 p-3">
                                <i class="bi bi-check2-circle fs-4 text-warning" aria-hidden="true"></i>
                                <div><h3 class="h6 fw-bold mb-1">Confirmación en línea</h3><p class="small text-white-50 mb-0">Facilita el registro de asistencia de los miembros.</p></div>
                            </div>
                            <div class="d-flex gap-3 rounded-3 bg-white bg-opacity-10 p-3">
                                <i class="bi bi-file-earmark-bar-graph fs-4 text-warning" aria-hidden="true"></i>
                                <div><h3 class="h6 fw-bold mb-1">Reportes y documentación</h3><p class="small text-white-50 mb-0">Mantén la información organizada y accesible.</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white border-top border-bottom py-5">
            <div class="container">
                <div class="text-center mb-4">
                    <p class="text-primary text-uppercase fw-bold small mb-1">Una comunidad conectada</p>
                    <h2 class="h3 fw-bold mb-0">Herramientas para servir con propósito</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-4">
                        <article class="surface-card h-100 p-4">
                            <span class="metric-icon metric-icon-primary mb-3"><i class="bi bi-calendar2-week" aria-hidden="true"></i></span>
                            <h3 class="h5 fw-bold">Gestión de eventos</h3>
                            <p class="text-secondary mb-0">Centraliza la planificación y el seguimiento de las actividades de la iglesia.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="surface-card h-100 p-4">
                            <span class="metric-icon metric-icon-success mb-3"><i class="bi bi-person-check" aria-hidden="true"></i></span>
                            <h3 class="h5 fw-bold">Confirmación en línea</h3>
                            <p class="text-secondary mb-0">Permite a los miembros organizar su participación en los eventos disponibles.</p>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="surface-card h-100 p-4">
                            <span class="metric-icon metric-icon-info mb-3"><i class="bi bi-clipboard-data" aria-hidden="true"></i></span>
                            <h3 class="h5 fw-bold">Reportes y actas</h3>
                            <p class="text-secondary mb-0">Apoya el trabajo administrativo con información y documentación centralizadas.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="container py-4 d-flex flex-column flex-sm-row justify-content-between gap-2 small text-secondary">
        <span>Iglesia Asamblea de Dios Boliviana</span>
        <span>Sistema de Gestión de Eventos</span>
    </footer>
</body>
</html>
