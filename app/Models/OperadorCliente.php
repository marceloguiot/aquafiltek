<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperadorCliente extends Model
{
    use HasFactory;

    protected $table = 'operador_cliente';

    protected $fillable = [
        'id_operador',
        'codigo_cliente',
    ];

    // Relaciones con otros modelos (si es necesario)
    public function operador()
    {
        return $this->belongsTo(User::class, 'id_operador', 'id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'codigo_cliente', 'codigo');
    }
}
