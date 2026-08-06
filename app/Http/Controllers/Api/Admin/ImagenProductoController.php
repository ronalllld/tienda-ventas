<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GuardarImagenRequest;
use App\Http\Resources\ImagenProductoResource;
use App\Models\ImagenProducto;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;

class ImagenProductoController extends Controller
{
    public function store(GuardarImagenRequest $request, Producto $producto)
    {
        $ruta = $request->file('imagen')->store('productos', 'public');

        $imagen = $producto->imagenes()->create([
            'ruta' => $ruta,
            'orden' => $request->integer('orden', $producto->imagenes()->count()),
        ]);

        return new ImagenProductoResource($imagen);
    }

    public function destroy(ImagenProducto $imagen)
    {
        if (! str_starts_with($imagen->ruta, 'http')) {
            Storage::disk('public')->delete($imagen->ruta);
        }

        $imagen->delete();

        return response()->noContent();
    }
}
