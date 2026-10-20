<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Enviar Convocatoria</h3>
            <p class="text-gray-500 text-sm mt-1">Evento: <strong>{{ $evento->nombre }}</strong></p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
            <form action="{{ route('convocatorias.store', $evento) }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Asunto del Correo <span class="text-red-500">*</span></label>
                    <input type="text" name="asunto" value="Invitación: {{ $evento->nombre }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Mensaje <span class="text-red-500">*</span></label>
                    <textarea name="mensaje" rows="6" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500" required>¡Bendiciones hermanos!

Los invitamos cordialmente a nuestro evento "{{ $evento->nombre }}" que se realizará el día {{ $evento->fecha_inicio->format('d/m/Y') }} en {{ $evento->lugar }}.

¡Esperamos contar con su presencia!</textarea>
                </div>

                <div class="bg-blue-50 text-blue-800 p-4 rounded-lg mb-6 text-sm">
                    <strong>Nota:</strong> Este mensaje se enviará a todos los miembros que tengan estado "Activo" en el sistema.
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('eventos.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-lg">Cancelar</a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow hover:bg-blue-700">
                        Enviar Convocatoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>