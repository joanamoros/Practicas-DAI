<?php
    include("auth.php");
    include("conexionPDO.php");

    $referencia = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT * FROM Repuestos WHERE Referencia = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $referencia, PDO::PARAM_INT);
    $stmt->execute();
    $repuesto = $stmt->fetch();

    if (!$repuesto) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Repuesto no encontrado.</p>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Modificar repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Modificar repuesto</center></h1>
    <form method="post" action="modificar_repuestos_lista2.php" enctype="multipart/form-data">
        <input type="hidden" name="referencia" value="<?php echo $repuesto['Referencia']; ?>">
        <p>Referencia: <input type="text" value="<?php echo $repuesto['Referencia']; ?>" readonly></p>
        <p>Descripción: <input type="text" name="descripcion" maxlength="30" required value="<?php echo htmlspecialchars($repuesto['Descripcion'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Importe (€): <input type="number" step="0.01" min="0" name="importe" required value="<?php echo htmlspecialchars($repuesto['Importe'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Ganancia (%): <input type="number" min="0" max="100" name="ganancia" required value="<?php echo htmlspecialchars($repuesto['Ganancia'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <?php if (!empty($repuesto['Fotografia'])) { ?>
        <p>Foto actual:<br><img src="foto.php?tabla=repuesto&id=<?php echo $repuesto['Referencia']; ?>" style="max-width:120px;max-height:120px;"></p>
        <?php } ?>
        <p>Nueva fotografía (opcional): <input type="file" name="foto" accept="image/*"></p>
        <p class="text-center"><input type="submit" value="Modificar repuesto" class="btn lila-button">&nbsp;<input type="reset" value="Borrar"></p>
    </form>
</body>
</html>
