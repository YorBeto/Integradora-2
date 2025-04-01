<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: white;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 800px;
            width: 90%;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* Estilo del Título */
        h1 {
            color: #007BFF;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 123, 255, 0.2);
            margin-bottom: 10px;
        }
        .separator {
            height: 3px;
            background: linear-gradient(to right, #007BFF, #00C6FF);
            margin-bottom: 20px;
        }
        /* Información de la empresa y cliente */
        .details {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
        }
        .details strong {
            color: #007BFF;
        }
        /* Mensaje de introducción */
        .intro {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            font-style: italic;
        }
        /* Tabla de productos */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 12px;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total {
            font-weight: bold;
            text-align: right;
            color: #007BFF;
        }
        .total-row {
            background-color: #e9ecef;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Factura de Compra</h1>
        <div class="separator"></div>
        
        <div class="details">
            <div class="company-info">
                <strong>Empresa:</strong> {{ env('COMPANY_NAME', 'Gatee') }}<br>
                <strong>Dirección:</strong> {{ env('COMPANY_ADDRESS', 'Av. Principal #123') }}<br>
                <strong>Teléfono:</strong> {{ env('COMPANY_PHONE', '+123 456 7890') }}
            </div>
            <div class="client-info">
                <strong>Cliente:</strong> {{ ['Juan Pérez', 'María Gómez', 'Carlos López', 'Ana Torres', 'Pedro Sánchez'][rand(0,4)] }}<br>
                <strong>Fecha:</strong> {{ date('d/m/Y') }}<br>
                <strong>Transportista:</strong> {{ $carrier }} 
            </div>
        </div>

        <div class="intro">
            Gracias por su compra. A continuación, se detallan los productos adquiridos junto con su peso correspondiente.
        </div>

                <table>
            <tr>
                <th>Producto</th>
                <th>Peso</th>
            </tr>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>
                    @if ($item['grams'] >= 1000)
                        {{ number_format($item['grams'] / 1000, 3) }} KG.
                    @else
                        {{ $item['grams'] }} G.
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="total">Total</td>
                <td class="total">
                    @if ($total >= 1000)
                        {{ number_format($total / 1000, 3) }} KG.
                    @else
                        {{ $total }} G.
                    @endif
                </td>
            </tr>
        </table>

        <p class="footer">Gracias por su compra. Para más información, visite nuestro sitio web.</p>
    </div>
</body>
</html>
