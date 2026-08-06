<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class GuardarCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoria = $this->route('categoria');

        return [
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('categorias', 'slug')->ignore($categoria),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->slug && $this->nombre) {
            $this->merge(['slug' => Str::slug($this->nombre)]);
        }
    }
}
