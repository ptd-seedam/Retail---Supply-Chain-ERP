<?php

namespace Modules\HRM\Http\Controllers;

use Modules\Core\Http\Controllers\BaseController;
use Modules\HRM\Http\Requests\StoreEmployeeRequest;
use Modules\HRM\Http\Requests\UpdateEmployeeRequest;
use Modules\HRM\Services\EmployeeService;

class EmployeeController extends BaseController
{
    protected $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $employees = $this->service->paginateEmployees();
        return $this->successResponse($employees, 'Danh sách nhân viên');
    }

    public function show($id)
    {
        $employee = $this->service->getEmployee($id);
        return $this->successResponse(['employee' => $employee], 'Nhân viên');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = $this->service->createEmployee($request->validated());
        return $this->successResponse(['employee' => $employee], 'Tạo nhân viên thành công', 201);
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->service->updateEmployee($id, $request->validated());
        return $this->successResponse(['employee' => $employee], 'Cập nhật nhân viên thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteEmployee($id);
        return $this->successResponse(null, 'Xóa nhân viên thành công');
    }

    public function getByDepartment($departmentId)
    {
        $employees = $this->service->getEmployeesByDepartment($departmentId);
        return $this->successResponse($employees, 'Danh sách nhân viên theo phòng ban');
    }

    public function getByShift($shiftId)
    {
        $employees = $this->service->getEmployeesByShift($shiftId);
        return $this->successResponse($employees, 'Danh sách nhân viên theo ca');
    }

    public function getActive()
    {
        $employees = $this->service->getActiveEmployees();
        return $this->successResponse($employees, 'Danh sách nhân viên hoạt động');
    }

    public function getOnLeave()
    {
        $employees = $this->service->getOnLeaveEmployees();
        return $this->successResponse($employees, 'Danh sách nhân viên đang nghỉ phép');
    }
}
