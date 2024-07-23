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
        Schema::create('operador_cliente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_operador');
            $table->string('codigo_cliente');
            $table->timestamps();

            // Foreign key constraints (if applicable)
            // Assuming there are tables 'operadores' and 'clientes'
            $table->foreign('id_operador')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('codigo_cliente')->references('codigo')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operador_cliente');
    }
};
