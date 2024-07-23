<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $table = 'gestiones';

    protected $fillable = [
        'codigo',
        'fecha',
        'hora',
        'comentarios',
        'tipo',
        'fecha_gestion',
        'id_operador'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_operador', 'id');
    }
 
}
