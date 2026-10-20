<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Usuarios del Sistema</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b">Nombre</th>
                        <th class="p-3 border-b">Email</th>
                        <th class="p-3 border-b">Rol</th>
                        <th class="p-3 border-b">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $user)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $user->nombre }}</td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">
                                @foreach($user->roles as $rol)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">{{ $rol->nombre }}</span>
                                @endforeach
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 {{ $user->estado == 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded text-xs font-bold">
                                    {{ ucfirst($user->estado) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $usuarios->links() }}</div>
    </div>
</x-app-layout>