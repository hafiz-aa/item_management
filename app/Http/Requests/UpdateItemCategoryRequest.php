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
            'cati_code' => ['required', 'string', 'max:50', Rule::unique('category_item', 'cati_code')->ignore($categoryId, 'cati_id')],
            'cati_name' => ['required', 'string', 'max:100'],
            'cati_notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'cati_code.required' => 'Kode kategori wajib diisi.',
            'cati_code.unique' => 'Kode kategori sudah digunakan.',
            'cati_name.required' => 'Nama kategori wajib diisi.',
        ];
    }
}
