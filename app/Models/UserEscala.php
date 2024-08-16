<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEscala extends Model
{
    use HasFactory;

    protected $table = 'users_escala';

    protected $fillable = [
        'usuario_id',
        'escala_id',
        'numero',
        'fecha',
    ];
}
