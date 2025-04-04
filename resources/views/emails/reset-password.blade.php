<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <style>
        /* ===== RESET Y ESTILOS BASE ===== */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #141416;
            color: #EAEAEA;
            height: 100vh;
            display: flex;
        }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .contenedor {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 0;
            left: 0;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(0, 140, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(0, 242, 255, 0.1) 0%, transparent 20%);
        }

        /* ===== CONTENEDOR MENSAJE ===== */
        .message-container {
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            background-color: #232328;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            z-index: 2;
            position: relative;
        }

        /* ===== TÍTULO Y MENSAJE ===== */
        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #EAEAEA;
        }

        p {
            font-size: 16px;
            color: #EAEAEA;
            line-height: 1.6;
        }

        strong {
            font-weight: bold;
            color: #00f2ff;
        }

        /* ===== MENSAJE DE RECOMENDACIÓN ===== */
        .recommendation {
            margin-top: 20px;
            font-size: 14px;
            color: #B0B0B0;
        }

        .recommendation a {
            color: #00a8ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .recommendation a:hover {
            color: #00f2ff;
            text-decoration: underline;
        }

        /* ===== MENSAJE DE CIERRE ===== */
        .closing {
            margin-top: 20px;
            font-size: 14px;
            color: #B0B0B0;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="message-container">
            <h2>Hola, {{ $name }}</h2>
            <p>Tu nueva contraseña es: <strong>{{ $newPassword }}</strong></p>
            <p>Por seguridad, te recomendamos cambiarla después de iniciar sesión.</p>
            <p class="closing">Gracias,</p>
            <p class="closing">Atentamente Gatee</p>
            <p class="recommendation">Si no solicitaste este cambio, por favor contacta con nuestro soporte.</p>
        </div>
    </div>
</body>
</html>
