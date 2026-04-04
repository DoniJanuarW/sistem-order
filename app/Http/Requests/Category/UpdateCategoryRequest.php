<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       $categoryId = $this->route('id') ?? $this->route('category');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($categoryId)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Kategori wajib diisi.',
            'name.string' => 'Nama Kategori harus berupa teks.',
            'name.max' => 'Nama Kategori melebihi batas 255 karakter.',
            'name.unique' => 'Nama Kategori telah terdaftar',
        ];
    }
}
