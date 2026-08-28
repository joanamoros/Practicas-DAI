<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT m.Matricula, m.Marca, m.Modelo, c.Nombre, c.Apellido1
            FROM Motocicletas m JOIN Clientes c ON m.Id_Cliente = c.Id_Cliente
            ORDER BY m.Matricula";
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
    <title>Motocicletas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>MOTOCICLETAS</b></h1>
        <a href="seleccionar_cliente_motocicleta.php" class="btn btn-success my-2">Añadir nueva moto</a>
        <form method="post" action="eliminar_motocicletas_lista.php" class="list-form">
            <div class="table-scroll">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr><th></th><th class="text-center">Matrícula</th><th class="text-center">Moto</th><th class="text-center">Propietario</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($resultado)) { ?>
                        <tr><td colspan="4" class="text-center">No hay motocicletas registradas.</td></tr>
                    <?php } ?>
                    <?php foreach ($resultado as $fila) {
                        $matricula = htmlspecialchars($fila['Matricula'], ENT_QUOTES, 'UTF-8');
                        $nombre_moto = htmlspecialchars($fila['Marca'] . ' ' . $fila['Modelo'], ENT_QUOTES, 'UTF-8');
                        $propietario = htmlspecialchars($fila['Nombre'] . ' ' . $fila['Apellido1'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="borrar[]" value="<?php echo $matricula; ?>"></td>
                        <td class="text-center"><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo $matricula; ?></a></td>
                        <td class="text-center"><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo $nombre_moto; ?></a></td>
                        <td class="text-center"><?php echo $propietario; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="text-center my-2">
                <input type="submit" name="eliminar" value="Eliminar Motos Seleccionadas" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar las motos seleccionadas? También se eliminarán sus facturas asociadas.');">
                <br>
                <input type="reset" value="Deseleccionar Todos" class="btn btn-secondary my-2">
            </div>
        </form>
    </div>
</body>
</html>
