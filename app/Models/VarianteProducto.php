<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VarianteProducto extends Model
{
    protected $table = 'variantes_producto';

    protected $fillable = [
        'producto_id',
        'talla',
        'color',
        'disponible',
    ];

    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
        ];
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'variante_id');
    }
}
