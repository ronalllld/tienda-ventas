<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ImagenProducto extends Model
{
    protected $table = 'imagenes_producto';

    protected $fillable = [
        'producto_id',
        'ruta',
        'orden',
    ];

    protected $appends = ['url'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(function () {
            if (str_starts_with($this->ruta, 'http://') || str_starts_with($this->ruta, 'https://')) {
                return $this->ruta;
            }

            return Storage::disk('public')->url($this->ruta);
        });
    }
}
