<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionAceptada extends Model
{
    use HasFactory;

    protected $table = 'gestiones_aceptadas';

    protected $fillable = [
        'id_cliente',
        'codigo',
        'fecha_acepto',
        'hora_acepto',
        'precio',
        'comentarios',
        'fecha_gestion',
        'id_operador'
    ];


}
