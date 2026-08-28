<?php
    include("auth.php");
    include("conexionPDO.php");

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['cliente'])) {
        header("Location: seleccionar_cliente_motocicleta.php");
        exit();
    }

    $id_cliente = intval($_POST['cliente']);

    // Comprobamos que el cliente existe realmente (no se puede dar de alta
    // una moto sin que su dueño esté ya registrado).
    $stmt = $conexion->prepare("SELECT Nombre, Apellido1 FROM Clientes WHERE Id_Cliente = ?");
    $stmt->execute([$id_cliente]);
    $cliente = $stmt->fetch();

    if (!$cliente) {
        include("topbar.php");
        echo "<p style='text-align:center;'>El cliente seleccionado no existe.</p>";
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Añadir motocicleta</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Añadir nueva motocicleta</b></h1>
        <p>Propietario: <b><?php echo htmlspecialchars($cliente['Nombre'] . ' ' . $cliente['Apellido1'], ENT_QUOTES, 'UTF-8'); ?></b></p>
        <form method="post" action="introducir_motocicleta2.php">
            <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
            <div class="form-group">
                <label for="matricula">Matrícula:</label>
                <input type="text" class="form-control" id="matricula" name="matricula" maxlength="7" required>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" maxlength="30" required>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" maxlength="30" required>
            </div>
            <div class="form-group">
                <label for="anyo">Año:</label>
                <input type="number" class="form-control" id="anyo" name="anyo" min="1900" max="2100" required>
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" id="color" name="color" maxlength="15" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
