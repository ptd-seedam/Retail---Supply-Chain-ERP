<?php

namespace Modules\Ecommerce\Services;

use Modules\Ecommerce\Repositories\OrderRepository;
use Modules\Ecommerce\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllOrders()
    {
        return $this->repository->all();
    }

    public function getOrder($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getOrdersByCustomer($customerId)
    {
        return $this->repository->getByCustomer($customerId);
    }

    public function getOrdersByStatus($status)
    {
        return $this->repository->getByStatus($status);
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $order = $this->repository->create($data);
            return $order;
        });
    }

    public function updateOrder($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateOrders($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function completeOrder($id)
    {
        $order = $this->getOrder($id);

        return DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            return $order;
        });
    }

    public function cancelOrder($id)
    {
        $order = $this->getOrder($id);

        return DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);
            return $order;
        });
    }

    public function calculateTotal($orderId)
    {
        $order = $this->getOrder($orderId);

        $itemsTotal = $order->orderItems()->sum(DB::raw('quantity * unit_price'));
        $discountAmount = $order->discount_amount;
        $taxAmount = $order->tax_amount;

        $total = $itemsTotal - $discountAmount + $taxAmount;

        $order->update([
            'subtotal' => $itemsTotal,
            'total_amount' => $total,
        ]);

        return $order;
    }
}
