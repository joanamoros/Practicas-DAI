<?php
    include("auth.php");
    include("conexionPDO.php");

    $numero_factura = isset($_GET['id']) ? $_GET['id'] : '';

    $stmt = $conexion->prepare("SELECT * FROM Facturas WHERE Numero_Factura = :nf");
    $stmt->bindParam(':nf', $numero_factura, PDO::PARAM_STR);
    $stmt->execute();
    $factura = $stmt->fetch();

    if (!$factura) {
        include("topbar.php");
        echo "<p style='text-align:center;'>Factura no encontrada.</p>";
        exit();
    }

    $stmtDet = $conexion->prepare(
        "SELECT d.Id_Det_Factura, d.Referencia, d.Unidades, r.Descripcion, r.Importe, r.Ganancia
         FROM Detalle_Factura d JOIN Repuestos r ON d.Referencia = r.Referencia
         WHERE d.Numero_Factura = :nf"
    );
    $stmtDet->bindParam(':nf', $numero_factura, PDO::PARAM_STR);
    $stmtDet->execute();
    $lineasActuales = $stmtDet->fetchAll();

    $stmtRep = $conexion->prepare("SELECT Referencia, Descripcion, Importe FROM Repuestos ORDER BY Descripcion");
    $stmtRep->execute();
    $referencias_repuestos = $stmtRep->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="estilo.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar factura</title>
</head>
<body>
    <?php include("topbar.php"); ?>
    <h1><center>Modificar factura</center></h1>
    <form method="post" action="modificar_facturas_lista2.php">
        <input type="hidden" name="numero_factura" value="<?php echo htmlspecialchars($factura['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?>">

        <p>Nº Factura: <b><?php echo htmlspecialchars($factura['Numero_Factura'], ENT_QUOTES, 'UTF-8'); ?></b> (Matrícula: <?php echo htmlspecialchars($factura['Matricula'], ENT_QUOTES, 'UTF-8'); ?>)</p>

        <div class="form-group">
            <label>Mano de obra (horas):</label>
            <input type="number" class="form-control" name="mano_obra" min="0" step="1" required value="<?php echo htmlspecialchars($factura['Mano_Obra'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group">
            <label>Precio por hora (€):</label>
            <input type="number" class="form-control" name="precio_hora" min="0" step="0.01" required value="<?php echo htmlspecialchars($factura['Precio_Hora'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group">
            <label>Fecha de emisión:</label>
            <input type="date" class="form-control" name="fecha_emision" required value="<?php echo htmlspecialchars($factura['Fecha_Emision'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group">
            <label>Fecha de pago:</label>
            <input type="date" class="form-control" name="fecha_pago" required value="<?php echo htmlspecialchars($factura['Fecha_Pago'], ENT_QUOTES, 'UTF-8'); ?>">
        </div>

        <h4>Líneas de repuestos actuales</h4>
        <?php if (empty($lineasActuales)) { ?>
            <p>Esta factura no tiene líneas de repuestos.</p>
        <?php } else { ?>
            <?php foreach ($lineasActuales as $l) { ?>
            <div class="linea-existente">
                <span><?php echo htmlspecialchars($l['Descripcion'], ENT_QUOTES, 'UTF-8'); ?> — <?php echo $l['Unidades']; ?> ud. x <?php echo number_format((float)$l['Importe'], 2); ?> € (+<?php echo $l['Ganancia']; ?>%)</span>
                <label><input type="checkbox" name="eliminar_linea[]" value="<?php echo $l['Id_Det_Factura']; ?>"> Eliminar</label>
            </div>
            <?php } ?>
        <?php } ?>

        <h4>Añadir nuevas líneas de repuestos</h4>
        <div id="detalles_factura_container"></div>
        <div class="button-row-inline">
            <?php if (!empty($referencias_repuestos)) { ?>
            <button type="button" class="btn btn-success" onclick="anyadirLinea()">Añadir línea de repuesto</button>
            <?php } ?>
            <input type="submit" value="Modificar factura" class="btn lila-button">
        </div>
    </form>

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
    </script>
</body>
</html>
