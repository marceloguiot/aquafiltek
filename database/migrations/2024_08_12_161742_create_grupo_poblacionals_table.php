<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_poblacionales', function (Blueprint $table) {
            $table->id(); // Campo ID automático
            $table->string('nombre'); // Nombre del grupo poblacional
            $table->unsignedBigInteger('idprovincia'); // ID de la provincia
            $table->unsignedBigInteger('idcanton'); // ID del cantón
            $table->unsignedBigInteger('idparroquia'); // ID de la parroquia
            $table->text('comentario')->nullable(); // Comentario, campo opcional
            $table->timestamps(); // Timestamps para created_at y updated_at

            // Agregar las relaciones si existen tablas para provincia, cantón y parroquia
            // $table->foreign('idprovincia')->references('id')->on('provincias');
            // $table->foreign('idcanton')->references('id')->on('cantones');
            // $table->foreign('idparroquia')->references('id')->on('parroquias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos_poblacionales');
    }
};
