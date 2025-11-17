<?php

namespace Modules\CRM\Models;

use Modules\Core\Entities\BaseModel;

class LoyaltyPoint extends BaseModel
{
    protected $table = 'loyalty_points';

    protected $fillable = [
        'customer_id',
        'points',
        'value',
        'type',
        'ref_type',
        'ref_id',
        'notes',
    ];

    protected $casts = [
        'points' => 'integer',
        'value' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
