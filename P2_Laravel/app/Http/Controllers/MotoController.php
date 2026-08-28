<?php

namespace App\Http\Controllers;

use App\Models\Motocicleta;

class MotoController extends Controller
{
    /**
     * Muestra todos los datos de la motocicleta cuya Matricula coincide con el
     * parámetro recibido por la URL: /datos_motocicleta/{matricula}
     */
    public function ver($matricula)
    {
        $motocicletas = Motocicleta::where('Matricula', $matricula)->get();

        return view('datos_motocicleta.ver', ['motocicletas' => $motocicletas]);
    }
}
