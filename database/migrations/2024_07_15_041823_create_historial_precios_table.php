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
        Schema::create('historial_precios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_gestion');
            $table->decimal('precio_ant', 8, 2);
            $table->decimal('precio_act', 8, 2);
            $table->text('motivo');
            $table->timestamp('fecha');
            $table->unsignedBigInteger('id_operador');
            $table->timestamps();

            $table->foreign('id_gestion')->references('id')->on('gestiones_aceptadas')->onDelete('cascade');
            $table->foreign('id_operador')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_precios');
    }
};
