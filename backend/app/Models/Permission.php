<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Permission Model
 *
 * Represents a permission in the system (e.g., view_users, create_products)
 */
class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'action',
    ];

    /**
     * Get the roles that have this permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'permission_role',
            'permission_id',
            'role_id'
        )
            ->withTimestamps()
            ->withPivot('assigned_at');
    }

    /**
     * Get the users that have this permission (through roles)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'permission_role',
            'permission_id',
            'user_id'
        );
    }

    /**
     * Get all permissions grouped by module
     */
    public static function groupedByModule()
    {
        return self::whereNotNull('module')
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module');
    }

    /**
     * Scope: Get permissions by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope: Get permissions by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }
}
