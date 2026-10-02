<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $fillable = [
        'visitor_id',
        'fecha',
        'ruta',
        'ultima_actividad',
    ];

    protected function casts(): array
    {
        return [
            // 'fecha' se guarda y se busca siempre como string "Y-m-d" plano
            // (updateOrCreate compara el valor crudo, sin pasar por el cast
            // de Eloquent, así que un cast 'date' aquí rompe el match).
            'ultima_actividad' => 'datetime',
        ];
    }
}
