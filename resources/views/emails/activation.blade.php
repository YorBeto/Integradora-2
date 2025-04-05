<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de Cuenta</title>
    <style>
        /* ===== ESTILOS BASE ===== */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #141416;
            color: #EAEAEA;
        }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .activation-container {
            min-height: 100vh;
            width: 100vw;
            display: flex;
            position: relative;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(0, 140, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 70%, rgba(0, 242, 255, 0.1) 0%, transparent 20%);
        }

        /* ===== CONTENEDOR MENSAJE ===== */
        .activation-message-container {
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
        .activation-recommendation {
            margin-top: 20px;
            font-size: 14px;
            color: #B0B0B0;
        }

        .activation-recommendation a {
            color: #00a8ff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .activation-recommendation a:hover {
            color: #00f2ff;
            text-decoration: underline;
        }

        /* ===== MENSAJE DE CIERRE ===== */
        .activation-closing {
            margin-top: 20px;
            font-size: 14px;
            color: #B0B0B0;
        }

        /* ===== ESTILO PARA EL ENLACE ===== */
        .activation-link {
            display: inline-block;
            background-color: #fff;
            color: #0056b3;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: 2px solid #0056b3;
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Arial Black', Arial, sans-serif;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .activation-link:hover {
            background-color: #0056b3;
            color: #fff;
        }

        /* ===== ESTILO PARA LA CONTRASEÑA ===== */
        .activation-password-box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="activation-container">
        <div class="activation-message-container">
            <h2>Hola {{ $name }}</h2>
            <p>Gracias por registrarte en nuestra plataforma. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
            <a href="{{ $activationLink }}" class="activation-link">Activar Cuenta</a>
            <p>Aquí tienes tu contraseña temporal:</p>
            <p class="activation-password-box"><strong>{{ $password }}</strong></p>
            <p>Por favor, cambia esta contraseña después de iniciar sesión.</p>
            <p>Este enlace expirará en 60 minutos.</p>
            <p class="activation-closing">Gracias, Atentamente Gatee</p>
            <p class="activation-recommendation">Si no solicitaste este cambio, por favor contacta con nuestro soporte.</p>
        </div>
    </div>
</body>
</html>
