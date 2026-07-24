<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', 'exists:branch,branch_id'],
            'tth_date' => ['required', 'date'],
            'branch_id_to' => ['required', 'integer', 'exists:branch,branch_id', 'different:branch_id'],
            'tth_status' => ['required', 'string', 'in:0,1,2'],
            'emp_id_sender' => ['required', 'integer', 'exists:employee,emp_id'],
            'tth_notes' => ['nullable', 'string', 'max:255'],
            'details' => ['nullable', 'array'],
            'details.*.itemd_id' => ['required_with:details', 'integer', 'exists:item_detail,itemd_id'],
            'details.*.whsl_id_from' => ['required_with:details', 'integer', 'exists:warehouse_location,whsl_id'],
            'details.*.ttd_status' => ['nullable', 'string', 'in:0,1'],
            'details.*.ttd_notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch asal wajib dipilih.',
            'tth_date.required' => 'Tanggal transfer wajib diisi.',
            'branch_id_to.required' => 'Branch tujuan wajib dipilih.',
            'branch_id_to.different' => 'Branch tujuan harus berbeda dengan branch asal.',
            'tth_status.required' => 'Status wajib dipilih.',
            'emp_id_sender.required' => 'Employee pengirim wajib dipilih.',
            'details.*.itemd_id.required_with' => 'Item wajib dipilih.',
            'details.*.whsl_id_from.required_with' => 'Warehouse asal wajib dipilih.',
        ];
    }
}
