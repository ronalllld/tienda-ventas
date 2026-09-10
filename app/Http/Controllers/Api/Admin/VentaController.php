<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VentaResource;
use App\Models\Venta;
use Illuminate\Support\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::query()->orderByDesc('created_at')->get();

        $inicioMes = Carbon::now()->startOfMonth();
        $ventasDelMes = $ventas->filter(fn (Venta $venta) => $venta->created_at->greaterThanOrEqualTo($inicioMes));

        $resumen = [
            'cantidad_total' => $ventas->count(),
            'ingresos_total' => (float) $ventas->sum('precio_venta'),
            'ganancia_total' => (float) $ventas->sum('ganancia'),
            'cantidad_mes' => $ventasDelMes->count(),
            'ingresos_mes' => (float) $ventasDelMes->sum('precio_venta'),
            'ganancia_mes' => (float) $ventasDelMes->sum('ganancia'),
        ];

        return VentaResource::collection($ventas)->additional(['resumen' => $resumen]);
    }
}
