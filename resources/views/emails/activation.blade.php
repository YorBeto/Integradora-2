<!DOCTYPE html>
<html>
<head>
    <title>Activación de Cuenta</title>
</head>
<body>
    <h2>Hola {{ $persona->name }}</h2>
    <p>Gracias por registrarte en nuestra plataforma. Para activar tu cuenta, haz clic en el siguiente enlace:</p>
    <a href="{{ $activationLink }}">Activar Cuenta</a>
    <p>Este enlace expirará en 60 minutos.</p>
</body>
</html>
