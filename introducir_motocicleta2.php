<?php
    include("auth.php");
    include("conexionPDO.php");

    $id_cliente = intval($_POST["id_cliente"] ?? 0);
    $matricula = trim($_POST["matricula"] ?? '');
    $marca = trim($_POST["marca"] ?? '');
    $modelo = trim($_POST["modelo"] ?? '');
    $anyo = intval($_POST["anyo"] ?? 0);
    $color = trim($_POST["color"] ?? '');

    $mensaje = '';
    $ok = false;

    try {
        // El dueño debe existir ya en Clientes (comprobación de integridad).
        $stmtCliente = $conexion->prepare("SELECT Id_Cliente FROM Clientes WHERE Id_Cliente = ?");
        $stmtCliente->execute([$id_cliente]);
        if (!$stmtCliente->fetch()) {
            $mensaje = "El cliente indicado no existe. No se puede crear la moto.";
        } elseif ($matricula === '') {
            $mensaje = "La matrícula es obligatoria.";
        } else {
            $sql = "INSERT INTO Motocicletas (Matricula, Marca, Modelo, Anyo, Color, Id_Cliente)
                    VALUES (:matricula, :marca, :modelo, :anyo, :color, :id_cliente)";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':matricula', $matricula);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':anyo', $anyo, PDO::PARAM_INT);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
            $stmt->execute();
            $ok = true;
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $mensaje = "Ya existe una motocicleta con esa matrícula.";
        } else {
            $mensaje = "Error al añadir la moto: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Nueva motocicleta</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">La moto se ha agregado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_motocicletas.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de motos</a>
    </div>
</body>
</html>
