<?php

namespace Modules\HRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2000|max:2099',
            'month' => 'required|integer|min:1|max:12',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,approved,paid',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Nhân viên là bắt buộc',
            'employee_id.exists' => 'Nhân viên không tồn tại',
            'year.required' => 'Năm là bắt buộc',
            'month.required' => 'Tháng là bắt buộc',
            'base_salary.required' => 'Lương cơ bản là bắt buộc',
        ];
    }
}
