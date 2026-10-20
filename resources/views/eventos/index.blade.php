<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Mensajes de éxito o error -->
                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
                @endif

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-bold">Lista de Eventos</h3>
                    <!-- Solo mostramos el botón si el usuario es Comité o Admin -->
                    @if(auth()->user()->tieneAlgunRol(['Comité de Eventos', 'Administrador']))
                        <a href="{{ route('eventos.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Nuevo Evento
                        </a>
                    @endif
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Nombre</th>
                            <th class="p-3 border">Fecha</th>
                            <th class="p-3 border">Lugar</th>
                            <th class="p-3 border">Estado</th>
                            <th class="p-3 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eventos as $evento)
                            <tr class="border-b">
                                <td class="p-3">{{ $evento->nombre }}</td>
                                <td class="p-3">{{ $evento->fecha_inicio->format('d/m/Y') }}</td>
                                <td class="p-3">{{ $evento->lugar }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded text-xs font-bold 
                                        @if($evento->estado == 'pendiente') bg-yellow-200 text-yellow-800
                                        @elseif($evento->estado == 'aprobado') bg-green-200 text-green-800
                                        @else bg-red-200 text-red-800 @endif">
                                        {{ ucfirst($evento->estado) }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    @if($evento->estado == 'pendiente' && auth()->user()->tieneAlgunRol(['Pastor Principal', 'Administrador']))
                                        <form action="{{ route('eventos.aprobar', $evento) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:underline">Aprobar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-3 text-center text-gray-500">No hay eventos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $eventos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>