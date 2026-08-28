<?php
    include("auth.php");
    include("conexionPDO.php");

    $facturas = array();
    $fecha_inicio = '';
    $fecha_fin = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buscar_por_fechas"])) {
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_fin = $_POST["fecha_fin"];

        $sql = "SELECT * FROM Facturas WHERE Fecha_Pago BETWEEN :inicio AND :fin ORDER BY Fecha_Pago";
        $consulta = $conexion->prepare($sql);
        $consulta->execute([':inicio' => $fecha_inicio, ':fin' => $fecha_fin]);
        $facturas = $consulta->fetchAll();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Resultados de búsqueda de facturas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Resultados de búsqueda de facturas</b></h1>
        <h2>Listado de facturas pagadas entre <?php echo htmlspecialchars($fecha_inicio, ENT_QUOTES, 'UTF-8'); ?> y <?php echo htmlspecialchars($fecha_fin, ENT_QUOTES, 'UTF-8'); ?></h2>
        <?php if ($facturas) { ?>
            <table class="table table-bordered">
                <thead><tr><th>Número de factura</th><th>Fecha de emisión</th><th>Fecha de pago</th><th>Total</th></tr></thead>
                <tbody>
                <?php foreach ($facturas as $factura) { ?>
                    <tr>
                        <td><a href="listar_facturas2.php?id=<?php echo urlencode($factura['Numero_Factura']); ?>"><?php echo htmlspecialchars($factura['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                        <td><?php echo htmlspecialchars($factura['Fecha_Emision'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($factura['Fecha_Pago'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format((float)$factura['Total'], 2); ?> €</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No se encontraron facturas en el rango de fechas especificado.</p>
        <?php } ?>
    </div>
</body>
</html>
