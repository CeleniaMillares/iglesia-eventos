<x-app-layout>
    <!-- Banner de Bienvenida -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-8 text-white shadow-lg mb-8 relative overflow-hidden">
        <!-- Adorno de fondo -->
        <div class="absolute right-0 top-0 opacity-10">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
        </div>
        <h3 class="text-3xl font-bold mb-2">¡Bienvenido, {{ Auth::user()->nombre }}!</h3>
        <p class="text-indigo-100 text-lg">Gestión de eventos y actividades de la congregación.</p>
    </div>

    <!-- Tarjetas de Estadísticas (Iguales a tu diseño) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Tarjeta 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Eventos</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Evento::count() }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Tarjeta 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Aprobados</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Evento::where('estado', 'aprobado')->count() }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Tarjeta 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-yellow-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pendientes</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\Evento::where('estado', 'pendiente')->count() }}</p>
            </div>
            <div class="p-3 bg-yellow-50 rounded-full text-yellow-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Tarjeta 4 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-purple-500 flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Miembros</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::count() }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-full text-purple-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h4 class="text-lg font-bold text-gray-800 mb-4">⚡ Acciones Rápidas</h4>
        <div class="flex flex-wrap gap-4">
            @if(Auth::user()->tieneAlgunRol(['Comité de Eventos', 'Administrador']))
                <a href="{{ route('eventos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition shadow">
                    + Crear Nuevo Evento
                </a>
            @endif
            <a href="{{ route('eventos.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2 px-6 rounded-lg transition shadow-sm">
                Ver Todos los Eventos
            </a>
        </div>
    </div>
</x-app-layout>