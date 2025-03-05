<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: left; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Fecha: {{ $date }}</p>
    <table>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
        </tr>
        @foreach($items as $item)
        <tr>
            <td>{{ $item['name'] }}</td>
            <td>${{ number_format($item['price'], 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td class="total">Total</td>
            <td class="total">${{ number_format($total, 2) }}</td>
        </tr>
    </table>
</body>
</html>
