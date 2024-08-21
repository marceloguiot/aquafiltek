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
        Schema::create('tira_informativa', function (Blueprint $table) {
            $table->id();
            $table->boolean('permanente')->default(0); // 0 or 1 for permanence status
            $table->integer('minutos'); // Number of minutes
            $table->text('mensaje'); // Text message
            $table->timestamps(); // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tira_informativa');
    }
};
