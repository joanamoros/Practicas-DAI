<?php
    include("auth.php");
    include("conexionPDO.php");

    $matriculaOriginal = trim($_POST["matricula_original"] ?? '');
    $matricula = trim($_POST["matricula"] ?? '');
    $marca = trim($_POST["marca"] ?? '');
    $modelo = trim($_POST["modelo"] ?? '');
    $anyo = intval($_POST["anyo"] ?? 0);
    $color = trim($_POST["color"] ?? '');

    $mensaje = '';
    $ok = false;

    try {
        $conexion->beginTransaction();

        // Si el usuario ha cambiado la matrícula, actualizamos también las
        // facturas que hacían referencia a la matrícula anterior, para no
        // dejar registros huérfanos (Facturas.Matricula -> Motocicletas.Matricula).
        if ($matricula !== $matriculaOriginal) {
            $stmtFact = $conexion->prepare("UPDATE Facturas SET Matricula = :nueva WHERE Matricula = :vieja");
            $stmtFact->bindParam(':nueva', $matricula);
            $stmtFact->bindParam(':vieja', $matriculaOriginal);
            $stmtFact->execute();
        }

        $sql = "UPDATE Motocicletas SET Matricula=:matricula, Marca=:marca, Modelo=:modelo, Anyo=:anyo, Color=:color
                WHERE Matricula=:matricula_original";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':matricula', $matricula);
        $stmt->bindParam(':marca', $marca);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':anyo', $anyo, PDO::PARAM_INT);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':matricula_original', $matriculaOriginal);
        $stmt->execute();

        $conexion->commit();
        $ok = true;
    } catch (PDOException $e) {
        if ($conexion->inTransaction()) { $conexion->rollBack(); }
        if ($e->getCode() == 23000) {
            $mensaje = "Ya existe otra motocicleta con esa matrícula.";
        } else {
            $mensaje = "Error al modificar la moto: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Modificar moto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">La moto se ha modificado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_motocicletas.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de motos</a>
    </div>
</body>
</html>
