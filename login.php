<?php
    session_start();

    // Credenciales de acceso a la aplicación. Usuario: admin / Contraseña: 1234
    $USUARIO_VALIDO = "admin";
    $PASSWORD_VALIDO = "1234";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($username === $USUARIO_VALIDO && $password === $PASSWORD_VALIDO) {
            // Regeneramos el id de sesión al loguear para evitar fijación de sesión.
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            header("Location: menu.php");
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";
            header("Location: login-form.php");
        }
        exit();
    } else {
        header("Location: login-form.php");
        exit();
    }
?>
