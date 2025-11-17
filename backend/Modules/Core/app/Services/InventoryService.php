<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\InventoryTransactionRepository;

class InventoryService
{
    protected $repository;

    public function __construct(InventoryTransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function recordIn($warehouseId, $productId, $quantity, $notes = null, $refType = null, $refId = null)
    {
        return $this->repository->create([
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'type' => 'IN',
            'quantity' => $quantity,
            'current_stock' => $quantity,
            'notes' => $notes,
            'ref_type' => $refType,
            'ref_id' => $refId,
        ]);
    }

    public function recordOut($warehouseId, $productId, $quantity, $notes = null, $refType = null, $refId = null)
    {
        return $this->repository->create([
            'warehouse_id' => $warehouseId,
            'product_id' => $productId,
            'type' => 'OUT',
            'quantity' => -$quantity,
            'current_stock' => -$quantity,
            'notes' => $notes,
            'ref_type' => $refType,
            'ref_id' => $refId,
        ]);
    }

    public function recordTransfer($fromWarehouseId, $toWarehouseId, $productId, $quantity, $notes = null)
    {
        // Record OUT from source
        $this->repository->create([
            'warehouse_id' => $fromWarehouseId,
            'product_id' => $productId,
            'type' => 'TRANSFER',
            'quantity' => -$quantity,
            'current_stock' => -$quantity,
            'notes' => $notes,
            'ref_type' => 'transfer',
        ]);

        // Record IN to destination
        return $this->repository->create([
            'warehouse_id' => $toWarehouseId,
            'product_id' => $productId,
            'type' => 'TRANSFER',
            'quantity' => $quantity,
            'current_stock' => $quantity,
            'notes' => $notes,
            'ref_type' => 'transfer',
        ]);
    }

    public function getHistory($warehouseId, $productId, $limit = 50)
    {
        return $this->repository->getHistory($warehouseId, $productId, $limit);
    }

    public function getByWarehouse($warehouseId)
    {
        return $this->repository->getByWarehouse($warehouseId);
    }

    public function getByProduct($productId)
    {
        return $this->repository->getByProduct($productId);
    }
}
