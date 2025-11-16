<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Auth;

trait UserAuditTrait
{
    protected static function bootUserAuditTrait()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by_id = Auth::id();
                $model->updated_by_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by_id = Auth::id();
            }
        });

        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->deleted_by_id = Auth::id();
                $model->save();
            }
        });
    }
}
