<?php
    include("auth.php");
    include("conexionPDO.php");

    $id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT * FROM Clientes WHERE Id_Cliente = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $id_cliente, PDO::PARAM_INT);
    $stmt->execute();
    $cliente = $stmt->fetch();

    if (!$cliente) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Cliente no encontrado.</p>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Modificar cliente</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Modificar cliente</center></h1>
    <form method="post" action="modificar_clientes_lista2.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $cliente['Id_Cliente']; ?>">
        <p>ID Cliente: <input type="text" value="<?php echo $cliente['Id_Cliente']; ?>" readonly></p>
        <p>DNI: <input type="text" name="dni" maxlength="9" required value="<?php echo htmlspecialchars($cliente['DNI'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Nombre: <input type="text" name="nombre" maxlength="15" required value="<?php echo htmlspecialchars($cliente['Nombre'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Primer apellido: <input type="text" name="apellido1" maxlength="15" required value="<?php echo htmlspecialchars($cliente['Apellido1'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Segundo apellido: <input type="text" name="apellido2" maxlength="15" required value="<?php echo htmlspecialchars($cliente['Apellido2'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Dirección: <input type="text" name="direccion" maxlength="50" required value="<?php echo htmlspecialchars($cliente['Direccion'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>C.P.: <input type="text" name="cp" maxlength="5" required value="<?php echo htmlspecialchars($cliente['CP'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Población: <input type="text" name="poblacion" maxlength="15" required value="<?php echo htmlspecialchars($cliente['Poblacion'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Provincia: <input type="text" name="provincia" maxlength="15" required value="<?php echo htmlspecialchars($cliente['Provincia'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>Teléfono: <input type="text" name="telefono" maxlength="9" required value="<?php echo htmlspecialchars($cliente['Telefono'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <p>E-mail: <input type="text" name="email" maxlength="30" required value="<?php echo htmlspecialchars($cliente['Email'], ENT_QUOTES, 'UTF-8'); ?>"></p>
        <?php if (!empty($cliente['Fotografia'])) { ?>
        <p>Foto actual:<br><img src="foto.php?tabla=cliente&id=<?php echo $cliente['Id_Cliente']; ?>" style="max-width:120px;max-height:120px;"></p>
        <?php } ?>
        <p>Nueva fotografía (opcional): <input type="file" name="foto" accept="image/*"></p>
        <p class="text-center"><input type="submit" value="Modificar cliente">&nbsp;<input type="reset" value="Borrar"></p>
    </form>
</body>
</html>