<?php
    // Cálculo de la Base Imponible, IVA y Total de una factura, siguiendo
    // exactamente la fórmula del enunciado:
    //   1) Por cada línea de detalle: Importe del repuesto x Unidades,
    //      más el porcentaje de Ganancia sobre ese importe.
    //   2) A la suma de todas las líneas se añade el montante de Mano de
    //      Obra: número de horas x precio de la hora.
    //   3) Sobre la Base Imponible se calcula el IVA y el Total.
    define('IVA_TIPO', 0.21); // 21% - tipo general vigente en España

    // Devuelve un array con el desglose calculado, o null si alguna
    // referencia de repuesto no existe en la base de datos.
    function calcularFactura($conexion, $detalles, $manoObraHoras, $precioHora) {
        $sumaLineas = 0.0;
        $lineasCalculadas = array();

        foreach ($detalles as $d) {
            $referencia = intval($d['referencia']);
            $unidades = intval($d['unidades']);
            if ($referencia <= 0 || $unidades <= 0) {
                return null;
            }

            $stmt = $conexion->prepare("SELECT Descripcion, Importe, Ganancia FROM Repuestos WHERE Referencia = ?");
            $stmt->execute([$referencia]);
            $rep = $stmt->fetch();
            if (!$rep) {
                return null;
            }

            $importeLinea = $rep['Importe'] * $unidades;
            $importeConGanancia = $importeLinea + ($importeLinea * $rep['Ganancia'] / 100);
            $sumaLineas += $importeConGanancia;

            $lineasCalculadas[] = array(
                'referencia' => $referencia,
                'descripcion' => $rep['Descripcion'],
                'unidades' => $unidades,
                'importe_unitario' => (float)$rep['Importe'],
                'ganancia' => (float)$rep['Ganancia'],
                'subtotal' => $importeConGanancia,
            );
        }

        $montanteManoObra = $manoObraHoras * $precioHora;
        $baseImponible = $sumaLineas + $montanteManoObra;
        $iva = $baseImponible * IVA_TIPO;
        $total = $baseImponible + $iva;

        return array(
            'lineas' => $lineasCalculadas,
            'suma_lineas' => $sumaLineas,
            'montante_mano_obra' => $montanteManoObra,
            'base_imponible' => $baseImponible,
            'iva' => $iva,
            'total' => $total,
        );
    }

    // Extrae el array de líneas detalles_factura[i][referencia|unidades]
    // enviado desde el formulario y lo normaliza, descartando filas vacías.
    function extraerDetallesPost($post) {
        $detalles = array();
        if (isset($post['detalles_factura']) && is_array($post['detalles_factura'])) {
            foreach ($post['detalles_factura'] as $linea) {
                if (!empty($linea['referencia']) && !empty($linea['unidades'])) {
                    $detalles[] = array(
                        'referencia' => intval($linea['referencia']),
                        'unidades' => intval($linea['unidades']),
                    );
                }
            }
        }
        return $detalles;
    }
?>
