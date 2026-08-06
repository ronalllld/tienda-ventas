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
            'talla' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
