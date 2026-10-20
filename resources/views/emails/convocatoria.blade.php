<!DOCTYPE html>
<html>
<head>
    <title>{{ $evento->nombre }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Invitación a: {{ $evento->nombre }}</h2>
    
    <p><strong>Fecha:</strong> {{ $evento->fecha_inicio->format('d/m/Y') }} a las {{ $evento->hora ? $evento->hora->format('H:i') : '00:00' }}</p>
    <p><strong>Lugar:</strong> {{ $evento->lugar }}</p>
    
    <hr>
    
    <p style="white-space: pre-wrap;">{{ $mensajeTexto }}</p>
    
    <hr>
    <p style="font-size: 12px; color: #777;">Atentamente,<br>Secretaría de la Iglesia Asamblea de Dios Boliviana</p>
</body>
</html>