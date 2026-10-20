<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Gestión de Miembros</h3>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b">Nombre</th>
                        <th class="p-3 border-b">Email</th>
                        <th class="p-3 border-b">Teléfono</th>
                        <th class="p-3 border-b">Estado</th>
                        <th class="p-3 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($miembros as $miembro)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $miembro->nombre }}</td>
                            <td class="p-3">{{ $miembro->email }}</td>
                            <td class="p-3">{{ $miembro->miembro->telefono ?? 'N/A' }}</td>
                            <td class="p-3">
                                @if($miembro->estado == 'activo')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Activo</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-bold">Pendiente</span>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($miembro->estado == 'pendiente')
                                    <form action="{{ route('miembros.verificar', $miembro) }}" method="POST">
                                        @csrf
                                        <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">Verificar</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">Sin acciones</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $miembros->links() }}</div>
    </div>
</x-app-layout>