<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id');
            $table->string('codigo')->unique();
            $table->timestamps();
            $table->string('coordenadas');
            $table->string('nombre_cliente');
            $table->text('datos_factura')->nullable();;
            $table->string('direccion');
            $table->string('telefono');
            $table->string('telefono_oficina')->nullable();
            $table->string('celular1')->nullable();
            $table->string('celular2')->nullable();
            $table->string('correo')->nullable();
            $table->text('informacion_cisterna')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('tipo_tel1')->nullable();
            $table->string('obser_tel1')->nullable();
            $table->string('tipo_telof')->nullable();
            $table->string('obser_telof')->nullable();
            $table->string('fecha_gestion')->nullable();
            $table->string('estado');
            $table->string('tipo_cel1')->nullable();
            $table->string('obser_cel1')->nullable();
            $table->string('tipo_cel2')->nullable();
            $table->string('obser_cel2')->nullable();
            $table->integer('inactivo')->default(0);
            $table->integer('tipo_inactivo')->default(0);
            $table->string('actualizar_pendiente')->nullable();
            $table->integer('deleted')->default(0);
            $table->integer('provincia')->nullable();
            $table->integer('canton')->nullable();
            $table->integer('parroquia')->nullable();
            $table->integer('fecha_subida')->nullable();
            $table->float('lng', 10, 6); // Longitude
            $table->float('lat', 10, 6); // Latitude
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
