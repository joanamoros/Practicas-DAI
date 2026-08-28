<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DB;

class ClienteController extends Controller
{
    public function ver(){
        $clientes = DB::select('select * from clientes where Id_Cliente = ?', [1]);
        return view('clientes.ver', ['clientes' => $clientes]);
    }
}

?>

