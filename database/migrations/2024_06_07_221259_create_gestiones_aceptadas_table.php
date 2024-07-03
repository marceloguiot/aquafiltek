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
        Schema::create('gestiones_aceptadas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->date('fecha_acepto');
            $table->time('hora_acepto');
            $table->decimal('precio', 10, 2);
            $table->text('comentarios')->nullable();
            $table->date('fecha_gestion');
            $table->unsignedBigInteger('id_operador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestiones_aceptadas');
    }
};

