<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center; /* Center text */
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        .no-data {
            text-align: center;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Motos</h1>

    @if ($motocicletas->isEmpty())
        <p class="no-data">No se ha encontrado ninguna motocicleta con esa matrícula.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>ID Cliente</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motocicletas as $motocicleta)
                <tr>
                    <td>{{ $motocicleta->Matricula }}</td>
                    <td>{{ $motocicleta->Marca }}</td>
                    <td>{{ $motocicleta->Modelo }}</td>
                    <td>{{ $motocicleta->Anyo }}</td>
                    <td>{{ $motocicleta->Color }}</td>
                    <td>{{ $motocicleta->Id_Cliente }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
