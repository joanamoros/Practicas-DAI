<?php
    include("auth.php");
    include("conexionPDO.php");

    function eliminarFacturaPorNumero($conexion, $numero_factura) {
        $conexion->prepare("DELETE FROM Detalle_Factura WHERE Numero_Factura = ?")->execute([$numero_factura]);
        $conexion->prepare("DELETE FROM Facturas WHERE Numero_Factura = ?")->execute([$numero_factura]);
    }

    $ok = false;
    $mensaje = '';

    try {
        if (isset($_POST['eliminar']) && isset($_POST["borrar"])) {
            $conexion->beginTransaction();
            foreach ($_POST["borrar"] as $numero_factura) {
                eliminarFacturaPorNumero($conexion, $numero_factura);
            }
            $conexion->commit();
            $ok = true;
        } elseif (isset($_POST['eliminar_individual']) && isset($_POST['numero_factura'])) {
            $conexion->beginTransaction();
            eliminarFacturaPorNumero($conexion, $_POST['numero_factura']);
            $conexion->commit();
            $ok = true;
        } else {
            $mensaje = "No se han seleccionado facturas para eliminar.";
        }
    } catch (PDOException $e) {
        if ($conexion->inTransaction()) { $conexion->rollBack(); }
        $mensaje = "Error al eliminar las facturas: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Eliminar factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">La(s) factura(s) se han eliminado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_facturas.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de facturas</a>
    </div>
</body>
</html>
