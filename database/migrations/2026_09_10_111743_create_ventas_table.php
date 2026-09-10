<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
            $table->foreignId('variante_id')->nullable()->constrained('variantes_producto')->nullOnDelete();
            $table->string('nombre_producto');
            $table->string('talla')->nullable();
            $table->string('color');
            $table->decimal('precio_venta', 10, 2);
            $table->decimal('precio_costo', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
