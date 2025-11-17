<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required' => 'SKU là bắt buộc',
            'sku.unique' => 'SKU đã tồn tại',
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'cost_price.required' => 'Giá vốn là bắt buộc',
            'selling_price.required' => 'Giá bán là bắt buộc',
        ];
    }
}
