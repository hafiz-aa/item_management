<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('item-category.edit');
    }

    public function rules(): array
    {
        $categoryId = $this->route('itemCategory');

        return [
            'category_code' => ['required', 'string', 'max:50', Rule::unique('item_categories', 'category_code')->ignore($categoryId, 'category_id')],
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
