<?php
    include("auth.php");
    include("conexionPDO.php");
    include("subir_foto.php");

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

    $foto = leerFotoSubida('foto');
    if ($foto === null) { $foto = ''; }

    $mensaje = '';
    $ok = false;

    if ($dni === '' || $nombre === '' || $apellido1 === '' || $apellido2 === '') {
        $mensaje = "Faltan campos obligatorios (DNI, Nombre y Apellidos).";
    } else {
        try {
            $sql = "INSERT INTO Clientes (DNI, Nombre, Apellido1, Apellido2, Direccion, CP, Poblacion, Provincia, Telefono, Email, Fotografia)
                    VALUES (:dni, :nombre, :apellido1, :apellido2, :direccion, :cp, :poblacion, :provincia, :telefono, :email, :foto)";
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
            $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
            $stmt->execute();
            $ok = true;
            $nuevoId = $conexion->lastInsertId();
        } catch (PDOException $e) {
            $mensaje = "Error al añadir el cliente: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Nuevo cliente</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top: 20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El cliente se ha agregado correctamente (Id_Cliente = <?php echo htmlspecialchars($nuevoId, ENT_QUOTES, 'UTF-8'); ?>).</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
            <p><a href="javascript:history.back()">Volver al formulario</a></p>
        <?php } ?>
        <br>
        <a href="listar_clientes.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de clientes</a>
    </div>
</body>
</html>
