<?php
    include("auth.php");
    include("conexionPDO.php");

    $sql = "SELECT * FROM Motocicletas WHERE 1=1";
    $params = array();

    if (!empty($_GET['matricula'])) {
        $sql .= " AND Matricula LIKE :matricula";
        $params[':matricula'] = '%' . $_GET['matricula'] . '%';
    }
    if (!empty($_GET['marca'])) {
        $sql .= " AND Marca LIKE :marca";
        $params[':marca'] = '%' . $_GET['marca'] . '%';
    }
    if (!empty($_GET['modelo'])) {
        $sql .= " AND Modelo LIKE :modelo";
        $params[':modelo'] = '%' . $_GET['modelo'] . '%';
    }
    if (!empty($_GET['anyo'])) {
        $sql .= " AND Anyo = :anyo";
        $params[':anyo'] = intval($_GET['anyo']);
    }
    if (!empty($_GET['color'])) {
        $sql .= " AND Color LIKE :color";
        $params[':color'] = '%' . $_GET['color'] . '%';
    }
    $sql .= " ORDER BY Matricula";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Buscar motocicletas</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h2 class="text-center">Resultados de la búsqueda</h2>
    <?php if ($resultados) { ?>
        <table>
            <tr><th>Matrícula</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Color</th></tr>
            <?php foreach ($resultados as $fila) { ?>
            <tr>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Matricula'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Marca'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Modelo'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Anyo'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                <td><a href="listar_motocicletas2.php?matricula=<?php echo urlencode($fila['Matricula']); ?>"><?php echo htmlspecialchars($fila['Color'], ENT_QUOTES, 'UTF-8'); ?></a></td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <div class="message">No se encontraron resultados.</div>
    <?php } ?>
</body>
</html>