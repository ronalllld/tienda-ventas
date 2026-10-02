<?php

use Illuminate\Support\Facades\Route;

// Ping de actividad para el contador de visitantes (ver /api/admin/estadisticas).
// Va antes del catch-all para que no lo capture esa ruta genérica.
Route::middleware('visitante')->get('/ping', function () {
    return response()->json(['ok' => true]);
});

// Panel admin: misma SPA, sin el middleware de visitantes (no se cuentan
// visitas internas del panel como tráfico de la tienda).
Route::get('/admin/{cualquier?}', function () {
    return view('app');
})->where('cualquier', '.*');

// Resto de rutas públicas de la tienda: sí quedan identificadas.
Route::middleware('visitante')->get('/{cualquier?}', function () {
    return view('app');
})->where('cualquier', '^(?!api|sanctum|storage|admin).*$');
