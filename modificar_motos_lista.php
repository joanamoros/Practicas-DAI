<?php
    include("auth.php");
    include("conexionPDO.php");

    $matricula = isset($_GET['matricula']) ? $_GET['matricula'] : '';

    $sql = "SELECT * FROM Motocicletas WHERE Matricula = :matricula";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
    $stmt->execute();
    $moto = $stmt->fetch();

    if (!$moto) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Moto no encontrada.</p>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Modificar moto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Modificar moto</center></h1>
    <form method="post" action="modificar_motos_lista2.php">
        <input type="hidden" name="matricula_original" value="<?php echo htmlspecialchars($moto['Matricula'], ENT_QUOTES, 'UTF-8'); ?>">
        <p>Matrícula: <input type="text" name="matricula" maxlength="7" required value="<?php echo htmlspecialchars($moto['Matricula'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Marca: <input type="text" name="marca" maxlength="30" required value="<?php echo htmlspecialchars($moto['Marca'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Modelo: <input type="text" name="modelo" maxlength="30" required value="<?php echo htmlspecialchars($moto['Modelo'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Año: <input type="number" name="anyo" min="1900" max="2100" required value="<?php echo htmlspecialchars($moto['Anyo'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Color: <input type="text" name="color" maxlength="15" required value="<?php echo htmlspecialchars($moto['Color'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p class="text-center"><input type="submit" value="Modificar moto">&nbsp;<input type="reset" value="Borrar"></p>
    </form>
</body>
</html>
