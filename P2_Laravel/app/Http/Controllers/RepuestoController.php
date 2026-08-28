<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;

class RepuestoController extends Controller
{
    /**
     * Lista todos los repuestos.
     */
    public function ver()
    {
        $repuestos = Repuesto::all();

        return view('repuestos.ver', ['repuestos' => $repuestos]);
    }

    /**
     * Actualiza la ganancia del repuesto cuya referencia es $referencia,
     * asignándole la ganancia $ganancia: /actualizar_repuesto/{referencia}/{ganancia}
     */
    public function actualizar($referencia, $ganancia)
    {
        if (!is_numeric($referencia) || !is_numeric($ganancia)) {
            return redirect()->route('repuestos.ver')->with('status', 'Datos inválidos.');
        }

        $repuesto = Repuesto::find($referencia);

        if (!$repuesto) {
            return redirect()->route('repuestos.ver')->with('status', 'Referencia no encontrada.');
        }

        $repuesto->Ganancia = $ganancia;
        $repuesto->save();

        return redirect()->route('repuestos.ver')->with('status', 'Ganancia actualizada correctamente.');
    }
}
