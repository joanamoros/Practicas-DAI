<?php
    // Barra superior común a todas las páginas protegidas: enlace al menú
    // principal y botón de Cerrar Sesión, siempre visible tras el login.
    // Requiere que auth.php ya se haya incluido antes (sesión iniciada).
    // El estilo viene de estilo.css (sin dependencias externas).
    $__usuario = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : '';
?>
<div class="app-topbar">
    <a href="menu.php" class="app-topbar-brand">Taller de motocicletas</a>
    <div class="app-topbar-right">
        <span class="app-topbar-user">Usuario: <?php echo $__usuario; ?></span>
        <a href="menu.php">&larr; Menú principal</a>
        <a href="logout.php" class="app-topbar-logout">Cerrar sesión</a>
    </div>
</div>