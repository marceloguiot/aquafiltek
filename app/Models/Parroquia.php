<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;

    protected $table = 'parroquias';

    protected $fillable = [
        'parroquia_nombre',
        'idcanton',
    ];

    public function canton()
    {
        return $this->belongsTo(Canton::class, 'idcanton');
    }
}
