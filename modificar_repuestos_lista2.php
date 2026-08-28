<?php
    include("auth.php");
    include("conexionPDO.php");
    include("subir_foto.php");

    $referencia = intval($_POST["referencia"] ?? 0);
    $descripcion = trim($_POST["descripcion"] ?? '');
    $importe = floatval($_POST["importe"] ?? 0);
    $ganancia = intval($_POST["ganancia"] ?? 0);

    $nuevaFoto = leerFotoSubida('foto');

    $mensaje = '';
    $ok = false;

    try {
        if ($nuevaFoto !== null) {
            $sql = "UPDATE Repuestos SET Descripcion=:descripcion, Importe=:importe, Ganancia=:ganancia, Fotografia=:foto WHERE Referencia=:referencia";
        } else {
            $sql = "UPDATE Repuestos SET Descripcion=:descripcion, Importe=:importe, Ganancia=:ganancia WHERE Referencia=:referencia";
        }
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':importe', $importe);
        $stmt->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
        if ($nuevaFoto !== null) {
            $stmt->bindParam(':foto', $nuevaFoto, PDO::PARAM_LOB);
        }
        $stmt->bindParam(':referencia', $referencia, PDO::PARAM_INT);
        $stmt->execute();
        $ok = true;
    } catch (PDOException $e) {
        $mensaje = "Error al modificar el repuesto: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Modificar repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El repuesto se ha modificado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_repuestos.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de repuestos</a>
    </div>
</body>
</html>
