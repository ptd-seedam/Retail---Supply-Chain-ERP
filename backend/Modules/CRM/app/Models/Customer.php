<?php

namespace Modules\CRM\Models;

use Modules\Core\Entities\BaseModel;

class Customer extends BaseModel
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'group_id',
        'credit_limit',
        'current_balance',
        'notes',
        'status',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(CustomerGroup::class, 'group_id');
    }

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }
}
