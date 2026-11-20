<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $aprobado ? 'Evento aprobado' : 'Evento rechazado' }}</title>
</head>
<body>
    <h1>{{ $aprobado ? 'Evento aprobado' : 'Evento rechazado' }}</h1>

    <p>Estimado miembro del Comité de Eventos:</p>

    <p>
        El evento <strong>{{ $evento->nombre }}</strong>
        {{ $aprobado ? 'ha sido aprobado por el Pastor Principal.' : 'ha sido rechazado por el Pastor Principal.' }}
    </p>

    <ul>
        <li><strong>Fecha de inicio:</strong> {{ $evento->fecha_inicio?->format('d/m/Y') ?? 'No definida' }}</li>
        <li><strong>Lugar:</strong> {{ $evento->lugar }}</li>
        <li><strong>Estado:</strong> {{ $evento->estado }}</li>
    </ul>

    @if(! $aprobado && $motivo)
        <p><strong>Motivo del rechazo:</strong> {{ $motivo }}</p>
    @endif

    <p>Atentamente,<br>{{ config('app.name', 'Iglesia Eventos') }}</p>
</body>
</html>
