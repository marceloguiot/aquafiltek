<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LlamadasProgramadas extends Model
{
    use HasFactory;

    protected $table = 'llamadas_programadas';

    protected $fillable = [
        'codigo',
        'fecha_llamada',
        'hora_llamada',
        'realizada',
        'id_operador',
        'fecha_programada',
        'timestamp'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codigo', 'codigo');
    }
}
