<?php
    include("auth.php");
    include("conexionPDO.php");

    // No permitimos eliminar un repuesto que ya se ha usado en alguna
    // factura, para no corromper el histórico de facturación.
    function repuestoEnUso($conexion, $referencia) {
        $stmt = $conexion->prepare("SELECT COUNT(*) FROM Detalle_Factura WHERE Referencia = ?");
        $stmt->execute([$referencia]);
        return $stmt->fetchColumn() > 0;
    }

    $ok = false;
    $mensaje = '';
    $referencias = [];

    if (isset($_POST['eliminar']) && isset($_POST["borrar"])) {
        $referencias = $_POST["borrar"];
    } elseif (isset($_POST['referencia_individual'])) {
        $referencias = [$_POST['referencia_individual']];
    }

    if (empty($referencias)) {
        $mensaje = "No se han seleccionado repuestos para eliminar.";
    } else {
        try {
            $conexion->beginTransaction();
            $enUso = [];
            foreach ($referencias as $referencia) {
                if (repuestoEnUso($conexion, intval($referencia))) {
                    $enUso[] = $referencia;
                } else {
                    $conexion->prepare("DELETE FROM Repuestos WHERE Referencia = ?")->execute([intval($referencia)]);
                }
            }
            $conexion->commit();
            if (empty($enUso)) {
                $ok = true;
            } else {
                $mensaje = "El/los repuesto(s) " . htmlspecialchars(implode(', ', $enUso), ENT_QUOTES, 'UTF-8') . " no se puede(n) eliminar porque ya aparece(n) en alguna factura. El resto sí se ha eliminado.";
            }
        } catch (PDOException $e) {
            if ($conexion->inTransaction()) { $conexion->rollBack(); }
            $mensaje = "Error al eliminar los repuestos: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Eliminar repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El/los repuesto(s) se han eliminado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo $mensaje; ?></div>
        <?php } ?>
        <br>
        <a href="listar_repuestos.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de repuestos</a>
    </div>
</body>
</html>
