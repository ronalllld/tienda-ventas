<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarVarianteRequest;
use App\Http\Resources\VarianteProductoResource;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VarianteProducto;

class VarianteProductoController extends Controller
{
    public function store(GuardarVarianteRequest $request, Producto $producto)
    {
        $variante = $producto->variantes()->create([
            ...$request->validated(),
            'disponible' => $request->boolean('disponible', true),
        ]);

        return new VarianteProductoResource($variante);
    }

    public function update(GuardarVarianteRequest $request, VarianteProducto $variante)
    {
        $disponibleAntes = $variante->disponible;

        $variante->update($request->validated());
        $variante->refresh();

        if ($disponibleAntes && ! $variante->disponible) {
            $variante->loadMissing('producto');

            Venta::create([
                'producto_id' => $variante->producto_id,
                'variante_id' => $variante->id,
                'nombre_producto' => $variante->producto->nombre,
                'talla' => $variante->talla,
                'color' => $variante->color,
                'precio_venta' => $variante->producto->precio,
                'precio_costo' => $variante->producto->precio_costo,
            ]);
        } elseif (! $disponibleAntes && $variante->disponible) {
            $variante->ventas()->latest()->first()?->delete();
        }

        return new VarianteProductoResource($variante);
    }

    public function destroy(VarianteProducto $variante)
    {
        $variante->delete();

        return response()->noContent();
    }
}
