@props(['light' => false, 'href' => url('/')])

<a href="{{ $href }}" class="brand-lockup {{ $light ? 'brand-lockup-light' : '' }}">
    @if(file_exists(public_path('images/logo-iglesia.png')))
        <img src="{{ asset('images/logo-iglesia.png') }}" alt="Logo de la Iglesia Asamblea de Dios Boliviana" class="brand-logo">
    @else
        <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 48 48" fill="none">
                <path d="M24 5v8m-4-4h8M10 22l14-10 14 10v19H10V22Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19 41V29h10v12M7 22h34" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    @endif
    <span class="brand-copy">
        <strong>Asamblea de Dios</strong>
        <small>Boliviana · Gestión de eventos</small>
    </span>
</a>
