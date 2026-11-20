<div class="sidebar-partial-brand d-flex align-items-center gap-2 d-lg-none mb-3 px-3">
    <img src="{{ asset('images/logo-iglesia.png') }}" alt="Logo de la Iglesia" class="sidebar-partial-logo">
    <span class="text-white fw-semibold small">Asamblea de Dios Boliviana</span>
</div>

<nav class="sidebar-nav sidebar-menu d-flex flex-column" aria-label="Navegación del panel">
    <ul class="nav flex-column gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2" aria-hidden="true"></i>
                <span>Panel principal</span>
            </a>
        </li>

        @if(auth()->user()->hasRole('Administrador'))
            <li class="nav-item">
                <a href="{{ route('usuarios.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                    <span>Gestión de Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    <span>Gestión de Roles</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('configuracion.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('configuraciones.*', 'configuracion.*') ? 'active' : '' }}">
                    <i class="bi bi-gear" aria-hidden="true"></i>
                    <span>Configuración</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('Pastor Principal'))
            <li class="nav-item">
                <a href="{{ route('eventos.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('eventos.index') && request('estado') !== 'aprobado' ? 'active' : '' }}">
                    <i class="bi bi-hourglass-split" aria-hidden="true"></i>
                    <span>Eventos Pendientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('eventos.index', ['estado' => 'aprobado']) }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('eventos.index') && request('estado') === 'aprobado' ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                    <span>Eventos Aprobados</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reportes.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
                    <span>Reportes</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('Secretaría'))
            <li class="nav-item">
                <a href="{{ route('eventos.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('eventos.index') && !request()->has('estado') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                    <span>Eventos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('miembros.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('miembros.*') ? 'active' : '' }}">
                    <i class="bi bi-people" aria-hidden="true"></i>
                    <span>Miembros</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('convocatorias.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('convocatorias.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper" aria-hidden="true"></i>
                    <span>Convocatorias</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('eventos.index', ['estado' => 'programados']) }}" class="sidebar-link d-flex align-items-center text-white {{ request()->query('estado') === 'programados' ? 'active' : '' }}">
                    <i class="bi bi-calendar2-check" aria-hidden="true"></i>
                    <span>Eventos Programados</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('Comité de Eventos'))
            <li class="nav-item">
                <a href="{{ route('eventos.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('eventos.index') && !request()->has('estado') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                    <span>Eventos</span>
                </a>
            </li>
        @endif

        @if(auth()->user()->hasRole('Miembro'))
            <li class="nav-item">
                <a href="{{ route('eventos.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('eventos.index') && !request()->has('estado') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                    <span>Eventos Disponibles</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('confirmaciones.index') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('confirmaciones.index') ? 'active' : '' }}">
                    <i class="bi bi-check2-square" aria-hidden="true"></i>
                    <span>Mis Confirmaciones</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a href="{{ route('profile.edit') }}" class="sidebar-link d-flex align-items-center text-white {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle" aria-hidden="true"></i>
                <span>Mi Perfil</span>
            </a>
        </li>
    </ul>
</nav>
