<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoPoblacional extends Model
{
    use HasFactory;

    protected $table = 'grupos_poblacionales';

    protected $fillable = [
        'nombre',
        'idprovincia',
        'idcanton',
        'idparroquia',
        'comentario',
    ];

    // Relación con otras tablas (si es necesario)
    // public function provincia()
    // {
    //     return $this->belongsTo(Provincia::class, 'idprovincia');
    // }

    // public function canton()
    // {
    //     return $this->belongsTo(Canton::class, 'idcanton');
    // }

    // public function parroquia()
    // {
    //     return $this->belongsTo(Parroquia::class, 'idparroquia');
    // }
}
