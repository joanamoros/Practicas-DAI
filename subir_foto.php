<?php
    // Lee el contenido binario de una fotografía subida por un formulario
    // (campo <input type="file">) para guardarla tal cual en un BLOB.
    // No depende de la librería GD, por lo que funciona con cualquier
    // formato de imagen (jpg, png, gif, webp...).
    function leerFotoSubida($campo) {
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK && !empty($_FILES[$campo]['tmp_name'])) {
            $contenido = file_get_contents($_FILES[$campo]['tmp_name']);
            return $contenido !== false ? $contenido : null;
        }
        return null;
    }
?>
