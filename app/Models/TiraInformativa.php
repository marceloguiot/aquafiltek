<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiraInformativa extends Model
{
    use HasFactory;

    protected $table = 'tira_informativa';

    protected $fillable = [
        'permanente',
        'minutos',
        'mensaje',
    ];
}
