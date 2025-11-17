<?php

namespace Modules\HRM\Services;

use Modules\HRM\Repositories\EmployeeRepository;

class EmployeeService
{
    protected $repository;

    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllEmployees()
    {
        return $this->repository->all();
    }

    public function getActiveEmployees()
    {
        return $this->repository->getActive();
    }

    public function getEmployee($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getEmployeesByDepartment($departmentId)
    {
        return $this->repository->getByDepartment($departmentId);
    }

    public function createEmployee(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateEmployee($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteEmployee($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateEmployees($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getEmployeesWithRelations()
    {
        return $this->repository->getWithRelations();
    }
}
