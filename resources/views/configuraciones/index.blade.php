<x-app-layout>
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Configuración general</h3>
                <p class="text-sm text-gray-600 mt-1">Administra los datos de la iglesia y el comportamiento del sistema.</p>
            </div>
        </div>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('configuraciones.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre_iglesia" class="form-label">Nombre de la Iglesia</label>
                <input id="nombre_iglesia" name="nombre_iglesia" type="text" maxlength="100" required
                    value="{{ old('nombre_iglesia', $valores['nombre_iglesia']) }}"
                    class="form-control @error('nombre_iglesia') is-invalid @enderror">
                @error('nombre_iglesia')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="correo_contacto" class="form-label">Correo de contacto</label>
                <input id="correo_contacto" name="correo_contacto" type="email" maxlength="255" required
                    value="{{ old('correo_contacto', $valores['correo_contacto']) }}"
                    class="form-control @error('correo_contacto') is-invalid @enderror">
                @error('correo_contacto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="limite_eventos_por_pagina" class="form-label">Límite de eventos por página</label>
                <input id="limite_eventos_por_pagina" name="limite_eventos_por_pagina" type="number" min="1" max="100" required
                    value="{{ old('limite_eventos_por_pagina', $valores['limite_eventos_por_pagina']) }}"
                    class="form-control @error('limite_eventos_por_pagina') is-invalid @enderror">
                @error('limite_eventos_por_pagina')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>

        <div class="mt-5 border-top pt-4">
            <h4 class="h6 fw-bold text-gray-800">Respaldo de la base de datos</h4>
            <p class="text-sm text-gray-600">Descarga el último respaldo generado automáticamente por el sistema.</p>
            <a href="{{ route('backups.ultimo') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-download me-1" aria-hidden="true"></i>Descargar último backup
            </a>
        </div>
    </div>
</x-app-layout>
