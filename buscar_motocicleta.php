<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Buscar motocicleta</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="buscar-wrap">
    <form method="GET" action="buscar_motocicleta2.php">
    <h2><center>Buscar motocicleta</center></h2><br>
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula"><br>

        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca"><br>

        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo"><br>

        <label for="anyo">Año:</label>
        <input type="text" id="anyo" name="anyo"><br>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color"><br><br>

        <div class="text-center">
            <input type="submit" value="Buscar">
        </div>
    </form>
    </div>
</body>
</html>