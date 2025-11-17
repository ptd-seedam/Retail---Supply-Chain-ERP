<?php

namespace Modules\Core\Models;

use Modules\Core\Entities\BaseModel;

class InventoryTransaction extends BaseModel
{
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'type',
        'quantity',
        'current_stock',
        'notes',
        'ref_type',
        'ref_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'current_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
