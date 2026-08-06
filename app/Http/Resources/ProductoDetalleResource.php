<?php

namespace App\Http\Resources;

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
            'activo' => (bool) $this->activo,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'imagenes' => ImagenProductoResource::collection($this->whenLoaded('imagenes')),
            'variantes' => VarianteProductoResource::collection($this->whenLoaded('variantes')),
        ];
    }
}
