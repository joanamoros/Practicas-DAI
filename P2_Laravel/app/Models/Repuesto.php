<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repuesto extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'repuestos';

    /**
     * La clave primaria real de la tabla es la referencia.
     */
    protected $primaryKey = 'Referencia';

    /**
     * La tabla no tiene columnas created_at / updated_at.
     */
    public $timestamps = false;

    protected $guarded = [];
}
