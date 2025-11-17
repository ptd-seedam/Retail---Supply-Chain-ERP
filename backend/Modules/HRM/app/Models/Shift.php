<?php

namespace Modules\HRM\Models;

use Modules\Core\Entities\BaseModel;

class Shift extends BaseModel
{
    protected $table = 'shifts';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
