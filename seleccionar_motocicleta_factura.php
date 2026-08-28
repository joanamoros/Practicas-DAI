<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT Matricula, CONCAT(Marca, ' ', Modelo) AS Moto FROM Motocicletas ORDER BY Marca, Modelo";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $motos = $consulta->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Añadir factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Añadir nueva factura</b></h1>
        <?php if (empty($motos)) { ?>
            <p>No hay motocicletas registradas. Debes dar de alta una moto antes de poder crear una factura.</p>
            <a href="seleccionar_cliente_motocicleta.php" class="btn btn-primary">Añadir motocicleta</a>
        <?php } else { ?>
        <form method="post" action="introducir_factura.php">
            <div class="form-group">
                <label for="moto">Seleccione una motocicleta:</label>
                <select class="form-control" id="moto" name="moto" required>
                    <option value="">Seleccione una motocicleta</option>
                    <?php foreach ($motos as $moto) { ?>
                    <option value="<?php echo htmlspecialchars($moto['Matricula'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($moto['Moto'] . ' (' . $moto['Matricula'] . ')', ENT_QUOTES, 'UTF-8'); ?></option>
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
