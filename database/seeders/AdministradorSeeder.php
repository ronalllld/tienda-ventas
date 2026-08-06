<?php

namespace Database\Seeders;

use App\Models\Administrador;
use Illuminate\Database\Seeder;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        Administrador::query()->updateOrCreate(
            ['correo' => 'admin@tienda.com'],
            [
                'nombre' => 'Administrador',
                'contrasena' => 'admin123',
            ]
        );
    }
}
