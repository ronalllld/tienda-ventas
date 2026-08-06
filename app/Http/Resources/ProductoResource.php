<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'precio' => (float) $this->precio,
            'activo' => (bool) $this->activo,
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'imagen_principal' => optional($this->imagenes->first())->url,
        ];
    }
}
