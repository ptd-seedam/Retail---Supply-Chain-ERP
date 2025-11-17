<?php

namespace Modules\CMS\Models;

use Modules\Core\Entities\BaseModel;

class News extends BaseModel
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'views',
        'status',
        'published_at',
    ];

    protected $casts = [
        'views' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
