<?php

namespace App\Http\Controllers\Api\Publico;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoDetalleResource;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoPublicoController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::query()
            ->where('activo', true)
            ->conStockDisponible()
            ->with(['categoria', 'imagenes'])
            ->when($request->filled('categoria'), function ($query) use ($request) {
                $query->whereHas('categoria', fn ($q) => $q->where('slug', $request->string('categoria')));
            })
            ->when($request->filled('talla') || $request->filled('color'), function ($query) use ($request) {
                $query->whereHas('variantes', function ($q) use ($request) {
                    $q->where('disponible', true);
                    $q->when($request->filled('talla'), fn ($q) => $q->where('talla', $request->string('talla')));
                    $q->when($request->filled('color'), fn ($q) => $q->where('color', $request->string('color')));
                });
            })
            ->when($request->filled('precio_min'), fn ($query) => $query->where('precio', '>=', $request->float('precio_min')))
            ->when($request->filled('precio_max'), fn ($query) => $query->where('precio', '<=', $request->float('precio_max')))
            ->orderBy('nombre')
            ->get();

        return ProductoResource::collection($productos);
    }

    public function show(string $idOSlug)
    {
        $producto = Producto::query()
            ->where('activo', true)
            ->conStockDisponible()
            ->with(['categoria', 'imagenes', 'variantes'])
            ->where(is_numeric($idOSlug) ? 'id' : 'slug', $idOSlug)
            ->firstOrFail();

        return new ProductoDetalleResource($producto);
    }
}
