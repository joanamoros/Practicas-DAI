<?php
    include("auth.php");
    include("conexionPDO.php");
    include("subir_foto.php");

    $idCliente = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
    $dni = trim($_POST["dni"] ?? '');
    $nombre = trim($_POST["nombre"] ?? '');
    $apellido1 = trim($_POST["apellido1"] ?? '');
    $apellido2 = trim($_POST["apellido2"] ?? '');
    $direccion = trim($_POST["direccion"] ?? '');
    $cp = trim($_POST["cp"] ?? '');
    $poblacion = trim($_POST["poblacion"] ?? '');
    $provincia = trim($_POST["provincia"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $email = trim($_POST["email"] ?? '');

    $nuevaFoto = leerFotoSubida('foto');

    $mensaje = '';
    $ok = false;

    try {
        if ($nuevaFoto !== null) {
            $sql = "UPDATE Clientes SET DNI=:dni, Nombre=:nombre, Apellido1=:apellido1, Apellido2=:apellido2,
                    Direccion=:direccion, CP=:cp, Poblacion=:poblacion, Provincia=:provincia,
                    Telefono=:telefono, Email=:email, Fotografia=:foto WHERE Id_Cliente=:id";
        } else {
            $sql = "UPDATE Clientes SET DNI=:dni, Nombre=:nombre, Apellido1=:apellido1, Apellido2=:apellido2,
                    Direccion=:direccion, CP=:cp, Poblacion=:poblacion, Provincia=:provincia,
                    Telefono=:telefono, Email=:email WHERE Id_Cliente=:id";
        }
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido1', $apellido1);
        $stmt->bindParam(':apellido2', $apellido2);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':cp', $cp);
        $stmt->bindParam(':poblacion', $poblacion);
        $stmt->bindParam(':provincia', $provincia);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        if ($nuevaFoto !== null) {
            $stmt->bindParam(':foto', $nuevaFoto, PDO::PARAM_LOB);
        }
        $stmt->bindParam(':id', $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        $ok = true;
    } catch (PDOException $e) {
        $mensaje = "Error al modificar el cliente: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Modificar cliente</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El cliente se ha modificado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_clientes2.php?id=<?php echo $idCliente; ?>" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Ver ficha del cliente</a>
        <a href="listar_clientes.php" style="background-color:#007bff;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado</a>
    </div>
</body>
</html>
