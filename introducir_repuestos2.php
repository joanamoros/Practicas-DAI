<?php
    include("auth.php");
    include("conexionPDO.php");
    include("subir_foto.php");

    $descripcion = trim($_POST["descripcion"] ?? '');
    $importe = floatval($_POST["importe"] ?? 0);
    $ganancia = intval($_POST["ganancia"] ?? 0);

    $foto = leerFotoSubida('foto');
    if ($foto === null) { $foto = ''; }

    $mensaje = '';
    $ok = false;

    if ($descripcion === '') {
        $mensaje = "La descripción es obligatoria.";
    } else {
        try {
            $sql = "INSERT INTO Repuestos (Descripcion, Importe, Ganancia, Fotografia) VALUES (:descripcion, :importe, :ganancia, :foto)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':importe', $importe);
            $stmt->bindParam(':ganancia', $ganancia, PDO::PARAM_INT);
            $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
            $stmt->execute();
            $ok = true;
            $nuevaReferencia = $conexion->lastInsertId();
        } catch (PDOException $e) {
            $mensaje = "Error al añadir el repuesto: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Nuevo repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El repuesto se ha agregado correctamente (Referencia = <?php echo htmlspecialchars($nuevaReferencia, ENT_QUOTES, 'UTF-8'); ?>).</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_repuestos.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de repuestos</a>
    </div>
</body>
</html>
