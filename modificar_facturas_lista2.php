<?php
    include("auth.php");
    include("conexionPDO.php");
    include("factura_calculo.php");

    $numero_factura = trim($_POST["numero_factura"] ?? '');
    $mano_obra = intval($_POST["mano_obra"] ?? 0);
    $precio_hora = floatval($_POST["precio_hora"] ?? 0);
    $fecha_emision = $_POST["fecha_emision"] ?? '';
    $fecha_pago = $_POST["fecha_pago"] ?? '';
    $lineasAEliminar = isset($_POST['eliminar_linea']) ? $_POST['eliminar_linea'] : array();
    $nuevasLineas = extraerDetallesPost($_POST);

    $ok = false;
    $mensaje = '';

    try {
        $conexion->beginTransaction();

        // 1) Eliminar las líneas marcadas.
        if (!empty($lineasAEliminar)) {
            $stmtDel = $conexion->prepare("DELETE FROM Detalle_Factura WHERE Id_Det_Factura = ? AND Numero_Factura = ?");
            foreach ($lineasAEliminar as $idLinea) {
                $stmtDel->execute([intval($idLinea), $numero_factura]);
            }
        }

        // 2) Insertar las líneas nuevas (validando que la referencia existe).
        foreach ($nuevasLineas as $d) {
            $stmtRep = $conexion->prepare("SELECT Referencia FROM Repuestos WHERE Referencia = ?");
            $stmtRep->execute([$d['referencia']]);
            if (!$stmtRep->fetch()) {
                throw new Exception("La referencia de repuesto " . $d['referencia'] . " no existe.");
            }
            $stmtIns = $conexion->prepare("INSERT INTO Detalle_Factura (Numero_Factura, Referencia, Unidades) VALUES (?, ?, ?)");
            $stmtIns->execute([$numero_factura, $d['referencia'], $d['unidades']]);
        }

        // 3) Recalcular los totales a partir de TODAS las líneas actuales
        //    de la factura (las que ya había menos las eliminadas, más las nuevas).
        $stmtActuales = $conexion->prepare("SELECT Referencia, Unidades FROM Detalle_Factura WHERE Numero_Factura = ?");
        $stmtActuales->execute([$numero_factura]);
        $detallesActuales = $stmtActuales->fetchAll();
        $detallesParaCalculo = array();
        foreach ($detallesActuales as $d) {
            $detallesParaCalculo[] = array('referencia' => $d['Referencia'], 'unidades' => $d['Unidades']);
        }

        $resultadoCalculo = calcularFactura($conexion, $detallesParaCalculo, $mano_obra, $precio_hora);
        if ($resultadoCalculo === null) {
            throw new Exception("No se han podido recalcular los totales de la factura.");
        }

        // 4) Actualizar la cabecera de la factura.
        $sql = "UPDATE Facturas SET Mano_Obra=:mano_obra, Precio_Hora=:precio_hora, Fecha_Emision=:f_emision,
                Fecha_Pago=:f_pago, Base_Imponible=:base, IVA=:iva, Total=:total WHERE Numero_Factura=:nf";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(array(
            ':mano_obra' => $mano_obra,
            ':precio_hora' => $precio_hora,
            ':f_emision' => $fecha_emision,
            ':f_pago' => $fecha_pago,
            ':base' => $resultadoCalculo['base_imponible'],
            ':iva' => $resultadoCalculo['iva'],
            ':total' => $resultadoCalculo['total'],
            ':nf' => $numero_factura,
        ));

        $conexion->commit();
        $ok = true;
    } catch (Exception $e) {
        if ($conexion->inTransaction()) { $conexion->rollBack(); }
        $mensaje = "Error al modificar la factura: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Modificar factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">La factura se ha modificado correctamente y sus totales se han recalculado.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_facturas2.php?id=<?php echo urlencode($numero_factura); ?>" style="background-color:#007bff;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Ver factura</a>
        <a href="listar_facturas.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado</a>
    </div>
</body>
</html>
