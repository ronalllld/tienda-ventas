<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarVarianteRequest;
use App\Http\Resources\VarianteProductoResource;
use App\Models\Producto;
use App\Models\VarianteProducto;

class VarianteProductoController extends Controller
{
    public function store(GuardarVarianteRequest $request, Producto $producto)
    {
        $variante = $producto->variantes()->create($request->validated());

        return new VarianteProductoResource($variante);
    }

    public function update(GuardarVarianteRequest $request, VarianteProducto $variante)
    {
        $variante->update($request->validated());

        return new VarianteProductoResource($variante);
    }

    public function destroy(VarianteProducto $variante)
    {
        $variante->delete();

        return response()->noContent();
    }
}
