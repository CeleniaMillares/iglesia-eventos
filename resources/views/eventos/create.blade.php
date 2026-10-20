<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Proponer Nuevo Evento</h3>
            <p class="text-gray-500 text-sm mt-1">Completa los detalles. El Pastor revisará y aprobará tu propuesta.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('eventos.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Nombre del Evento <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Ej. Vigilia de Jóvenes">
                    @error('nombre') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Fecha de Inicio <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('fecha_inicio') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Fecha de Fin (Opcional)</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Hora (Opcional)</label>
                        <input type="time" name="hora" value="{{ old('hora') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Lugar <span class="text-red-500">*</span></label>
                        <input type="text" name="lugar" value="{{ old('lugar') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Ej. Templo Principal">
                        @error('lugar') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Capacidad Máxima (0 = ilimitada)</label>
                        <input type="number" name="capacidad_maxima" value="{{ old('capacidad_maxima', 0) }}" min="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Objetivos del Evento <span class="text-red-500">*</span></label>
                    <textarea name="objetivos" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3" required placeholder="¿Cuál es el propósito de este evento?">{{ old('objetivos') }}</textarea>
                    @error('objetivos') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Descripción Detallada</label>
                    <textarea name="descripcion" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3">{{ old('descripcion') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('eventos.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition">
                        Enviar Propuesta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>