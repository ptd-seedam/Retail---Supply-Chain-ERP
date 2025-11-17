<?php

namespace Modules\HRM\Models;

use Modules\Core\Entities\BaseModel;
use App\Models\User;

class Employee extends BaseModel
{
    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'employee_code',
        'department_id',
        'shift_id',
        'phone',
        'address',
        'hire_date',
        'termination_date',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'termination_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }
}
