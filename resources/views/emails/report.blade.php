<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9fafb;
        }
        .athlete-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Informe de Evaluación Deportiva</h1>
        </div>
        
        <div class="content">
            <p>Estimado/a,</p>
            
            <p>{!! nl2br(e($message)) !!}</p>
            
            <div class="athlete-info">
                <h3>Información del Atleta</h3>
                <p><strong>Nombre:</strong> {{ $athlete->athleteProfile->first_name ?? '' }} {{ $athlete->athleteProfile->last_name ?? '' }}</p>
                <p><strong>Deporte:</strong> {{ $athlete->sport ?? 'No disponible' }}</p>
                <p><strong>Categoría:</strong> {{ $athlete->category ?? 'No disponible' }}</p>
                <p><strong>Fecha de Evaluación:</strong> {{ $athlete->evaluation_date ? \Carbon\Carbon::parse($athlete->evaluation_date)->format('d/m/Y') : 'No disponible' }}</p>
            </div>
            
            <p>Para ver el informe completo, haga clic en el siguiente enlace:</p>
            
            <div style="text-align: center;">
                <a href="{{ $reportUrl }}" class="button">Ver Informe Completo</a>
            </div>
            
            <p>Este enlace estará disponible durante 30 días.</p>
            
            <p>Saludos cordiales,<br>
            Elite Sports Tracker</p>
        </div>
        
        <div class="footer">
            <p>Este correo ha sido enviado automáticamente. Por favor, no responda a este mensaje.</p>
            <p>&copy; {{ date('Y') }} Elite Sports Tracker. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
