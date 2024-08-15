<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionInactivos extends Model
{
    use HasFactory;

    protected $table = 'gestion_inactivos';

    protected $fillable = [
        'id_operador',
        'codigo_cliente',
        'motivo',
        'fecha',
        'hora'
    ];
}
