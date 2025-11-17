<?php

namespace Modules\HRM\Models;

use Modules\Core\Entities\BaseModel;

class Department extends BaseModel
{
    protected $table = 'departments';

    protected $fillable = [
        'name',
        'code',
        'parent_id',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
