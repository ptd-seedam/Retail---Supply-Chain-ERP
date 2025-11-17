<?php

namespace Modules\HRM\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\HRM\Models\Employee;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByDepartment($departmentId)
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    public function getByCode($code)
    {
        return $this->model->where('employee_code', $code)->first();
    }

    public function getWithRelations()
    {
        return $this->model->with(['user', 'department', 'shift'])->get();
    }
}
