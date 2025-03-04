<!DOCTYPE html>
<html>
<head>
    <title>Restablecimiento de Contraseña</title>
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
        a {
            display: inline-block;
            background-color: #fff; /* Blanco */
            color: #0056b3; /* Azul */
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: 2px solid #0056b3; /* Contorno azul */
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Arial Black', Arial, sans-serif; /* Fuente más estilizada */
            text-align: center; /* Centrar texto */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        a:hover {
            background-color: #0056b3; /* Azul */
            color: #fff; /* Blanco */
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
        <p>Has solicitado restablecer tu contraseña. Haz clic en el enlace a continuación para configurarla:</p>
        <a href="{{ $resetPasswordLink }}">Restablecer Contraseña</a>
        <p>Este enlace expirará en 30 minutos.</p>
        <p>Si no solicitaste este cambio, ignora este correo.</p>
    </div>
</body>
</html>
