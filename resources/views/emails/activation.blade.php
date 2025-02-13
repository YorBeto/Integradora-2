<!DOCTYPE html>
<html>
<head>
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0056b3; /* Azul */
        }
        p {
            color: #666;
        }
        a {
            display: inline-block;
            background-color: #333; /* Negro */
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        a:hover {
            background-color: #0056b3; /* Azul oscuro */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hola {{ $name }}</h2>
        <p>Gracias por registrarte en nuestra plataforma. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
        <a href="{{ $activationLink }}">Activar Cuenta</a>
        <p>Aquí tienes tu contraseña temporal:</p>
        <p><strong>{{ $password }}</strong></p>
        <p>Por favor, cambia esta contraseña después de iniciar sesión.</p>
        <p>Este enlace expirará en 60 minutos.</p>
    </div>
</body>
</html>
