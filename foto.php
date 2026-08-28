<?php
    // Sirve las fotografías almacenadas como BLOB directamente desde la base
    // de datos, sin necesidad de escribir ficheros temporales en disco.
    // Uso: foto.php?tabla=cliente&id=5   |   foto.php?tabla=repuesto&id=12
    include("auth.php");
    include("conexionPDO.php");

    $tabla = isset($_GET['tabla']) ? $_GET['tabla'] : '';
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $foto = null;

    try {
        if ($tabla === 'cliente') {
            $sql = "SELECT Fotografia FROM Clientes WHERE Id_Cliente = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $fila = $stmt->fetch();
            if ($fila) { $foto = $fila['Fotografia']; }
        } elseif ($tabla === 'repuesto') {
            $sql = "SELECT Fotografia FROM Repuestos WHERE Referencia = :id";
            $stmt = $conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $fila = $stmt->fetch();
            if ($fila) { $foto = $fila['Fotografia']; }
        }
    } catch (PDOException $e) {
        $foto = null;
    }

    if ($foto) {
        header('Content-Type: image/jpeg');
        header('Content-Length: ' . strlen($foto));
        echo $foto;
    } else {
        // Sin foto disponible: devolvemos un pixel transparente para que
        // la etiqueta <img> no rompa el diseño.
        header('Content-Type: image/gif');
        echo base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBTAA7');
    }
?>
