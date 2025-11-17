<?php

namespace Modules\HRM\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\HRM\Models\Salary;

class SalaryRepository extends BaseRepository
{
    public function __construct(Salary $model)
    {
        parent::__construct($model);
    }

    public function getByEmployee($employeeId)
    {
        return $this->model->where('employee_id', $employeeId)->get();
    }

    public function getByEmployeeAndMonth($employeeId, $year, $month)
    {
        return $this->model
            ->where('employee_id', $employeeId)
            ->where('year', $year)
            ->where('month', $month)
            ->first();
    }

    public function getPending()
    {
        return $this->model->where('status', 'pending')->get();
    }

    public function getApproved()
    {
        return $this->model->where('status', 'approved')->get();
    }
}
