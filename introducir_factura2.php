<?php
    include("auth.php");
    include("conexionPDO.php");
    include("factura_calculo.php");

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: seleccionar_motocicleta_factura.php");
        exit();
    }

    $matricula = $_POST["matricula"] ?? '';
    $numero_factura = trim($_POST["numero_factura"] ?? '');
    $mano_obra = intval($_POST["mano_obra"] ?? 0);
    $precio_hora = floatval($_POST["precio_hora"] ?? 0);
    $fecha_emision = $_POST["fecha_emision"] ?? '';
    $fecha_pago = $_POST["fecha_pago"] ?? '';
    $detalles = extraerDetallesPost($_POST);

    $errores = array();

    // La motocicleta debe existir.
    $stmtMoto = $conexion->prepare("SELECT Matricula, Marca, Modelo FROM Motocicletas WHERE Matricula = ?");
    $stmtMoto->execute([$matricula]);
    $moto = $stmtMoto->fetch();
    if (!$moto) {
        $errores[] = "La motocicleta indicada no existe.";
    }

    if ($numero_factura === '') {
        $errores[] = "El número de factura es obligatorio.";
    } else {
        $stmtDup = $conexion->prepare("SELECT COUNT(*) FROM Facturas WHERE Numero_Factura = ?");
        $stmtDup->execute([$numero_factura]);
        if ($stmtDup->fetchColumn() > 0) {
            $errores[] = "Ya existe una factura con el número '" . htmlspecialchars($numero_factura, ENT_QUOTES, 'UTF-8') . "'.";
        }
    }

    if ($fecha_emision === '' || $fecha_pago === '') {
        $errores[] = "Las fechas de emisión y de pago son obligatorias.";
    }

    $resultadoCalculo = null;
    if (empty($errores)) {
        $resultadoCalculo = calcularFactura($conexion, $detalles, $mano_obra, $precio_hora);
        if ($resultadoCalculo === null && !empty($detalles)) {
            $errores[] = "Alguna de las referencias de repuesto indicadas no existe en la base de datos.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Confirmar factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Confirmar nueva factura</b></h1>

        <?php if (!empty($errores)) { ?>
            <div class="error-box">
                <ul style="text-align:left;">
                    <?php foreach ($errores as $err) { echo "<li>" . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "</li>"; } ?>
                </ul>
            </div>
            <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
        <?php } else { ?>
            <p>Moto: <b><?php echo htmlspecialchars($moto['Marca'] . ' ' . $moto['Modelo'] . ' (' . $moto['Matricula'] . ')', ENT_QUOTES, 'UTF-8'); ?></b>
               &nbsp;|&nbsp; Nº Factura: <b><?php echo htmlspecialchars($numero_factura, ENT_QUOTES, 'UTF-8'); ?></b></p>

            <table class="table table-bordered">
                <thead><tr><th>Repuesto</th><th>Unidades</th><th>Importe unitario</th><th>Ganancia</th><th>Subtotal</th></tr></thead>
                <tbody>
                <?php if (empty($resultadoCalculo['lineas'])) { ?>
                    <tr><td colspan="5">Sin líneas de repuestos (solo mano de obra).</td></tr>
                <?php } ?>
                <?php foreach ($resultadoCalculo['lineas'] as $l) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($l['descripcion'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo $l['unidades']; ?></td>
                        <td><?php echo number_format($l['importe_unitario'], 2); ?> €</td>
                        <td><?php echo $l['ganancia']; ?> %</td>
                        <td><?php echo number_format($l['subtotal'], 2); ?> €</td>
                    </tr>
                <?php } ?>
                    <tr><td colspan="4">Mano de obra (<?php echo $mano_obra; ?> h x <?php echo number_format($precio_hora, 2); ?> €/h)</td><td><?php echo number_format($resultadoCalculo['montante_mano_obra'], 2); ?> €</td></tr>
                </tbody>
                <tfoot class="totales">
                    <tr><td colspan="4">Base imponible</td><td><?php echo number_format($resultadoCalculo['base_imponible'], 2); ?> €</td></tr>
                    <tr><td colspan="4">IVA (21%)</td><td><?php echo number_format($resultadoCalculo['iva'], 2); ?> €</td></tr>
                    <tr><td colspan="4">TOTAL</td><td><?php echo number_format($resultadoCalculo['total'], 2); ?> €</td></tr>
                </tfoot>
            </table>

            <form method="post" action="guardar_factura.php">
                <input type="hidden" name="matricula" value="<?php echo htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="numero_factura" value="<?php echo htmlspecialchars($numero_factura, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="mano_obra" value="<?php echo $mano_obra; ?>">
                <input type="hidden" name="precio_hora" value="<?php echo $precio_hora; ?>">
                <input type="hidden" name="fecha_emision" value="<?php echo htmlspecialchars($fecha_emision, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="fecha_pago" value="<?php echo htmlspecialchars($fecha_pago, ENT_QUOTES, 'UTF-8'); ?>">
                <?php foreach ($detalles as $i => $d) { ?>
                    <input type="hidden" name="detalles_factura[<?php echo $i; ?>][referencia]" value="<?php echo $d['referencia']; ?>">
                    <input type="hidden" name="detalles_factura[<?php echo $i; ?>][unidades]" value="<?php echo $d['unidades']; ?>">
                <?php } ?>
                <a href="javascript:history.back()" class="btn btn-secondary">Volver a editar</a>
                <button type="submit" class="btn btn-primary">Confirmar y guardar factura</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
