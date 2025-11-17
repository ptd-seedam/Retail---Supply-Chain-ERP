<?php

namespace Modules\Core\Models;

use Modules\Core\Entities\BaseModel;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'category_id',
        'cost_price',
        'selling_price',
        'reorder_level',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
