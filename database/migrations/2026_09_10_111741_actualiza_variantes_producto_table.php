<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variantes_producto', function (Blueprint $table) {
            $table->boolean('disponible')->default(true)->after('color');
        });

        DB::table('variantes_producto')->update([
            'disponible' => DB::raw('CASE WHEN stock > 0 THEN 1 ELSE 0 END'),
        ]);

        Schema::table('variantes_producto', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->string('talla', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('variantes_producto', function (Blueprint $table) {
            $table->unsignedInteger('stock')->default(0)->after('color');
        });

        DB::table('variantes_producto')->update([
            'stock' => DB::raw('CASE WHEN disponible = 1 THEN 1 ELSE 0 END'),
        ]);

        DB::table('variantes_producto')->whereNull('talla')->update(['talla' => 'Única']);

        Schema::table('variantes_producto', function (Blueprint $table) {
            $table->dropColumn('disponible');
            $table->string('talla', 50)->nullable(false)->change();
        });
    }
};
