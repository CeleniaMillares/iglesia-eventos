<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Reportes y Actas de Eventos</h3>
        <p class="text-gray-500 mb-6 text-sm">Aquí puedes descargar las actas en PDF de los eventos que ya han finalizado.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b">Evento</th>
                        <th class="p-3 border-b">Fecha</th>
                        <th class="p-3 border-b">Lugar</th>
                        <th class="p-3 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($eventos as $evento)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $evento->nombre }}</td>
                            <td class="p-3">{{ $evento->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $evento->lugar }}</td>
                            <td class="p-3">
                                <a href="{{ route('eventos.acta', $evento) }}" class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm font-bold hover:bg-red-200 transition">
                                    Descargar PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">No hay eventos finalizados para generar reportes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $eventos->links() }}</div>
    </div>
</x-app-layout>