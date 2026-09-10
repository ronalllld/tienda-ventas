<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    protected $fillable = [
        'producto_id',
        'variante_id',
        'nombre_producto',
        'talla',
        'color',
        'precio_venta',
        'precio_costo',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'decimal:2',
            'precio_costo' => 'decimal:2',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function variante(): BelongsTo
    {
        return $this->belongsTo(VarianteProducto::class, 'variante_id');
    }

    public function getGananciaAttribute(): float
    {
        return (float) $this->precio_venta - (float) ($this->precio_costo ?? 0);
    }
}
