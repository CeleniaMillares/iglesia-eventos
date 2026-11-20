<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema de Eventos') }}</title>
    @include('layouts.partials.head-assets')
    @stack('styles')
</head>
<body>
    <div class="app-shell d-flex">
        <aside class="app-sidebar d-none d-lg-flex flex-column text-white">
            <div class="sidebar-brand">
                <x-church-brand light :href="route('dashboard')" />
            </div>
            <nav aria-label="Navegación principal">
                @include('layouts.partials.sidebar-menu')
            </nav>
            <div class="sidebar-footer">
                <div class="small text-white-50 px-3 mb-2">Sesión iniciada como</div>
                <div class="fw-semibold px-3 text-truncate">{{ Auth::user()->nombre }}</div>
                <div class="small text-white-50 px-3 mt-1">{{ Auth::user()->roles->first()?->nombre ?? 'Miembro' }}</div>
            </div>
        </aside>

        <div class="app-main d-flex flex-column">
            <header class="app-topbar d-flex align-items-center justify-content-between px-3 px-lg-4">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavigation" aria-controls="mobileNavigation" aria-label="Abrir navegación">
                        <i class="bi bi-list fs-5" aria-hidden="true"></i>
                    </button>
                    <div class="d-none d-sm-block">
                        <div class="fw-bold">
                            @if(request()->routeIs('dashboard'))
                                Inicio
                            @elseif(request()->routeIs('eventos.*'))
                                Gestión de eventos
                            @elseif(request()->routeIs('miembros.*'))
                                Gestión de miembros
                            @elseif(request()->routeIs('convocatorias.*'))
                                Convocatorias
                            @elseif(request()->routeIs('reportes.*'))
                                Reportes y actas
                            @elseif(request()->routeIs('usuarios.*'))
                                Usuarios del sistema
                            @elseif(request()->routeIs('roles.*'))
                                Gestión de roles
                            @elseif(request()->routeIs('configuraciones.*'))
                                Configuración general
                            @elseif(request()->routeIs('profile.*'))
                                Mi perfil
                            @else
                                Sistema de eventos
                            @endif
                        </div>
                        <div class="small text-secondary">Iglesia Asamblea de Dios Boliviana</div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light border d-flex align-items-center gap-2 rounded-pill px-2 py-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/'.Auth::user()->foto) }}" alt="" class="topbar-avatar">
                        @else
                            <span class="topbar-avatar">{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</span>
                        @endif
                        <span class="d-none d-sm-inline text-start">
                            <span class="d-block small fw-semibold text-dark">{{ Auth::user()->nombre }}</span>
                            <span class="topbar-role d-block text-secondary">{{ Auth::user()->roles->first()?->nombre ?? 'Miembro' }}</span>
                        </span>
                        <i class="bi bi-chevron-down small text-secondary me-1" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2" aria-hidden="true"></i>Mi perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <section class="page-heading container-fluid px-3 px-lg-4">
                <p class="small fw-bold text-uppercase text-primary mb-1 tracking-wide">Panel de gestión</p>
                <h1 class="h3 fw-bold mb-1">
                    @if(request()->routeIs('dashboard'))
                        Inicio
                    @elseif(request()->routeIs('eventos.*'))
                        Gestión de eventos
                    @elseif(request()->routeIs('miembros.*'))
                        Gestión de miembros
                    @elseif(request()->routeIs('convocatorias.*'))
                        Convocatorias
                    @elseif(request()->routeIs('reportes.*'))
                        Reportes y actas
                    @elseif(request()->routeIs('usuarios.*'))
                        Usuarios del sistema
                    @elseif(request()->routeIs('roles.*'))
                        Gestión de roles
                    @elseif(request()->routeIs('profile.*'))
                        Mi perfil
                    @else
                        Sistema de eventos
                    @endif
                </h1>
                <p class="text-secondary mb-0">Organiza actividades y acompaña el trabajo de la comunidad.</p>
            </section>

            <main class="container-fluid px-3 px-lg-4 py-4 flex-grow-1">
                <div class="content-shell mx-auto w-100">
                    {{ $slot }}
                </div>
            </main>

            <footer class="border-top bg-white px-3 px-lg-4 py-3 d-flex flex-column flex-sm-row justify-content-between gap-1 small text-secondary">
                <span>Asamblea de Dios Boliviana</span>
                <span>Sistema de Gestión de Eventos</span>
            </footer>
        </div>
    </div>

    <div class="offcanvas offcanvas-start offcanvas-sidebar" tabindex="-1" id="mobileNavigation" aria-labelledby="mobileNavigationLabel">
        <div class="offcanvas-header border-bottom border-secondary">
            <h2 class="h6 mb-0 text-white" id="mobileNavigationLabel">Navegación</h2>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">
            <nav aria-label="Navegación móvil">
                @include('layouts.partials.sidebar-menu')
            </nav>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
