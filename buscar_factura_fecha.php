<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Buscar facturas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Buscar facturas</b></h1>
        <form method="post" action="buscar_factura_fecha2.php">
            <h4>Facturas pagadas entre dos fechas</h4>
            <br>
            <div class="form-group">
                <label for="fecha_inicio">Fecha de inicio:</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha de fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
            </div>
            <br>
            <div class="text-center">
                <button type="submit" name="buscar_por_fechas" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</body>
</html>
