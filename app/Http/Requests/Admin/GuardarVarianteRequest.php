<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GuardarVarianteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'talla' => ['nullable', 'string', 'max:50'],
            'color' => ['required', 'string', 'max:50'],
            'disponible' => ['sometimes', 'boolean'],
        ];
    }
}
