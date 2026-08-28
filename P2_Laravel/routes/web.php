<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MotoController;
use App\Http\Controllers\RepuestoController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('clientes', [ClienteController::class, 'ver']);

Route::get('repuestos', [RepuestoController::class, 'ver'])->name('repuestos.ver');

Route::get('datos_motocicleta/{matricula}', [MotoController::class, 'ver']);

Route::get('actualizar_repuesto/{referencia}/{ganancia}', [RepuestoController::class, 'actualizar']);
