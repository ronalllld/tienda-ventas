<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VarianteProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'talla' => $this->talla,
            'color' => $this->color,
            'disponible' => (bool) $this->disponible,
        ];
    }
}
