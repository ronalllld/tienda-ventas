<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicoDiario extends Model
{
    protected $table = 'picos_diarios';

    protected $fillable = [
        'fecha',
        'maximo',
        'hora',
    ];

    protected function casts(): array
    {
        return [
            // Igual que en Visita: 'fecha' se guarda y se busca como string
            // "Y-m-d" plano, sin cast (ver nota en App\Models\Visita).
            'maximo' => 'integer',
        ];
    }
}
