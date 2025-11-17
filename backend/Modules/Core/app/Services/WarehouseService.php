<?php

namespace Modules\Core\Services;

use Modules\Core\Repositories\WarehouseRepository;

class WarehouseService
{
    protected $repository;

    public function __construct(WarehouseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllWarehouses()
    {
        return $this->repository->all();
    }

    public function getActiveWarehouses()
    {
        return $this->repository->getActive();
    }

    public function getWarehouse($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function createWarehouse(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateWarehouse($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteWarehouse($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateWarehouses($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }
}
