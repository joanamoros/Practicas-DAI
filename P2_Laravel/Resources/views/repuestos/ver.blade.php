<!DOCTYPE html>
<html>
<body>
    <h1>Repuestos</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Referencia</th>
                <th>Descripcion</th>
                <th>Importe</th>
                <th>Ganancia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($repuestos as $repuesto)
            <tr>
                <td>{{ $repuesto->Referencia }}</td>
                <td>{{ $repuesto->Descripcion }}</td>
                <td>{{ $repuesto->Importe }}</td>
                <td>{{ $repuesto->Ganancia }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
