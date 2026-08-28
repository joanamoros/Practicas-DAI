<?php
    include("auth.php");
    include("conexionPDO.php");

    if ($_SERVER["REQUEST_METHOD"] !== "POST" || empty($_POST["moto"])) {
        header("Location: seleccionar_motocicleta_factura.php");
        exit();
    }
    $matricula = $_POST["moto"];

    $stmtMoto = $conexion->prepare("SELECT Matricula, Marca, Modelo FROM Motocicletas WHERE Matricula = ?");
    $stmtMoto->execute([$matricula]);
    $moto = $stmtMoto->fetch();

    if (!$moto) {
        include("topbar.php");
        echo "<p style='text-align:center;'>La motocicleta seleccionada no existe.</p>";
        exit();
    }

    $sql = "SELECT Referencia, Descripcion, Importe FROM Repuestos ORDER BY Descripcion";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();
    $referencias_repuestos = $consulta->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Añadir factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <div class="container">
        <h1 class="text-center my-4"><b>Añadir nueva factura</b></h1>
        <p style="text-align:center;">Moto: <b><?php echo htmlspecialchars($moto['Marca'] . ' ' . $moto['Modelo'] . ' (' . $moto['Matricula'] . ')', ENT_QUOTES, 'UTF-8'); ?></b></p>

        <?php if (empty($referencias_repuestos)) { ?>
            <p style="text-align:center;">No hay repuestos dados de alta. Debes añadir al menos un repuesto antes de poder facturar piezas (aunque puedes facturar solo mano de obra si lo dejas sin líneas).</p>
        <?php } ?>

        <form method="post" action="introducir_factura2.php">
            <input type="hidden" name="matricula" value="<?php echo htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label for="numero_factura">Número de factura:</label>
                <input type="text" class="form-control" id="numero_factura" name="numero_factura" maxlength="15" required>
            </div>
            <div class="form-group">
                <label for="mano_obra">Mano de obra (horas):</label>
                <input type="number" class="form-control" id="mano_obra" name="mano_obra" min="0" step="1" value="0" required>
            </div>
            <div class="form-group">
                <label for="precio_hora">Precio por hora (€):</label>
                <input type="number" class="form-control" id="precio_hora" name="precio_hora" min="0" step="0.01" value="0" required>
            </div>
            <div class="form-group">
                <label for="fecha_emision">Fecha de emisión:</label>
                <input type="date" class="form-control" id="fecha_emision" name="fecha_emision" required>
            </div>
            <div class="form-group">
                <label for="fecha_pago">Fecha de pago:</label>
                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
            </div>

            <h4>Líneas de repuestos</h4>
            <div id="detalles_factura_container"></div>
            <div class="button-row-inline">
                <?php if (!empty($referencias_repuestos)) { ?>
                <button type="button" class="btn btn-success" onclick="anyadirLinea()">Añadir línea de repuesto</button>
                <?php } ?>
                <button type="submit" class="btn btn-primary">Continuar</button>
            </div>
        </form>
    </div>

    <template id="plantilla_linea">
        <div class="detalle_factura">
            <div class="form-group">
                <label>Referencia de repuesto:</label>
                <select class="form-control" name="detalles_factura[__i__][referencia]" required>
                    <option value="">Seleccione un repuesto</option>
                    <?php foreach ($referencias_repuestos as $r) { ?>
                    <option value="<?php echo $r['Referencia']; ?>"><?php echo htmlspecialchars($r['Descripcion'] . ' (' . number_format((float)$r['Importe'], 2) . ' €)', ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group" style="max-width:120px;">
                <label>Unidades:</label>
                <input type="number" class="form-control" name="detalles_factura[__i__][unidades]" min="1" value="1" required>
            </div>
            <button type="button" class="quitar-linea" onclick="this.closest('.detalle_factura').remove()">Quitar</button>
        </div>
    </template>

    <script>
        var contadorLineas = 0;
        function anyadirLinea() {
            var plantilla = document.getElementById('plantilla_linea').innerHTML.replace(/__i__/g, contadorLineas);
            var contenedor = document.getElementById('detalles_factura_container');
            var wrapper = document.createElement('div');
            wrapper.innerHTML = plantilla.trim();
            contenedor.appendChild(wrapper.firstElementChild);
            contadorLineas++;
        }
        <?php if (!empty($referencias_repuestos)) { ?>
        // Empezamos con una línea ya visible para comodidad del usuario.
        anyadirLinea();
        <?php } ?>
    </script>
</body>
</html>
