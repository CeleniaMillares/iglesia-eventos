<x-app-layout>
    @push('styles')
        @vite('resources/css/bootstrap.css')
    @endpush
    <div class="surface-card p-3 p-md-4">
        <div>
            <h2 class="h3 mb-1">Editar rol</h2>
            <p class="text-muted">Actualiza el nombre o la descripción del rol.</p>
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST" class="card card-body shadow-sm">
            @csrf
            @method('PUT')
            @include('roles._form', [
                'submitLabel' => 'Guardar cambios',
                'esRolBase' => in_array($role->nombre, ['Administrador', 'Pastor Principal', 'Secretaría', 'Comité de Eventos', 'Miembro'], true),
            ])
        </form>
    </div>
</x-app-layout>
