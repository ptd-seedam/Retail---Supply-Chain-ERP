<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\InventoryTransaction;

class InventoryTransactionRepository extends BaseRepository
{
    public function __construct(InventoryTransaction $model)
    {
        parent::__construct($model);
    }

    public function getByWarehouse($warehouseId)
    {
        return $this->model->where('warehouse_id', $warehouseId)->get();
    }

    public function getByProduct($productId)
    {
        return $this->model->where('product_id', $productId)->get();
    }

    public function getByType($type)
    {
        return $this->model->where('type', $type)->get();
    }

    public function getByRef($refType, $refId)
    {
        return $this->model
            ->where('ref_type', $refType)
            ->where('ref_id', $refId)
            ->get();
    }

    public function getHistory($warehouseId, $productId, $limit = 50)
    {
        return $this->model
            ->where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
