<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialPrecio extends Model
{
    use HasFactory;

    protected $table = 'historial_precios';

    protected $fillable = [
        'id_gestion',
        'precio_ant',
        'precio_act',
        'motivo',
        'fecha',
        'id_operador',
    ];

    public $timestamps = true;

    // Relaciones con otros modelos (si es necesario)
    public function gestion()
    {
        return $this->belongsTo(GestionAceptada::class, 'id_gestion');
    }

    public function operador()
    {
        return $this->belongsTo(User::class, 'id_operador');
    }
}
