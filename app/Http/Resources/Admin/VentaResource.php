<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VentaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'producto_id' => $this->producto_id,
            'nombre_producto' => $this->nombre_producto,
            'talla' => $this->talla,
            'color' => $this->color,
            'precio_venta' => (float) $this->precio_venta,
            'precio_costo' => $this->precio_costo !== null ? (float) $this->precio_costo : null,
            'ganancia' => $this->ganancia,
            'fecha' => $this->created_at->toIso8601String(),
        ];
    }
}
