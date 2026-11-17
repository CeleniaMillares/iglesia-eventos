<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Editar Evento</h3>
            <p class="text-gray-500 text-sm mt-1">Los eventos solo se pueden editar antes de su aprobación.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('eventos.update', $evento) }}" method="POST">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre del Evento</label>
                    <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $evento->nombre) }}" maxlength="100" required class="w-full border-gray-300 rounded-lg shadow-sm">
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="fecha_inicio" class="block text-gray-700 font-bold mb-2">Fecha de Inicio</label>
                        <input id="fecha_inicio" type="date" name="fecha_inicio" value="{{ old('fecha_inicio', $evento->fecha_inicio->format('Y-m-d')) }}" required class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('fecha_inicio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="fecha_fin" class="block text-gray-700 font-bold mb-2">Fecha de Fin</label>
                        <input id="fecha_fin" type="date" name="fecha_fin" value="{{ old('fecha_fin', $evento->fecha_fin?->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('fecha_fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="hora" class="block text-gray-700 font-bold mb-2">Hora</label>
                        <input id="hora" type="time" name="hora" value="{{ old('hora', $evento->hora?->format('H:i')) }}" class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('hora')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="lugar" class="block text-gray-700 font-bold mb-2">Lugar</label>
                        <input id="lugar" type="text" name="lugar" value="{{ old('lugar', $evento->lugar) }}" maxlength="100" required class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('lugar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="capacidad_maxima" class="block text-gray-700 font-bold mb-2">Capacidad Máxima (0 = ilimitada)</label>
                        <input id="capacidad_maxima" type="number" name="capacidad_maxima" value="{{ old('capacidad_maxima', $evento->capacidad_maxima) }}" min="0" class="w-full border-gray-300 rounded-lg shadow-sm">
                        @error('capacidad_maxima')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="objetivos" class="block text-gray-700 font-bold mb-2">Objetivos del Evento</label>
                    <textarea id="objetivos" name="objetivos" rows="3" maxlength="1000" required class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('objetivos', $evento->objetivos) }}</textarea>
                    @error('objetivos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="descripcion" class="block text-gray-700 font-bold mb-2">Descripción Detallada</label>
                    <textarea id="descripcion" name="descripcion" rows="3" maxlength="1000" class="w-full border-gray-300 rounded-lg shadow-sm">{{ old('descripcion', $evento->descripcion) }}</textarea>
                    @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('eventos.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
