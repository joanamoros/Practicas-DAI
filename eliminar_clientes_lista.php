<?php
    include("auth.php");
    include("conexionPDO.php");

    // Elimina un cliente junto con sus motocicletas y las facturas
    // (y líneas de detalle) asociadas a esas motocicletas, para no dejar
    // registros huérfanos en la base de datos.
    function eliminarClientePorId($conexion, $id_cliente) {
        $sqlMotos = $conexion->prepare("SELECT Matricula FROM Motocicletas WHERE Id_Cliente = ?");
        $sqlMotos->execute([$id_cliente]);
        $matriculas = $sqlMotos->fetchAll(PDO::FETCH_COLUMN);

        foreach ($matriculas as $matricula) {
            $sqlFacturas = $conexion->prepare("SELECT Numero_Factura FROM Facturas WHERE Matricula = ?");
            $sqlFacturas->execute([$matricula]);
            $facturas = $sqlFacturas->fetchAll(PDO::FETCH_COLUMN);

            foreach ($facturas as $numeroFactura) {
                $conexion->prepare("DELETE FROM Detalle_Factura WHERE Numero_Factura = ?")->execute([$numeroFactura]);
            }
            if (!empty($facturas)) {
                $conexion->prepare("DELETE FROM Facturas WHERE Matricula = ?")->execute([$matricula]);
            }
        }

        $conexion->prepare("DELETE FROM Motocicletas WHERE Id_Cliente = ?")->execute([$id_cliente]);
        $conexion->prepare("DELETE FROM Clientes WHERE Id_Cliente = ?")->execute([$id_cliente]);
    }

    $ok = false;
    $mensaje = '';

    try {
        if (isset($_POST['eliminar']) && isset($_POST["borrar"])) {
            $conexion->beginTransaction();
            foreach ($_POST["borrar"] as $id_cliente) {
                eliminarClientePorId($conexion, intval($id_cliente));
            }
            $conexion->commit();
            $ok = true;
        } elseif (isset($_POST['id_cliente'])) {
            $conexion->beginTransaction();
            eliminarClientePorId($conexion, intval($_POST['id_cliente']));
            $conexion->commit();
            $ok = true;
        } else {
            $mensaje = "No se han seleccionado clientes para eliminar.";
        }
    } catch (PDOException $e) {
        if ($conexion->inTransaction()) { $conexion->rollBack(); }
        $mensaje = "Error al eliminar: " . $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Eliminar cliente</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div style="text-align:center; margin-top:20px;">
        <?php if ($ok) { ?>
            <div style="font-size:18px;color:green;">El/los cliente(s) se han eliminado correctamente.</div>
        <?php } else { ?>
            <div style="font-size:18px;color:red;"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>
        <br>
        <a href="listar_clientes.php" style="background-color:#4CAF50;border:none;color:white;padding:15px 32px;text-align:center;text-decoration:none;display:inline-block;font-size:16px;margin:4px 2px;cursor:pointer;">Volver al listado de clientes</a>
    </div>
</body>
</html>
