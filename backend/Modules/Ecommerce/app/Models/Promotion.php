<?php

namespace Modules\Ecommerce\Models;

use Modules\Core\Entities\BaseModel;

class Promotion extends BaseModel
{
    protected $table = 'promotions';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'discount_value',
        'max_usage',
        'current_usage',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
