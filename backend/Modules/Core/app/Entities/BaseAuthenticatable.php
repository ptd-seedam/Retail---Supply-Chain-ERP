<?php

namespace Modules\Core\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class BaseAuthenticatable extends Authenticatable
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Modules\Core\Traits\UserAuditTrait;
}
