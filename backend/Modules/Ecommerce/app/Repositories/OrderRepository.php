<?php

namespace Modules\Ecommerce\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Ecommerce\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getByCustomer($customerId)
    {
        return $this->model->where('customer_id', $customerId)->get();
    }

    public function getByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    public function getByOrderNumber($orderNumber)
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }

    public function getPending()
    {
        return $this->model->where('status', 'pending')->get();
    }

    public function getProcessing()
    {
        return $this->model->where('status', 'processing')->get();
    }

    public function getCompleted()
    {
        return $this->model->where('status', 'completed')->get();
    }

    public function getWithItems()
    {
        return $this->model->with(['orderItems.product'])->get();
    }
}
