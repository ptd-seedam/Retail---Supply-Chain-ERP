<?php

namespace Modules\HRM\Services;

use Modules\HRM\Repositories\SalaryRepository;

class SalaryService
{
    protected $repository;

    public function __construct(SalaryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSalary($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getSalariesByEmployee($employeeId)
    {
        return $this->repository->getByEmployee($employeeId);
    }

    public function getSalaryByMonth($employeeId, $year, $month)
    {
        return $this->repository->getByEmployeeAndMonth($employeeId, $year, $month);
    }

    public function createSalary(array $data)
    {
        // Calculate total_salary
        $data['total_salary'] = $data['base_salary'] + ($data['bonus'] ?? 0) - ($data['deductions'] ?? 0);
        return $this->repository->create($data);
    }

    public function updateSalary($id, array $data)
    {
        if (isset($data['base_salary']) || isset($data['bonus']) || isset($data['deductions'])) {
            $salary = $this->repository->findOrFail($id);
            $baseSalary = $data['base_salary'] ?? $salary->base_salary;
            $bonus = $data['bonus'] ?? $salary->bonus;
            $deductions = $data['deductions'] ?? $salary->deductions;
            $data['total_salary'] = $baseSalary + $bonus - $deductions;
        }
        return $this->repository->update($id, $data);
    }

    public function deleteSalary($id)
    {
        return $this->repository->delete($id);
    }

    public function getPendingSalaries()
    {
        return $this->repository->getPending();
    }

    public function getApprovedSalaries()
    {
        return $this->repository->getApproved();
    }

    public function approveSalary($id)
    {
        return $this->updateSalary($id, ['status' => 'approved']);
    }

    public function markAsPaid($id, $paymentDate = null)
    {
        $data = ['status' => 'paid'];
        if ($paymentDate) {
            $data['payment_date'] = $paymentDate;
        }
        return $this->updateSalary($id, $data);
    }
}
