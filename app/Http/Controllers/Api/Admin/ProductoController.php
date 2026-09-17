<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarProductoRequest;
use App\Http\Resources\Admin\ProductoDetalleResource;
use App\Http\Resources\Admin\ProductoListaResource;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'imagenes', 'variantes'])
            ->orderByDesc('id')
            ->get();

        return ProductoListaResource::collection($productos);
    }

    public function store(GuardarProductoRequest $request)
    {
        $producto = Producto::create($request->validated());

        return new ProductoDetalleResource($producto->load(['categoria', 'imagenes', 'variantes']));
    }

    public function show(Producto $producto)
    {
        return new ProductoDetalleResource($producto->load(['categoria', 'imagenes', 'variantes']));
    }

    public function update(GuardarProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());

        return new ProductoDetalleResource($producto->load(['categoria', 'imagenes', 'variantes']));
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->noContent();
    }
}
