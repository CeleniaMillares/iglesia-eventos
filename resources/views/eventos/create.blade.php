<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('eventos.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nombre del Evento *</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border-gray-300 rounded" required>
                        @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Fecha de Inicio *</label>
                            <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="w-full border-gray-300 rounded" required>
                            @error('fecha_inicio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Fecha de Fin (Opcional)</label>
                            <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="w-full border-gray-300 rounded">
                            @error('fecha_fin') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Hora (Opcional)</label>
                            <input type="time" name="hora" value="{{ old('hora') }}" class="w-full border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Lugar *</label>
                            <input type="text" name="lugar" value="{{ old('lugar') }}" class="w-full border-gray-300 rounded" required>
                            @error('lugar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Objetivos del Evento</label>
                        <textarea name="objetivos" class="w-full border-gray-300 rounded" rows="3">{{ old('objetivos') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Capacidad Máxima (0 = ilimitada)</label>
                        <input type="number" name="capacidad_maxima" value="{{ old('capacidad_maxima', 0) }}" min="0" class="w-full border-gray-300 rounded">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('eventos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Evento
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>