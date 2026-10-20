<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Historial de Convocatorias</h3>

        <!-- Botón para crear una nueva convocatoria -->
        <a href="{{ route('eventos.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Convocatoria
        </a>
    </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b">Asunto</th>
                        <th class="p-3 border-b">Evento</th>
                        <th class="p-3 border-b">Enviado por</th>
                        <th class="p-3 border-b">Fecha de Envío</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($convocatorias as $conv)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $conv->asunto }}</td>
                            <td class="p-3">{{ $conv->evento->nombre ?? 'Evento eliminado' }}</td>
                            <td class="p-3">{{ $conv->user->nombre ?? 'N/A' }}</td>
                            <td class="p-3">{{ $conv->fecha_envio->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay convocatorias enviadas aún.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $convocatorias->links() }}</div>
    </div>
</x-app-layout>