<?php
    session_start();
    // Si ya hay una sesión activa, vamos directos al menú.
    if (isset($_SESSION['username'])) {
        header("Location: menu.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Iniciar sesión</title>
</head>
<body class="login-page">
    <div class="login-card">
        <div class="login-brand">Taller de Motocicletas</div>
        <?php if (isset($_SESSION['error'])) { ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['error']); ?></p>
        <?php } ?>
        <form action="login.php" method="POST">
            <p><label for="username">Usuario</label>
            <input type="text" id="username" name="username" required autofocus></p>
            <p><label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required></p>
            <input type="submit" value="Iniciar Sesión">
        </form>
    </div>
</body>
</html>