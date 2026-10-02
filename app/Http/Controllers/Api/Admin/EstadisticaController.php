<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PicoDiario;
use App\Models\Visita;

class EstadisticaController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        $conectados = Visita::where('fecha', $hoy)
            ->where('ultima_actividad', '>=', now()->subMinutes(5))
            ->count();

        $visitantesHoy = Visita::where('fecha', $hoy)->count();

        $picoHoy = PicoDiario::where('fecha', $hoy)->first();
        $record = PicoDiario::orderByDesc('maximo')->first();

        return response()->json([
            'conectados' => $conectados,
            'visitantes_hoy' => $visitantesHoy,
            'pico_hoy' => $picoHoy ? [
                'maximo' => $picoHoy->maximo,
                'hora' => $picoHoy->hora,
            ] : null,
            'record' => $record ? [
                'maximo' => $record->maximo,
                'fecha' => $record->fecha,
            ] : null,
        ]);
    }
}
