<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Core\Http\Controllers\BaseController;
use Modules\Ecommerce\Http\Requests\StoreOrderItemRequest;
use Modules\Ecommerce\Http\Requests\UpdateOrderItemRequest;

class OrderItemController extends BaseController
{
    public function index($orderId)
    {
        $orderItems = \Modules\Ecommerce\Models\OrderItem::where('order_id', $orderId)->paginate();
        return $this->successResponse($orderItems, 'Danh sách sản phẩm đơn hàng');
    }

    public function show($orderId, $itemId)
    {
        $item = \Modules\Ecommerce\Models\OrderItem::findOrFail($itemId);
        if ($item->order_id != $orderId) {
            return $this->errorResponse('Sản phẩm không thuộc đơn hàng này', 404);
        }
        return $this->successResponse(['item' => $item], 'Chi tiết sản phẩm');
    }

    public function store(StoreOrderItemRequest $request, $orderId)
    {
        $validated = $request->validated();
        $validated['order_id'] = $orderId;

        $item = \Modules\Ecommerce\Models\OrderItem::create($validated);
        return $this->successResponse(['item' => $item], 'Thêm sản phẩm vào đơn hàng thành công', 201);
    }

    public function update(UpdateOrderItemRequest $request, $orderId, $itemId)
    {
        $item = \Modules\Ecommerce\Models\OrderItem::findOrFail($itemId);
        if ($item->order_id != $orderId) {
            return $this->errorResponse('Sản phẩm không thuộc đơn hàng này', 404);
        }

        $item->update($request->validated());
        return $this->successResponse(['item' => $item], 'Cập nhật sản phẩm thành công');
    }

    public function destroy($orderId, $itemId)
    {
        $item = \Modules\Ecommerce\Models\OrderItem::findOrFail($itemId);
        if ($item->order_id != $orderId) {
            return $this->errorResponse('Sản phẩm không thuộc đơn hàng này', 404);
        }

        $item->delete();
        return $this->successResponse(null, 'Xóa sản phẩm khỏi đơn hàng thành công');
    }
}
