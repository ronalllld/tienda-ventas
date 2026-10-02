<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_id');
            $table->date('fecha');
            $table->string('ruta')->nullable();
            $table->timestamp('ultima_actividad');
            $table->timestamps();

            $table->unique(['visitor_id', 'fecha']);
            $table->index('fecha');
            $table->index('ultima_actividad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};
