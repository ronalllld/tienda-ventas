<?php

use App\Http\Controllers\Api\Admin\CategoriaController;
use App\Http\Controllers\Api\Admin\ImagenProductoController;
use App\Http\Controllers\Api\Admin\ProductoController;
use App\Http\Controllers\Api\Admin\SesionAdminController;
use App\Http\Controllers\Api\Admin\VarianteProductoController;
use App\Http\Controllers\Api\Admin\VentaController;
use App\Http\Controllers\Api\Publico\CategoriaPublicaController;
use App\Http\Controllers\Api\Publico\ProductoPublicoController;
use Illuminate\Support\Facades\Route;

// Rutas públicas del catálogo (sin autenticación)
Route::get('/categorias', [CategoriaPublicaController::class, 'index']);
Route::get('/productos', [ProductoPublicoController::class, 'index']);
Route::get('/productos/{idOSlug}', [ProductoPublicoController::class, 'show']);

// Rutas de administración
Route::prefix('admin')->group(function () {
    Route::post('/login', [SesionAdminController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [SesionAdminController::class, 'logout']);
        Route::get('/yo', [SesionAdminController::class, 'yo']);

        Route::apiResource('categorias', CategoriaController::class)->parameters([
            'categorias' => 'categoria',
        ]);

        Route::apiResource('productos', ProductoController::class)->parameters([
            'productos' => 'producto',
        ]);

        Route::post('/productos/{producto}/variantes', [VarianteProductoController::class, 'store']);
        Route::put('/variantes/{variante}', [VarianteProductoController::class, 'update']);
        Route::delete('/variantes/{variante}', [VarianteProductoController::class, 'destroy']);

        Route::post('/productos/{producto}/imagenes', [ImagenProductoController::class, 'store']);
        Route::delete('/imagenes/{imagen}', [ImagenProductoController::class, 'destroy']);

        Route::get('/ventas', [VentaController::class, 'index']);
    });
});
