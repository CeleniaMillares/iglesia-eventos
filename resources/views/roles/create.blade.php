<x-app-layout>
    @push('styles')
        @vite('resources/css/bootstrap.css')
    @endpush
    <div class="surface-card p-3 p-md-4">
        <div>
            <h2 class="h3 mb-1">Crear rol</h2>
            <p class="text-muted">Registra un nuevo rol para el sistema.</p>
        </div>

        <form action="{{ route('roles.store') }}" method="POST" class="card card-body shadow-sm">
            @csrf
            @include('roles._form', ['submitLabel' => 'Crear rol'])
        </form>
    </div>
</x-app-layout>
