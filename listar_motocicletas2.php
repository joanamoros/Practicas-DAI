<?php
    include("auth.php");
    include("conexionPDO.php");

    $matricula = isset($_GET['matricula']) ? $_GET['matricula'] : '';

    if ($matricula === '') {
        include("topbar.php");
        echo "<p style='text-align:center;'>Matrícula no proporcionada.</p>";
        exit();
    }

    $sql = "SELECT m.*, c.Nombre, c.Apellido1, c.Apellido2 FROM Motocicletas m
            JOIN Clientes c ON m.Id_Cliente = c.Id_Cliente
            WHERE m.Matricula = :matricula";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':matricula', $matricula, PDO::PARAM_STR);
    $consulta->execute();
    $fila = $consulta->fetch();

    if (!$fila) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Moto no encontrada.</p>";
        exit();
    }

    $matriculaOut = htmlspecialchars($fila['Matricula'], ENT_QUOTES, 'UTF-8');
    $marca = htmlspecialchars($fila['Marca'], ENT_QUOTES, 'UTF-8');
    $modelo = htmlspecialchars($fila['Modelo'], ENT_QUOTES, 'UTF-8');
    $anyo = htmlspecialchars($fila['Anyo'], ENT_QUOTES, 'UTF-8');
    $color = htmlspecialchars($fila['Color'], ENT_QUOTES, 'UTF-8');
    $propietario = htmlspecialchars($fila['Nombre'] . ' ' . $fila['Apellido1'] . ' ' . $fila['Apellido2'], ENT_QUOTES, 'UTF-8');

    // Facturas de esta motocicleta
    $stmtF = $conexion->prepare("SELECT Numero_Factura, Fecha_Emision, Total FROM Facturas WHERE Matricula = :m ORDER BY Fecha_Emision DESC");
    $stmtF->bindParam(':m', $fila['Matricula'], PDO::PARAM_STR);
    $stmtF->execute();
    $facturas = $stmtF->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Detalles de la moto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center">Detalles de la moto</h1>
        <a class="btn btn-edit" href="modificar_motos_lista.php?matricula=<?php echo urlencode($fila['Matricula']); ?>">Editar moto</a>
        <form method="post" action="eliminar_motocicletas_lista.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta moto? También se eliminarán sus facturas asociadas.');">
            <input type="hidden" name="matricula_individual" value="<?php echo $matriculaOut; ?>">
            <button type="submit" class="btn btn-delete">Eliminar moto</button>
        </form>
        <table>
            <tr><th>Matrícula</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Color</th><th>Propietario</th></tr>
            <tr>
                <td><?php echo $matriculaOut; ?></td>
                <td><?php echo $marca; ?></td>
                <td><?php echo $modelo; ?></td>
                <td><?php echo $anyo; ?></td>
                <td><?php echo $color; ?></td>
                <td><a href="listar_clientes2.php?id=<?php echo $fila['Id_Cliente']; ?>"><?php echo $propietario; ?></a></td>
            </tr>
        </table>

        <h2 class="mt-section">Facturas de esta motocicleta</h2>
        <?php if (empty($facturas)) { ?>
            <p>No hay facturas registradas para esta moto.</p>
        <?php } else { ?>
        <table>
            <tr><th>Nº Factura</th><th>Fecha Emisión</th><th>Total</th></tr>
            <?php foreach ($facturas as $f) { ?>
            <tr>
                <td><a href="listar_facturas2.php?id=<?php echo urlencode($f['Numero_Factura']); ?>"><?php echo htmlspecialchars($f['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><?php echo htmlspecialchars($f['Fecha_Emision'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo number_format((float)$f['Total'], 2); ?> €</td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>
</body>
</html>
