<?php

namespace Modules\Core\Models;

use Modules\Core\Entities\BaseModel;

class Warehouse extends BaseModel
{
    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'location',
        'description',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
