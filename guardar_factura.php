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

    $ok = false;
    $mensaje = '';

    try {
        // Revalidamos todo de nuevo por seguridad (nunca confiar solo en
        // la página de confirmación anterior).
        $stmtMoto = $conexion->prepare("SELECT Matricula FROM Motocicletas WHERE Matricula = ?");
        $stmtMoto->execute([$matricula]);
        if (!$stmtMoto->fetch()) {
            throw new Exception("La motocicleta indicada no existe.");
        }
        if ($numero_factura === '') {
            throw new Exception("El número de factura es obligatorio.");
        }

        $resultadoCalculo = calcularFactura($conexion, $detalles, $mano_obra, $precio_hora);
        if ($resultadoCalculo === null && !empty($detalles)) {
            throw new Exception("Alguna referencia de repuesto no existe en la base de datos.");
        }
        if ($resultadoCalculo === null) {
            // Sin líneas de detalle: solo mano de obra.
            $resultadoCalculo = calcularFactura($conexion, array(), $mano_obra, $precio_hora);
        }

        $conexion->beginTransaction();

        $sqlFactura = "INSERT INTO Facturas (Numero_Factura, Matricula, Mano_Obra, Precio_Hora, Fecha_Emision, Fecha_Pago, Base_Imponible, IVA, Total)
                        VALUES (:nf, :matricula, :mano_obra, :precio_hora, :f_emision, :f_pago, :base, :iva, :total)";
        $stmtFactura = $conexion->prepare($sqlFactura);
        $stmtFactura->execute(array(
            ':nf' => $numero_factura,
            ':matricula' => $matricula,
            ':mano_obra' => $mano_obra,
            ':precio_hora' => $precio_hora,
            ':f_emision' => $fecha_emision,
            ':f_pago' => $fecha_pago,
            ':base' => $resultadoCalculo['base_imponible'],
            ':iva' => $resultadoCalculo['iva'],
            ':total' => $resultadoCalculo['total'],
        ));

        $sqlDetalle = "INSERT INTO Detalle_Factura (Numero_Factura, Referencia, Unidades) VALUES (:nf, :ref, :unidades)";
        $stmtDetalle = $conexion->prepare($sqlDetalle);
        foreach ($detalles as $d) {
            $stmtDetalle->execute(array(
                ':nf' => $numero_factura,
                ':ref' => $d['referencia'],
                ':unidades' => $d['unidades'],
            ));
        }

        $conexion->commit();
        $ok = true;
    } catch (Exception $e) {
        if ($conexion->inTransaction()) { $conexion->rollBack(); }
        if ($e instanceof PDOException && $e->getCode() == 23000) {
            $mensaje = "Ya existe una factura con ese número.";
        } else {
            $mensaje = "No se ha podido guardar la factura: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Factura guardada</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">La factura y sus líneas de detalle se han guardado correctamente.</div>
            <br>
            <a href="listar_facturas2.php?id=<?php echo urlencode($numero_factura); ?>" style="background-color:#007bff;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Ver factura</a>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_facturas.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de facturas</a>
    </div>
</body>
</html>
