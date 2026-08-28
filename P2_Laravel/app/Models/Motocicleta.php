<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motocicleta extends Model
{
    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'motocicletas';

    /**
     * La clave primaria real de la tabla es la matrícula (texto), no un id autoincremental.
     */
    protected $primaryKey = 'Matricula';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * La tabla no tiene columnas created_at / updated_at.
     */
    public $timestamps = false;

    protected $guarded = [];
}
