<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT Id_Cliente, DNI, Nombre, Apellido1, Apellido2 FROM Clientes ORDER BY Nombre, Apellido1";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $resultado = $consulta->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clientes</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>CLIENTES</b></h1>
        <a href="introducir_clientes.php" class="btn btn-success my-2">Añadir nuevo cliente</a>
        <form method="post" action="eliminar_clientes_lista.php" class="list-form">
            <div class="table-scroll">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th class="text-center">DNI</th>
                        <th class="text-center">Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($resultado)) { ?>
                        <tr><td colspan="3" class="text-center">No hay clientes registrados.</td></tr>
                    <?php } ?>
                    <?php foreach ($resultado as $fila) {
                        $id_cliente = $fila['Id_Cliente'];
                        $dni = htmlspecialchars($fila['DNI'], ENT_QUOTES, 'UTF-8');
                        $nombre_completo = htmlspecialchars($fila['Nombre'] . ' ' . $fila['Apellido1'] . ' ' . $fila['Apellido2'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="borrar[]" value="<?php echo $id_cliente; ?>"></td>
                        <td class="text-center"><a href="listar_clientes2.php?id=<?php echo $id_cliente; ?>"><?php echo $dni; ?></a></td>
                        <td class="text-center"><a href="listar_clientes2.php?id=<?php echo $id_cliente; ?>"><?php echo $nombre_completo; ?></a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="text-center my-2">
                <input type="submit" name="eliminar" value="Eliminar Clientes Seleccionados" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar los clientes seleccionados? También se eliminarán sus motocicletas y facturas asociadas.');">
                <br>
                <input type="reset" value="Deseleccionar Todos" class="btn btn-secondary my-2">
            </div>
        </form>
    </div>
</body>
</html>
