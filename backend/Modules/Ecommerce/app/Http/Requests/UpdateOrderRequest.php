<?php

namespace Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('order');

        return [
            'order_number' => "required|string|max:100|unique:orders,order_number,{$id}",
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'status' => 'nullable|in:pending,processing,completed,cancelled,refunded',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'promotion_id' => 'nullable|exists:promotions,id',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_number.required' => 'Số đơn hàng là bắt buộc',
            'order_number.unique' => 'Số đơn hàng đã tồn tại',
        ];
    }
}
