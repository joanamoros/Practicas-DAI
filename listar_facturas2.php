<?php
    include("auth.php");
    include("conexionPDO.php");

    $numero_factura = isset($_GET['id']) ? $_GET['id'] : '';

    $sql = "SELECT * FROM Facturas WHERE Numero_Factura = :numero_factura";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':numero_factura', $numero_factura, PDO::PARAM_STR);
    $consulta->execute();
    $fila = $consulta->fetch();

    if (!$fila) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Factura no encontrada.</p>";
        exit();
    }

    // Líneas de detalle de esta factura
    $stmtDet = $conexion->prepare(
        "SELECT d.Referencia, d.Unidades, r.Descripcion, r.Importe, r.Ganancia
         FROM Detalle_Factura d JOIN Repuestos r ON d.Referencia = r.Referencia
         WHERE d.Numero_Factura = :nf"
    );
    $stmtDet->bindParam(':nf', $numero_factura, PDO::PARAM_STR);
    $stmtDet->execute();
    $detalles = $stmtDet->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Detalles de la factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center">Detalles de la factura</h1>
        <a class="btn btn-edit" href="modificar_facturas_lista.php?id=<?php echo urlencode($fila['Numero_Factura']); ?>">Editar factura</a>
        <form method="post" action="eliminar_facturas_lista.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta factura?');">
            <input type="hidden" name="numero_factura" value="<?php echo htmlspecialchars($fila['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" name="eliminar_individual" value="1" class="btn btn-danger">Eliminar factura</button>
        </form>
        <table>
            <tr>
                <th>Número de factura</th><th>Matrícula</th><th>Mano de obra (h)</th><th>Precio/hora</th>
                <th>Fecha emisión</th><th>Fecha pago</th><th>Base imponible</th><th>IVA</th><th>Total</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($fila['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Matricula'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><?php echo htmlspecialchars($fila['Mano_Obra'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo number_format((float)$fila['Precio_Hora'], 2); ?> €</td>
                <td><?php echo htmlspecialchars($fila['Fecha_Emision'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($fila['Fecha_Pago'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo number_format((float)$fila['Base_Imponible'], 2); ?> €</td>
                <td><?php echo number_format((float)$fila['IVA'], 2); ?> €</td>
                <td><b><?php echo number_format((float)$fila['Total'], 2); ?> €</b></td>
            </tr>
        </table>

        <h2 class="mt-section">Líneas de detalle (repuestos)</h2>
        <?php if (empty($detalles)) { ?>
            <p>Esta factura no tiene líneas de repuestos (solo mano de obra).</p>
        <?php } else { ?>
        <table>
            <tr><th>Referencia</th><th>Descripción</th><th>Unidades</th><th>Importe unitario</th><th>Ganancia</th><th>Subtotal</th></tr>
            <?php foreach ($detalles as $d) {
                $subtotal = $d['Importe'] * $d['Unidades'];
                $subtotal += $subtotal * $d['Ganancia'] / 100;
            ?>
            <tr>
                <td><a href="listar_repuestos2.php?id=<?php echo $d['Referencia']; ?>"><?php echo $d['Referencia']; ?></a></td>
                <td><?php echo htmlspecialchars($d['Descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $d['Unidades']; ?></td>
                <td><?php echo number_format((float)$d['Importe'], 2); ?> €</td>
                <td><?php echo htmlspecialchars($d['Ganancia'], ENT_QUOTES, 'UTF-8'); ?> %</td>
                <td><?php echo number_format($subtotal, 2); ?> €</td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>
</body>
</html>
