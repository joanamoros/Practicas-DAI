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
    <title>Añadir motocicleta</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Añadir nueva motocicleta</b></h1>
        <?php if (empty($clientes)) { ?>
            <p>No hay clientes registrados. Debes dar de alta un cliente antes de poder añadir una moto.</p>
            <a href="introducir_clientes.php" class="btn btn-primary">Añadir cliente</a>
        <?php } else { ?>
        <form method="post" action="introducir_motocicleta.php">
            <div class="form-group">
                <label for="cliente">Seleccione un cliente:</label>
                <select class="form-control" id="cliente" name="cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach ($clientes as $cliente) {
                        $nombreCompleto = htmlspecialchars($cliente['Nombre'] . ' ' . $cliente['Apellido1'] . ' ' . $cliente['Apellido2'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <option value="<?php echo $cliente['Id_Cliente']; ?>"><?php echo $nombreCompleto; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Seleccionar</button>
            </div>
        </form>
        <?php } ?>
    </div>
</body>
</html>
