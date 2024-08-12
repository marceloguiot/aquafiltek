<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    use HasFactory;

    protected $table = 'cantones';

    protected $fillable = [
        'canton_nombre',
        'idprov'
    ];

    // Define the relationship with the Provincia model
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'idprov');
    }
}
