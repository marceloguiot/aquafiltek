<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'comentario',
        'fecha',
        'hora',
        'id_cliente',
        'estado',
        'id_operador',
        'fecha_gestion',
        'hora_gestion'
    ];

    public $timestamps = false;

}
