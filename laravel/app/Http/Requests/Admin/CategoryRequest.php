<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $categoryId = $this->route('category')?->getKey();

        return [
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:100', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:16'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'slug' => 'slug',
            'description' => 'descripción',
            'image' => 'imagen',
            'icon' => 'icono',
            'color' => 'color',
            'active' => 'activo',
            'sort_order' => 'orden',
        ];
    }

    public function messages(): array
    {
        return [
            'color.regex' => 'El color debe tener formato hexadecimal, por ejemplo #FF5500.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function validatedAttributes(): array
    {
        $data = $this->safe([
            'name',
            'slug',
            'description',
            'image',
            'icon',
            'color',
        ]);

        $data['active'] = $this->boolean('active');
        $data['sort_order'] = (int) $this->input('sort_order', 0);

        return $data;
    }
}
