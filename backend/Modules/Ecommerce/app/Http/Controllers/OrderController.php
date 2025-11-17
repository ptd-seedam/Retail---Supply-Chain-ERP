<?php

namespace Modules\Ecommerce\Http\Controllers;

use Modules\Core\Http\Controllers\BaseController;
use Modules\Ecommerce\Http\Requests\StoreOrderRequest;
use Modules\Ecommerce\Http\Requests\UpdateOrderRequest;
use Modules\Ecommerce\Services\OrderService;

class OrderController extends BaseController
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $orders = $this->service->paginateOrders();
        return $this->successResponse($orders, 'Danh sách đơn hàng');
    }

    public function show($id)
    {
        $order = $this->service->getOrder($id);
        return $this->successResponse(['order' => $order], 'Đơn hàng');
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->service->createOrder($request->validated());
        return $this->successResponse(['order' => $order], 'Tạo đơn hàng thành công', 201);
    }

    public function update(UpdateOrderRequest $request, $id)
    {
        $order = $this->service->updateOrder($id, $request->validated());
        return $this->successResponse(['order' => $order], 'Cập nhật đơn hàng thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteOrder($id);
        return $this->successResponse(null, 'Xóa đơn hàng thành công');
    }

    public function complete($id)
    {
        $order = $this->service->completeOrder($id);
        return $this->successResponse(['order' => $order], 'Hoàn thành đơn hàng');
    }

    public function cancel($id)
    {
        $order = $this->service->cancelOrder($id);
        return $this->successResponse(['order' => $order], 'Hủy đơn hàng');
    }

    public function calculateTotal($id)
    {
        $total = $this->service->calculateTotal($id);
        return $this->successResponse(['total' => $total], 'Tính toán tổng tiền');
    }

    public function getByStatus($status)
    {
        $orders = $this->service->getOrdersByStatus($status);
        return $this->successResponse($orders, "Danh sách đơn hàng trạng thái: $status");
    }

    public function getByCustomer($customerId)
    {
        $orders = $this->service->getOrdersByCustomer($customerId);
        return $this->successResponse($orders, 'Danh sách đơn hàng khách hàng');
    }

    public function getPending()
    {
        $orders = $this->service->getPendingOrders();
        return $this->successResponse($orders, 'Danh sách đơn hàng chờ xử lý');
    }
}
