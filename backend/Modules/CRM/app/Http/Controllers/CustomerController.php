<?php

namespace Modules\CRM\Http\Controllers;

use Modules\Core\Http\Controllers\BaseController;
use Modules\CRM\Http\Requests\StoreCustomerRequest;
use Modules\CRM\Http\Requests\UpdateCustomerRequest;
use Modules\CRM\Services\CustomerService;

class CustomerController extends BaseController
{
    protected $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $customers = $this->service->paginateCustomers();
        return $this->successResponse($customers, 'Danh sách khách hàng');
    }

    public function show($id)
    {
        $customer = $this->service->getCustomer($id);
        return $this->successResponse(['customer' => $customer], 'Khách hàng');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = $this->service->createCustomer($request->validated());
        return $this->successResponse(['customer' => $customer], 'Tạo khách hàng thành công', 201);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->service->updateCustomer($id, $request->validated());
        return $this->successResponse(['customer' => $customer], 'Cập nhật khách hàng thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteCustomer($id);
        return $this->successResponse(null, 'Xóa khách hàng thành công');
    }

    public function getByGroup($groupId)
    {
        $customers = $this->service->getCustomersByGroup($groupId);
        return $this->successResponse($customers, 'Danh sách khách hàng theo nhóm');
    }

    public function getActive()
    {
        $customers = $this->service->getActiveCustomers();
        return $this->successResponse($customers, 'Danh sách khách hàng hoạt động');
    }

    public function getSuspended()
    {
        $customers = $this->service->getSuspendedCustomers();
        return $this->successResponse($customers, 'Danh sách khách hàng bị tạm dừng');
    }

    public function getHighValue()
    {
        $customers = $this->service->getHighValueCustomers();
        return $this->successResponse($customers, 'Danh sách khách hàng giá trị cao');
    }
}
