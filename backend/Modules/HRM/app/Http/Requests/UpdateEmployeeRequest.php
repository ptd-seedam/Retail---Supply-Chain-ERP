<?php

namespace Modules\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('employee');

        return [
            'user_id' => 'required|exists:users,id',
            'employee_code' => "required|string|max:50|unique:employees,employee_code,{$id}",
            'department_id' => 'nullable|exists:departments,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'termination_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,on_leave',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Người dùng là bắt buộc',
            'user_id.exists' => 'Người dùng không tồn tại',
            'employee_code.required' => 'Mã nhân viên là bắt buộc',
            'employee_code.unique' => 'Mã nhân viên đã tồn tại',
        ];
    }
}
