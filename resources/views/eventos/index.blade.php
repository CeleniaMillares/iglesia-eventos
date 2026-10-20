<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Gestión de Eventos</h3>
            <p class="text-gray-500 text-sm mt-1">Administra el ciclo de vida de los eventos de la congregación.</p>
        </div>
        @if(auth()->user()->tieneAlgunRol(['Comité de Eventos', 'Administrador']))
            <a href="{{ route('eventos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Evento
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($eventos as $evento)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <!-- Cabecera de la tarjeta con color según estado -->
                <div class="h-2 w-full 
                    @if($evento->estado == 'pendiente') bg-yellow-400
                    @elseif($evento->estado == 'aprobado') bg-blue-500
                    @elseif($evento->estado == 'planificado') bg-indigo-500
                    @elseif($evento->estado == 'finalizado') bg-green-500
                    @else bg-red-500 @endif">
                </div>
                
                <div class="p-5">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="text-lg font-bold text-gray-800">{{ $evento->nombre }}</h4>
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wide
                            @if($evento->estado == 'pendiente') bg-yellow-100 text-yellow-700
                            @elseif($evento->estado == 'aprobado') bg-blue-100 text-blue-700
                            @elseif($evento->estado == 'planificado') bg-indigo-100 text-indigo-700
                            @elseif($evento->estado == 'finalizado') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $evento->estado }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 space-y-2 mb-4">
                        <p class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $evento->fecha_inicio->format('d/m/Y') }} {{ $evento->hora ? '- '.$evento->hora->format('H:i') : '' }}</p>
                        <p class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> {{ $evento->lugar }}</p>
                        <p class="flex items-center"><svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Creado por: {{ $evento->creador->nombre ?? 'N/A' }}</p>
                    </div>

                    @if($evento->estado == 'rechazado' && $evento->motivo_rechazo)
                        <div class="bg-red-50 text-red-700 p-2 rounded text-xs mb-4">
                            <strong>Motivo:</strong> {{ $evento->motivo_rechazo }}
                        </div>
                    @endif

                    <!-- Botones de Acción según Rol y Estado -->
                    <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-100">
                        @if($evento->estado == 'pendiente' && auth()->user()->tieneAlgunRol(['Pastor Principal', 'Administrador']))
                            <form action="{{ route('eventos.aprobar', $evento) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white py-1.5 rounded text-sm font-semibold transition">Aprobar</button>
                            </form>
                            <!-- Botón para abrir modal de rechazo -->
                            <button onclick="document.getElementById('modal-rechazo-{{$evento->id}}').showModal()" class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 py-1.5 rounded text-sm font-semibold transition">Rechazar</button>
                            
                            <!-- Modal de Rechazo -->
                            <dialog id="modal-rechazo-{{$evento->id}}" class="p-0 rounded-lg shadow-xl backdrop:bg-gray-900/50">
                                <form action="{{ route('eventos.rechazar', $evento) }}" method="POST" class="p-6 w-96">
                                    @csrf
                                    <h5 class="text-lg font-bold mb-4">Motivo del Rechazo</h5>
                                    <textarea name="motivo_rechazo" rows="3" class="w-full border-gray-300 rounded mb-4" required placeholder="Explique por qué se rechaza..."></textarea>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" onclick="this.closest('dialog').close()" class="px-3 py-1 text-gray-600">Cancelar</button>
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Rechazar</button>
                                    </div>
                                </form>
                            </dialog>
                        @endif

                        @if($evento->estado == 'aprobado' && auth()->user()->tieneAlgunRol(['Secretaría', 'Administrador']))
                            <a href="{{ route('convocatorias.create', $evento) }}" class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded text-sm font-semibold transition">
                                Enviar Convocatoria
                            </a>
                        @endif

                    @if($evento->estado == 'planificado' && auth()->user()->tieneAlgunRol(['Secretaría', 'Administrador']))
                        <!-- Botón para finalizar -->
                        <form action="{{ route('eventos.finalizar', $evento) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-1.5 rounded text-sm font-semibold transition">
                                Finalizar Evento
                            </button>
                           
                        </form>
                        
                    @endif

                    @if($evento->estado == 'finalizado')
                        <!-- Botón para descargar el PDF -->
                        <a href="{{ route('eventos.acta', $evento) }}" class="block text-center w-full bg-red-600 hover:bg-red-700 text-white py-1.5 rounded text-sm font-semibold transition">
                            Descargar Acta PDF
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-12 text-center rounded-xl shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="text-lg font-medium text-gray-900">No hay eventos</h3>
                <p class="text-gray-500 mt-1">No se han registrado eventos todavía.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $eventos->links() }}
    </div>
</x-app-layout>