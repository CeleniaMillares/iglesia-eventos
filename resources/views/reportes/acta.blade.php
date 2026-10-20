<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta del Evento</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ccc; padding-bottom: 10px; }
        .content { margin-bottom: 20px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Acta Oficial de Evento</h1>
        <h2>{{ $evento->nombre }}</h2>
    </div>

    <div class="content">
        <p><strong>Fecha:</strong> {{ $evento->fecha_inicio->format('d/m/Y') }} {{ $evento->hora ? 'a las '.$evento->hora->format('H:i') : '' }}</p>
        <p><strong>Lugar:</strong> {{ $evento->lugar }}</p>
        <p><strong>Creado por:</strong> {{ $evento->creador->nombre ?? 'N/A' }}</p>
        <p><strong>Objetivos:</strong> {{ $evento->objetivos }}</p>
        <p><strong>Descripción:</strong> {{ $evento->descripcion ?? 'Sin descripción adicional.' }}</p>
    </div>

    <div class="content">
        <h3>Lista de Participantes Confirmados</h3>
        <table>
            <thead>
                <tr>
                    <th>Nombre del Miembro</th>
                    <th>Email</th>
                    <th>Fecha de Confirmación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evento->confirmaciones()->where('estado', 'confirmada')->with('miembro.user')->get() as $conf)
                    <tr>
                        <td>{{ $conf->miembro->user->nombre ?? 'N/A' }}</td>
                        <td>{{ $conf->miembro->user->email ?? 'N/A' }}</td>
                        <td>{{ $conf->fecha_confirmacion->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center;">No hay asistencias confirmadas aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Generado automáticamente por el Sistema de Gestión de Eventos - Iglesia Asamblea de Dios Boliviana<br>
        Fecha de generación: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>