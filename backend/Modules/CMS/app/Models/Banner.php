<?php

namespace Modules\CMS\Models;

use Modules\Core\Entities\BaseModel;

class Banner extends BaseModel
{
    protected $table = 'banners';

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'sort_order',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
