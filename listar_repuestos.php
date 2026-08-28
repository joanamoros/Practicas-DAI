<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT Referencia, Descripcion, Importe, Ganancia FROM Repuestos ORDER BY Referencia";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $resultado = $consulta->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Repuestos</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>REPUESTOS</b></h1>
        <a href="introducir_repuestos.php" class="btn btn-success my-2">Añadir nuevo repuesto</a>
        <form method="post" action="eliminar_repuestos_lista.php" class="list-form">
            <div class="table-scroll">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr><th></th><th class="text-center">Descripción</th><th class="text-center">Importe</th><th class="text-center">Ganancia</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($resultado)) { ?>
                        <tr><td colspan="4" class="text-center">No hay repuestos registrados.</td></tr>
                    <?php } ?>
                    <?php foreach ($resultado as $fila) {
                        $descripcion = htmlspecialchars($fila['Descripcion'], ENT_QUOTES, 'UTF-8');
                        $referencia = $fila['Referencia'];
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="borrar[]" value="<?php echo $referencia; ?>"></td>
                        <td class="text-center"><a href="listar_repuestos2.php?id=<?php echo $referencia; ?>"><?php echo $descripcion; ?></a></td>
                        <td class="text-center"><?php echo number_format((float)$fila['Importe'], 2); ?> €</td>
                        <td class="text-center"><?php echo htmlspecialchars($fila['Ganancia'], ENT_QUOTES, 'UTF-8'); ?> %</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="text-center my-2">
                <input type="submit" name="eliminar" value="Eliminar Repuestos Seleccionados" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar los repuestos seleccionados?');">
                <br>
                <input type="reset" value="Deseleccionar Todos" class="btn btn-secondary my-2">
            </div>
        </form>
    </div>
</body>
</html>
