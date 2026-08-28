<?php
    // Control de acceso mediante sesiones.
    // Este fichero debe incluirse en la PRIMERA línea de cualquier página que
    // requiera estar logueado, antes de imprimir cualquier salida HTML.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['username'])) {
        // No hay sesión iniciada: no se permite acceder a ninguna
        // funcionalidad de la aplicación por URL directa.
        header("Location: login-form.php");
        exit();
    }
?>
