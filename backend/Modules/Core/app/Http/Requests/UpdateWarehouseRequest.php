<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('warehouse');

        return [
            'code' => "required|string|max:50|unique:warehouses,code,{$id}",
            'name' => 'required|string|max:191',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Mã kho là bắt buộc',
            'code.unique' => 'Mã kho đã tồn tại',
            'name.required' => 'Tên kho là bắt buộc',
        ];
    }
}
