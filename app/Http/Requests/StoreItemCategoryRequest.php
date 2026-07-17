<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('item-category.create');
    }

    public function rules(): array
    {
        return [
            'category_code' => ['required', 'string', 'max:50', 'unique:item_categories,category_code'],
            'category_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_code.required' => 'Kode kategori wajib diisi.',
            'category_code.unique' => 'Kode kategori sudah digunakan.',
            'category_name.required' => 'Nama kategori wajib diisi.',
        ];
    }
}
