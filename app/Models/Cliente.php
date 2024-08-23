<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $primariKey = 'id';

    protected $fillable = [
        'codigo',
        'coordenadas',
        'estado',
        'nombre_cliente',
        'datos_factura',
        'direccion',
        'telefono',
        'telefono_oficina',
        'celular1',
        'celular2',
        'correo',
        'informacion_cisterna',
        'observaciones',
        'estado',
        'tipo_tel1',
        'tipo_telof',
        'tipo_cel1',
        'tipo_cel2',
        'obser_tel1',
        'obser_telof',
        'obser_cel1',
        'obser_cel2',
        'inactivo',
        'archivado',
        'tipo_inactivo',
        'actualizar_pendiente',
        'deleted',
        'provincia',
        'canton',
        'parroquia',
        'fecha_subida',
        'lng',  // Longitude
        'lat',  // Latitude
    ];

    public function llamadasProgramadas()
    {
        return $this->hasMany(LlamadasProgramadas::class, 'codigo', 'codigo');
    }

    public function gestionInactivo()
{
    return $this->hasOne(GestionInactivos::class, 'codigo_cliente', 'codigo');
}

}
