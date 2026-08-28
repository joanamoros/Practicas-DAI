<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT Id_Cliente, Nombre, Apellido1, Apellido2 FROM Clientes ORDER BY Nombre";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $clientes = $consulta->fetchAll();
?>
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
        <form method="post" action="buscar_factura_cliente2.php">
            <h4>Facturas de un cliente</h4>
            <div class="form-group">
                <br>
                <label for="id_cliente">Selecciona un cliente:</label>
                <select class="form-control" id="id_cliente" name="id_cliente" required>
                    <option value="">Selecciona un cliente</option>
                    <?php foreach ($clientes as $cliente) {
                        $nombreCompleto = htmlspecialchars($cliente['Nombre'] . ' ' . $cliente['Apellido1'] . ' ' . $cliente['Apellido2'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <option value="<?php echo $cliente['Id_Cliente']; ?>"><?php echo $nombreCompleto; ?></option>
                    <?php } ?>
                </select>
                <br>
            </div>
            <div class="text-center">
                <button type="submit" name="buscar_por_cliente" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</body>
</html>
