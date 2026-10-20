<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema de Eventos') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex h-screen overflow-hidden">

            <!-- SIDEBAR (MENÚ LATERAL) -->
            <aside class="w-64 bg-[#1e293b] text-gray-300 flex flex-col shadow-xl z-20">
                <!-- Logo -->
                <div class="h-16 flex items-center justify-center border-b border-gray-700 bg-[#0f172a]">
                    <span class="text-white text-lg font-bold tracking-wider">⛪ Sistema de Eventos</span>
                </div>

                <!-- Navegación -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>

                    <!-- Eventos -->
                    <a href="{{ route('eventos.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('eventos.*') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Eventos
                    </a>

                    <!-- Miembros (Solo Secretaría y Admin) -->
                    @if(Auth::user()->tieneAlgunRol(['Administrador', 'Secretaría']))
                    <a href="{{ route('miembros.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('miembros.*') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Miembros
                    </a>
                    @endif

                      <!-- Convocatorias (Solo Secretaría y Admin) -->
                    @if(Auth::user()->tieneAlgunRol(['Administrador', 'Secretaría']))
                    <a href="{{ route('convocatorias.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('convocatorias.*') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Convocatorias
                    </a>
                    
                    <!-- Reportes (Solo Secretaría y Admin) -->
                    <a href="{{ route('reportes.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('reportes.*') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Reportes y Actas
                    </a>
                    @endif   


                    <!-- Panel Admin (Solo Administrador) -->
                    @if(Auth::user()->tieneAlgunRol(['Administrador']))
                    <div class="pt-4 pb-2">
                        <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Panel Admin</p>
                    </div>
                    <a href="{{ route('usuarios.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-700 hover:text-white transition {{ request()->routeIs('usuarios.*') ? 'bg-slate-700 text-white border-l-4 border-blue-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Usuarios del Sistema
                    </a>
                    @endif
                </nav>

                <!-- Perfil del Usuario (Abajo) -->
                <div class="p-4 border-t border-gray-700 bg-[#0f172a]">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->nombre, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->nombre }}</p>
                            <p class="text-xs text-gray-400">
                                {{ Auth::user()->roles->first()->nombre ?? 'Sin Rol' }}
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- CONTENIDO PRINCIPAL -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Barra Superior -->
                <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">
                    <div class="flex items-center">
                        <!-- Título dinámico según la ruta -->
                        <h2 class="text-xl font-semibold text-gray-800">
                            @if(request()->routeIs('dashboard')) Dashboard
                            @elseif(request()->routeIs('eventos.*')) Gestión de Eventos
                            @elseif(request()->routeIs('miembros.*')) Gestión de Miembros
                            @elseif(request()->routeIs('usuarios.*')) Usuarios del Sistema
                            @else Panel de Control @endif
                        </h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                            {{ Auth::user()->roles->first()->nombre ?? 'Miembro' }}
                        </span>
                        
                        <!-- Botón Cerrar Sesión -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Área de Contenido Dinámico -->
                <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>