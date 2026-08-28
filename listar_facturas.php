<?php
    include("auth.php");
    include("conexionPDO.php");

    // OJO: se lista la tabla Facturas (una fila = una factura), no
    // Detalle_Factura (que tendría varias filas por factura si esta tiene
    // varias líneas de repuestos).
    $sql = "SELECT f.Numero_Factura, f.Fecha_Emision, f.Fecha_Pago, f.Total, m.Marca, m.Modelo
            FROM Facturas f JOIN Motocicletas m ON f.Matricula = m.Matricula
            ORDER BY f.Fecha_Emision DESC, f.Numero_Factura DESC";
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
    <title>Facturas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>FACTURAS</b></h1>
        <a href="seleccionar_motocicleta_factura.php" class="btn btn-success my-2">Añadir nueva factura</a>
        <form method="post" action="eliminar_facturas_lista.php" class="list-form">
            <div class="table-scroll">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr><th></th><th class="text-center">Nº factura</th><th class="text-center">Moto</th><th class="text-center">Fecha emisión</th><th class="text-center">Pagada</th><th class="text-center">Total</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($resultado)) { ?>
                        <tr><td colspan="6" class="text-center">No hay facturas registradas.</td></tr>
                    <?php } ?>
                    <?php foreach ($resultado as $fila) {
                        $numero_factura = htmlspecialchars($fila['Numero_Factura'], ENT_QUOTES, 'UTF-8');
                        $moto = htmlspecialchars($fila['Marca'] . ' ' . $fila['Modelo'], ENT_QUOTES, 'UTF-8');
                        $pagada = (!empty($fila['Fecha_Pago']) && $fila['Fecha_Pago'] !== '0000-00-00') ? 'Sí' : 'No';
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="borrar[]" value="<?php echo $numero_factura; ?>"></td>
                        <td class="text-center"><a href="listar_facturas2.php?id=<?php echo urlencode($fila['Numero_Factura']); ?>"><?php echo $numero_factura; ?></a></td>
                        <td class="text-center"><?php echo $moto; ?></td>
                        <td class="text-center"><?php echo htmlspecialchars($fila['Fecha_Emision'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="text-center"><?php echo $pagada; ?></td>
                        <td class="text-center"><?php echo number_format((float)$fila['Total'], 2); ?> €</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="text-center my-2">
                <input type="submit" name="eliminar" value="Eliminar Facturas Seleccionadas" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar las facturas seleccionadas?');">
                <br>
                <input type="reset" value="Deseleccionar Todos" class="btn btn-secondary my-2">
            </div>
        </form>
    </div>
</body>
</html>
