<?php

namespace Modules\CRM\Models;

use Modules\Core\Entities\BaseModel;

class CustomerGroup extends BaseModel
{
    protected $table = 'customer_groups';

    protected $fillable = [
        'name',
        'description',
        'discount_percent',
        'status',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'group_id');
    }
}
