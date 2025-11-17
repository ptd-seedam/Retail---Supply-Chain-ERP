<?php

namespace Modules\HRM\Models;

use Modules\Core\Entities\BaseModel;

class Salary extends BaseModel
{
    protected $table = 'salaries';

    protected $fillable = [
        'employee_id',
        'year',
        'month',
        'base_salary',
        'bonus',
        'deductions',
        'total_salary',
        'status',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'payment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
