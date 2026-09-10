<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GuardarProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $producto = $this->route('producto');

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('productos', 'slug')->ignore($producto),
            ],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'precio_costo' => ['nullable', 'numeric', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'activo' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->slug && $this->nombre) {
            $this->merge(['slug' => Str::slug($this->nombre)]);
        }
    }
}
