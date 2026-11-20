<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Historial de actas</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $evento->nombre }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b">Generado por</th>
                        <th class="p-3 border-b">Fecha y hora</th>
                        <th class="p-3 border-b">Archivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evento->actas as $acta)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $acta->generadoPor->nombre ?? 'Usuario eliminado' }}</td>
                            <td class="p-3">
                                {{ $acta->fecha_generacion?->format('d/m/Y H:i') ?? $acta->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                            </td>
                            <td class="p-3">{{ $acta->archivo_pdf ?? $acta->archivo ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-sm text-gray-600">
                                Todavía no se han generado actas para este evento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary btn-sm">Volver a eventos</a>
        </div>
    </div>
</x-app-layout>
