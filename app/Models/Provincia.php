<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    use HasFactory;

    // If your table name is not 'provincias', specify it here
    protected $table = 'provincias';

    // Define the columns that are mass assignable
    protected $fillable = ['nombre'];
}
