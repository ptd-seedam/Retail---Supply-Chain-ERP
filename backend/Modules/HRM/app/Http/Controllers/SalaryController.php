<?php

namespace Modules\HRM\Http\Controllers;

use Modules\Core\Http\Controllers\BaseController;
use Modules\HRM\Http\Requests\StoreSalaryRequest;
use Modules\HRM\Http\Requests\UpdateSalaryRequest;
use Modules\HRM\Services\SalaryService;

class SalaryController extends BaseController
{
    protected $service;

    public function __construct(SalaryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $salaries = $this->service->paginateSalaries();
        return $this->successResponse($salaries, 'Danh sách lương');
    }

    public function show($id)
    {
        $salary = $this->service->getSalary($id);
        return $this->successResponse(['salary' => $salary], 'Bảng lương');
    }

    public function store(StoreSalaryRequest $request)
    {
        $salary = $this->service->createSalary($request->validated());
        return $this->successResponse(['salary' => $salary], 'Tạo bảng lương thành công', 201);
    }

    public function update(UpdateSalaryRequest $request, $id)
    {
        $salary = $this->service->updateSalary($id, $request->validated());
        return $this->successResponse(['salary' => $salary], 'Cập nhật bảng lương thành công');
    }

    public function destroy($id)
    {
        $this->service->deleteSalary($id);
        return $this->successResponse(null, 'Xóa bảng lương thành công');
    }

    public function approveSalary($id)
    {
        $salary = $this->service->approveSalary($id);
        return $this->successResponse(['salary' => $salary], 'Phê duyệt bảng lương thành công');
    }

    public function markAsPaid($id)
    {
        $salary = $this->service->markAsPaid($id);
        return $this->successResponse(['salary' => $salary], 'Đánh dấu đã thanh toán thành công');
    }

    public function getByEmployee($employeeId)
    {
        $salaries = $this->service->getSalariesByEmployee($employeeId);
        return $this->successResponse($salaries, 'Danh sách lương nhân viên');
    }

    public function getPending()
    {
        $salaries = $this->service->getPendingSalaries();
        return $this->successResponse($salaries, 'Danh sách lương chờ phê duyệt');
    }

    public function getByMonth($year, $month)
    {
        $salaries = $this->service->getSalariesByMonth($year, $month);
        return $this->successResponse($salaries, "Danh sách lương tháng $month/$year");
    }
}
