<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SesionAdminController extends Controller
{
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'correo' => ['required', 'email'],
            'contrasena' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['correo' => $credenciales['correo'], 'password' => $credenciales['contrasena']])) {
            throw ValidationException::withMessages([
                'correo' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return response()->json(['administrador' => $this->datosAdministrador($request)]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function yo(Request $request)
    {
        return response()->json(['administrador' => $this->datosAdministrador($request)]);
    }

    private function datosAdministrador(Request $request): array
    {
        return $request->user()->only(['id', 'nombre', 'correo']);
    }
}
