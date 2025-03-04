<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #eaeaea;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background-color: #232328;
            border-radius: 10px;
            padding: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #4caf50;
            text-align: center;
        }
        label {
            color: #eaeaea;
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #121212;
            color: #eaeaea;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #1849dc;
            color: #eaeaea;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #4caf50;
        }
        .error, .success {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .error {
            background-color: #ffcccb;
            color: #121212;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Cambiar Contraseña</h2>

        <!-- Mensajes de error -->
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="user" value="{{ $user }}">

            <label for="current_password">Contraseña Actual:</label>
            <input type="password" name="current_password" value="{{ old('current_password') }}" required>
            @if ($errors->has('current_password'))
                <div class="error">{{ $errors->first('current_password') }}</div>
            @endif

            <label for="password">Nueva Contraseña:</label>
            <input type="password" name="password" value="{{ old('password') }}" required>
            @if ($errors->has('password'))
                <div class="error">{{ $errors->first('password') }}</div>
            @endif

            <label for="password_confirmation">Confirma Nueva Contraseña:</label>
            <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
            @if ($errors->has('password_confirmation'))
                <div class="error">{{ $errors->first('password_confirmation') }}</div>
            @endif

            <button type="submit">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>
