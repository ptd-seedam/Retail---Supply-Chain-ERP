<?php

namespace Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Đơn hàng là bắt buộc',
            'order_id.exists' => 'Đơn hàng không tồn tại',
            'product_id.required' => 'Sản phẩm là bắt buộc',
            'product_id.exists' => 'Sản phẩm không tồn tại',
            'quantity.required' => 'Số lượng là bắt buộc',
            'unit_price.required' => 'Giá bán là bắt buộc',
        ];
    }
}
