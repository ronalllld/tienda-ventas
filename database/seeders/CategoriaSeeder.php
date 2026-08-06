<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Camisetas', 'Pantalones', 'Vestidos', 'Chaquetas', 'Accesorios'] as $nombre) {
            Categoria::query()->updateOrCreate(
                ['slug' => Str::slug($nombre)],
                ['nombre' => $nombre]
            );
        }
    }
}
