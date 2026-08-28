<?php
    include("auth.php");
    include("conexionPDO.php");

    $id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;

    $sql = "SELECT * FROM Clientes WHERE Id_Cliente = :id_cliente";
    $consulta = $conexion->prepare($sql);
    $consulta->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
    $consulta->execute();
    $fila = $consulta->fetch();

    if (!$fila) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Cliente no encontrado.</p>";
        exit();
    }

    $id_cliente = $fila['Id_Cliente'];
    $dni = htmlspecialchars($fila['DNI'], ENT_QUOTES, 'UTF-8');
    $nombre = htmlspecialchars($fila['Nombre'], ENT_QUOTES, 'UTF-8');
    $apellido1 = htmlspecialchars($fila['Apellido1'], ENT_QUOTES, 'UTF-8');
    $apellido2 = htmlspecialchars($fila['Apellido2'], ENT_QUOTES, 'UTF-8');
    $direccion = htmlspecialchars($fila['Direccion'], ENT_QUOTES, 'UTF-8');
    $cp = htmlspecialchars($fila['CP'], ENT_QUOTES, 'UTF-8');
    $poblacion = htmlspecialchars($fila['Poblacion'], ENT_QUOTES, 'UTF-8');
    $provincia = htmlspecialchars($fila['Provincia'], ENT_QUOTES, 'UTF-8');
    $telefono = htmlspecialchars($fila['Telefono'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($fila['Email'], ENT_QUOTES, 'UTF-8');
    $tieneFoto = !empty($fila['Fotografia']);

    // Motocicletas de este cliente
    $sqlMotos = "SELECT Matricula, Marca, Modelo FROM Motocicletas WHERE Id_Cliente = :id ORDER BY Matricula";
    $stmtMotos = $conexion->prepare($sqlMotos);
    $stmtMotos->bindParam(':id', $id_cliente, PDO::PARAM_INT);
    $stmtMotos->execute();
    $motos = $stmtMotos->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <title>Detalles del Cliente</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center">Detalles del cliente</h1>
        <a href="modificar_clientes_lista.php?id=<?php echo $id_cliente; ?>" class="btn btn-blue">Editar cliente</a>
        <form method="post" action="eliminar_clientes_lista.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este cliente? También se eliminarán sus motocicletas y facturas asociadas.');">
            <button type="submit" name="id_cliente" value="<?php echo $id_cliente; ?>" class="btn btn-red">Eliminar cliente</button>
        </form>
        <div class="table-scroll table-fit">
        <table>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido1</th>
                <th>Apellido2</th>
                <th>Dirección</th>
                <th>CP</th>
                <th>Población</th>
                <th>Provincia</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Fotografía</th>
            </tr>
            <tr>
                <td><?php echo $id_cliente; ?></td>
                <td><?php echo $dni; ?></td>
                <td><?php echo $nombre; ?></td>
                <td><?php echo $apellido1; ?></td>
                <td><?php echo $apellido2; ?></td>
                <td><?php echo $direccion; ?></td>
                <td><?php echo $cp; ?></td>
                <td><?php echo $poblacion; ?></td>
                <td><?php echo $provincia; ?></td>
                <td><?php echo $telefono; ?></td>
                <td><?php echo $email; ?></td>
                <td>
                    <?php if ($tieneFoto) { ?>
                        <a href="foto.php?tabla=cliente&id=<?php echo $id_cliente; ?>" target="_blank">
                            <img src="foto.php?tabla=cliente&id=<?php echo $id_cliente; ?>" alt="Foto">
                        </a>
                    <?php } else { echo "Sin foto"; } ?>
                </td>
            </tr>
        </table>
        </div>

        <h2>Motocicletas de este cliente</h2>
        <?php if (empty($motos)) { ?>
            <p>Este cliente no tiene motocicletas registradas.</p>
        <?php } else { ?>
        <table>
            <tr><th>Matrícula</th><th>Marca</th><th>Modelo</th></tr>
            <?php foreach ($motos as $moto) { ?>
            <tr>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($moto['Matricula']); ?>"><?php echo htmlspecialchars($moto['Matricula'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><?php echo htmlspecialchars($moto['Marca'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($moto['Modelo'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php } ?>
    </div>
</body>
</html>
