<?php
    include("auth.php");
    include("conexionPDO.php");

    $referencia = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT * FROM Repuestos WHERE Referencia = :referencia";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':referencia', $referencia, PDO::PARAM_INT);
    $consulta->execute();
    $fila = $consulta->fetch();

    if (!$fila) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Repuesto no encontrado.</p>";
        exit();
    }

    $referenciaOut = $fila['Referencia'];
    $descripcion = htmlspecialchars($fila['Descripcion'], ENT_QUOTES, 'UTF-8');
    $importe = htmlspecialchars($fila['Importe'], ENT_QUOTES, 'UTF-8');
    $ganancia = htmlspecialchars($fila['Ganancia'], ENT_QUOTES, 'UTF-8');
    $tieneFoto = !empty($fila['Fotografia']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Detalles del repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center">Detalles del repuesto</h1>
        <a class="btn btn-primary" href="modificar_repuestos_lista.php?id=<?php echo $referenciaOut; ?>">Editar repuesto</a>
        <form method="post" action="eliminar_repuestos_lista.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este repuesto?');">
            <button type="submit" name="referencia_individual" value="<?php echo $referenciaOut; ?>" class="btn btn-danger">Eliminar repuesto</button>
        </form>
        <table>
            <tr><th>Referencia</th><th>Descripción</th><th>Importe (€)</th><th>Ganancia (%)</th><th>Fotografía</th></tr>
            <tr>
                <td><?php echo $referenciaOut; ?></td>
                <td><?php echo $descripcion; ?></td>
                <td><?php echo number_format((float)$importe, 2); ?></td>
                <td><?php echo $ganancia; ?></td>
                <td>
                    <?php if ($tieneFoto) { ?>
                        <a href="foto.php?tabla=repuesto&id=<?php echo $referenciaOut; ?>" target="_blank">
                            <img src="foto.php?tabla=repuesto&id=<?php echo $referenciaOut; ?>" alt="Foto">
                        </a>
                    <?php } else { echo "Sin foto"; } ?>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
