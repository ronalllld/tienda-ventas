<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\ImagenProducto;
use App\Models\Producto;
use App\Models\VarianteProducto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            'Camisetas' => [
                ['nombre' => 'Camiseta Básica Blanca', 'precio' => 39900, 'color' => 'Blanco'],
                ['nombre' => 'Camiseta Estampada Retro', 'precio' => 49900, 'color' => 'Beige'],
                ['nombre' => 'Camiseta Oversize Negra', 'precio' => 44900, 'color' => 'Negro'],
            ],
            'Pantalones' => [
                ['nombre' => 'Jean Slim Azul', 'precio' => 89900, 'color' => 'Azul'],
                ['nombre' => 'Pantalón Cargo Verde', 'precio' => 94900, 'color' => 'Verde'],
                ['nombre' => 'Jogger Gris', 'precio' => 69900, 'color' => 'Gris'],
            ],
            'Vestidos' => [
                ['nombre' => 'Vestido Floral de Verano', 'precio' => 109900, 'color' => 'Multicolor'],
                ['nombre' => 'Vestido Casual Negro', 'precio' => 99900, 'color' => 'Negro'],
                ['nombre' => 'Vestido de Noche Rojo', 'precio' => 149900, 'color' => 'Rojo'],
            ],
            'Chaquetas' => [
                ['nombre' => 'Chaqueta de Cuero Negra', 'precio' => 199900, 'color' => 'Negro'],
                ['nombre' => 'Chaqueta Denim Clásica', 'precio' => 129900, 'color' => 'Azul'],
                ['nombre' => 'Chaqueta Deportiva Azul', 'precio' => 119900, 'color' => 'Azul'],
            ],
            'Accesorios' => [
                ['nombre' => 'Gorra Clásica Negra', 'precio' => 34900, 'color' => 'Negro'],
                ['nombre' => 'Cinturón de Cuero Marrón', 'precio' => 44900, 'color' => 'Marrón'],
                ['nombre' => 'Bufanda de Lana Gris', 'precio' => 29900, 'color' => 'Gris'],
            ],
        ];

        foreach ($productos as $categoriaNombre => $items) {
            $categoria = Categoria::where('slug', Str::slug($categoriaNombre))->firstOrFail();

            foreach ($items as $item) {
                $slug = Str::slug($item['nombre']);

                $producto = Producto::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'nombre' => $item['nombre'],
                        'descripcion' => "{$item['nombre']} de la categoría {$categoriaNombre}, ideal para uso diario. Prenda de excelente calidad y confección.",
                        'precio' => $item['precio'],
                        'categoria_id' => $categoria->id,
                        'activo' => true,
                    ]
                );

                $esAccesorio = $categoriaNombre === 'Accesorios';
                $tallas = $esAccesorio ? ['Única'] : ['S', 'M', 'L'];
                $colores = $esAccesorio ? [$item['color'], 'Negro'] : [$item['color']];

                foreach ($tallas as $talla) {
                    foreach ($colores as $color) {
                        if ($talla === 'Única' && $color === $item['color']) {
                            $stock = 15;
                        } else {
                            $stock = fake()->numberBetween(0, 20);
                        }

                        VarianteProducto::query()->updateOrCreate(
                            ['producto_id' => $producto->id, 'talla' => $talla, 'color' => $color],
                            ['stock' => $stock]
                        );
                    }
                }

                if ($producto->imagenes()->count() === 0) {
                    foreach (range(1, 3) as $orden) {
                        ImagenProducto::create([
                            'producto_id' => $producto->id,
                            'ruta' => "https://picsum.photos/seed/{$slug}-{$orden}/600/800",
                            'orden' => $orden,
                        ]);
                    }
                }
            }
        }
    }
}
