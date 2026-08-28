<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Buscador de facturas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Buscador de facturas</center></h1>
    <div class="text-center">
        <a href="buscar_factura_fecha.php" class="btn lila-button">Por fecha</a>
        <a href="buscar_factura_cliente.php" class="btn azul-button">Por cliente</a>
    </div>
</body>
</html>