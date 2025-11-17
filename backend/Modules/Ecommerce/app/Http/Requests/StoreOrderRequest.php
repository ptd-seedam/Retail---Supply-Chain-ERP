<?php

namespace Modules\Ecommerce\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => 'required|string|max:100|unique:orders,order_number',
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'status' => 'nullable|in:pending,processing,completed,cancelled,refunded',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'promotion_id' => 'nullable|exists:promotions,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'order_number.required' => 'Số đơn hàng là bắt buộc',
            'order_number.unique' => 'Số đơn hàng đã tồn tại',
            'items.required' => 'Đơn hàng phải có ít nhất 1 sản phẩm',
            'items.*.product_id.exists' => 'Sản phẩm không tồn tại',
            'items.*.quantity.required' => 'Số lượng là bắt buộc',
            'items.*.unit_price.required' => 'Giá bán là bắt buộc',
        ];
    }
}
