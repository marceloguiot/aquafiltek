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
        Schema::create('gestion_inactivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_operador');
            $table->string('codigo_cliente');
            $table->string('motivo');
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();

            // Add foreign key constraint if needed
            // $table->foreign('id_operador')->references('id')->on('users')->onDelete('cascade');
            // If the 'codigo_cliente' refers to a specific table, you might want to add a foreign key here too.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gestion_inactivos');
    }
};
