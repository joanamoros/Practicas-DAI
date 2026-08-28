<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal</title>
</head>
<body>
    <div class="app-topbar">
        <span class="app-topbar-brand">Taller de motocicletas</span>
        <div class="app-topbar-right">
            <span class="app-topbar-user">Usuario: <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></span>
            <a href="logout.php" class="app-topbar-logout">Cerrar sesión</a>
        </div>
    </div>

    <div class="container">
        <h1 class="text-center">Menú principal</h1>

        <div class="menu-search-row">
            <a href="buscar_motocicleta.php" class="btn btn-secondary">Buscar motocicleta</a>
            <a href="menu_buscar_factura.php" class="btn btn-secondary">Buscar factura</a>
        </div>

        <div class="card menu-card">
            <div class="menu-links">
                <a href="listar_clientes.php">Clientes</a>
                <a href="listar_motocicletas.php">Motocicletas</a>
                <a href="listar_repuestos.php">Repuestos</a>
                <a href="listar_facturas.php">Facturas</a>
            </div>
        </div>
    </div>
</body>
</html>