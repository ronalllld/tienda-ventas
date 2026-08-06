<?php

namespace App\Http\Controllers\Api\Publico;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;

class CategoriaPublicaController extends Controller
{
    public function index()
    {
        return CategoriaResource::collection(
            Categoria::orderBy('nombre')->get()
        );
    }
}
