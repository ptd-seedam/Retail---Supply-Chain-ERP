<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\UserAuditTrait;

abstract class BaseModel extends Model
{
    use SoftDeletes, UserAuditTrait;
}
