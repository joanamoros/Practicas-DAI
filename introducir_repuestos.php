<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Nuevo repuesto</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Nuevo repuesto</center></h1>
    <p style="text-align:center;color:#666;">La referencia se asigna automáticamente al guardar.</p>
    <form method="post" action="introducir_repuestos2.php" enctype="multipart/form-data">
        <p>Descripción: <input type="text" name="descripcion" maxlength="30" required></p>
        <p>Importe (€): <input type="number" step="0.01" min="0" name="importe" required></p>
        <p>Ganancia (%): <input type="number" name="ganancia" min="0" max="100" required></p>
        <p>Fotografía: <input type="file" name="foto" accept="image/*"></p>
        <p class="text-center"><input type="submit" value="Añadir repuesto" class="btn btn-success">&nbsp;<input type="reset" value="Borrar"></p>
    </form>
</body>
</html>
