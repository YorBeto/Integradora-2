<!DOCTYPE html>
<html>
<head>
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a; /* Negro no tan oscuro */
            color: #333;
            margin: 0;
            padding: 20px;
            text-align: center; /* Centrar todo el contenido */
        }
        .container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center; /* Centrar contenido de la caja */
        }
        h2 {
            color: #0056b3; /* Azul */
        }
        p {
            color: #666;
        }
        .password-box {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hola {{ $name }}</h2>
        <p>Gracias por registrarte en nuestra plataforma. Aquí tienes tu contraseña temporal:</p>
        <p class="password-box"><strong>{{ $password }}</strong></p>
        <p>Por favor, cambia esta contraseña después de iniciar sesión.</p>
        <p>Esta contraseña es válida hasta que la cambies.</p>
    </div>
</body>
</html>
