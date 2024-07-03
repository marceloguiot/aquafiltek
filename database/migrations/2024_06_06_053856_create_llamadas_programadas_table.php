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
        Schema::create('llamadas_programadas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->date('fecha_llamada');
            $table->time('hora_llamada');
            $table->Integer('realizada')->default(0);
            $table->unsignedBigInteger('id_operador');
            $table->date('fecha_programada');
            $table->string('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llamadas_programadas');
    }
};

