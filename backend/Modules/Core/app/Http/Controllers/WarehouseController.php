<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Http\Requests\StoreWarehouseRequest;
use Modules\Core\Http\Requests\UpdateWarehouseRequest;
use Modules\Core\Services\WarehouseService;

class WarehouseController extends BaseController
{
    protected $service;

    public function __construct(WarehouseService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $warehouses = $this->service->paginateWarehouses();
        return $this->successResponse($warehouses, 'Danh sách kho');
    }

    public function show($id)
    {
        $warehouse = $this->service->getWarehouse($id);
        return $this->successResponse(['warehouse' => $warehouse], 'Kho hàng');
    }

    public function store(StoreWarehouseRequest $request)
    {
        $warehouse = $this->service->createWarehouse($request->validated());
        return $this->successResponse(['warehouse' => $warehouse], 'Tạo kho thành công', 201);
    }

    public function update(UpdateWarehouseRequest $request, $id)
    {
        $warehouse = $this->service->updateWarehouse($id, $request->validated());
        return $this->successResponse(['warehouse' => $warehouse], 'Cập nhật kho thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteWarehouse($id);
        return $this->successResponse(null, 'Xóa kho thành công');
    }
}
