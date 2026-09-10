<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\CategoriaResource;
use App\Http\Resources\ImagenProductoResource;
use App\Http\Resources\VarianteProductoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoDetalleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'precio' => (float) $this->precio,
            'precio_costo' => $this->precio_costo !== null ? (float) $this->precio_costo : null,
            'activo' => (bool) $this->activo,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'imagenes' => ImagenProductoResource::collection($this->whenLoaded('imagenes')),
            'variantes' => VarianteProductoResource::collection($this->whenLoaded('variantes')),
        ];
    }
}
