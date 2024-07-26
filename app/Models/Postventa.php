<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postventa extends Model
{
    use HasFactory;

    protected $table = 'postventa';

    protected $fillable = [
        'id_gestion',
        'trabajo_realizado',
        'pago_realizado',
        'fecha_ejecucion',
        'informe'
    ];
}
