<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostventaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postventa', function (Blueprint $table) {
            $table->id();
            $table->integer('id_gestion');
            $table->string('trabajo_realizado');
            $table->decimal('pago_realizado', 8, 2);
            $table->date('fecha_ejecucion');
            $table->string('informe'); // Store file path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postventa');
    }
}
