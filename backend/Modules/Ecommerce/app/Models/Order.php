<?php

namespace Modules\Ecommerce\Models;

use Modules\Core\Entities\BaseModel;
use Modules\CRM\Models\Customer;
use Modules\Core\Models\Warehouse;

class Order extends BaseModel
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'warehouse_id',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'promotion_id',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
