<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-700">Agenda de la iglesia</p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Detalle del evento</h2>
                <p class="mt-1 text-sm text-slate-600">Información de solo lectura del evento seleccionado.</p>
            </div>

            <a href="{{ route('eventos.index', request()->only('estado')) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Volver a eventos
            </a>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900">{{ $evento->nombre }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $evento->lugar }}</p>
                </div>
                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                    {{ ucfirst($evento->estado) }}
                </span>
            </div>

            <dl class="mt-6 grid gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de inicio</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $evento->fecha_inicio?->format('d/m/Y') ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Fecha de fin</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $evento->fecha_fin?->format('d/m/Y') ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Hora</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $evento->hora ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Capacidad máxima</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $evento->capacidad_maxima ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Creado por</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ optional($evento->creador)->nombre ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Responsable principal</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ optional($evento->responsablePrincipal)->nombre ?? 'N/A' }}</dd>
                </div>
            </dl>

            <div class="mt-6 border-t border-slate-100 pt-6">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Objetivos</dt>
                <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $evento->objetivos }}</dd>
            </div>

            @if($evento->descripcion)
                <div class="mt-6 border-t border-slate-100 pt-6">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Descripción</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $evento->descripcion }}</dd>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
