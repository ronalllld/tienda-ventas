<?php

use App\Models\PicoDiario;
use App\Models\Visita;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Actualiza el pico de conectados simultáneos del día, si lo que hay ahora lo supera.
Schedule::call(function () {
    $hoy = now()->toDateString();

    $conectados = Visita::where('fecha', $hoy)
        ->where('ultima_actividad', '>=', now()->subMinutes(5))
        ->count();

    if ($conectados === 0) {
        return;
    }

    $pico = PicoDiario::firstOrNew(['fecha' => $hoy]);

    if ($conectados > $pico->maximo) {
        $pico->maximo = $conectados;
        $pico->hora = now()->format('H:i:s');
        $pico->save();
    }
})->everyMinute()->name('actualizar-pico-visitantes')->withoutOverlapping();

// Limpia visitas viejas para no acumular filas indefinidamente.
Schedule::call(function () {
    Visita::where('fecha', '<', now()->subDays(90)->toDateString())->delete();
})->daily()->name('limpiar-visitas-antiguas');
