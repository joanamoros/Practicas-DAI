<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Introducir clientes</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Nuevo cliente</center></h1>
    <p style="text-align:center;color:#666;">El ID de cliente se asigna automáticamente al guardar.</p>
    <form method="post" action="introducir_clientes2.php" enctype="multipart/form-data">
        <p>DNI: <input type="text" name="dni" maxlength="9" required></p>
        <p>Nombre: <input type="text" name="nombre" maxlength="15" required></p>
        <p>Primer apellido: <input type="text" name="apellido1" maxlength="15" required></p>
        <p>Segundo apellido: <input type="text" name="apellido2" maxlength="15" required></p>
        <p>Dirección: <input type="text" name="direccion" maxlength="50" required></p>
        <p>C.P.: <input type="text" name="cp" maxlength="5" required></p>
        <p>Población: <input type="text" name="poblacion" maxlength="15" required></p>
        <p>Provincia: <input type="text" name="provincia" maxlength="15" required></p>
        <p>Teléfono: <input type="text" name="telefono" maxlength="9" required></p>
        <p>E-mail: <input type="text" name="email" maxlength="30" required></p>
        <p>Fotografía: <input type="file" name="foto" accept="image/*"></p>
        <p class="text-center"><input type="submit" value="Introducir cliente">&nbsp;<input type="reset" value="Borrar"></p>
    </form>
</body>
</html>