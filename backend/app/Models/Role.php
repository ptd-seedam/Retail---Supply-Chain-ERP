<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Role Model
 *
 * Represents a role in the system (e.g., Admin, Manager, Staff)
 */
class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Get the permissions associated with this role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',
            'permission_id'
        )
            ->withTimestamps()
            ->withPivot('assigned_at');
    }

    /**
     * Get the users associated with this role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'role_id',
            'user_id'
        )
            ->withTimestamps()
            ->withPivot('assigned_at', 'assigned_by_id');
    }

    /**
     * Check if role has permission by slug
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()
            ->where('slug', $permissionSlug)
            ->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()
            ->whereIn('slug', $permissionSlugs)
            ->exists();
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $count = $this->permissions()
            ->whereIn('slug', $permissionSlugs)
            ->count();

        return $count === count($permissionSlugs);
    }

    /**
     * Get all permission slugs for this role
     */
    public function getPermissionSlugs(): array
    {
        return $this->permissions()
            ->pluck('slug')
            ->toArray();
    }

    /**
     * Attach permission to role
     */
    public function grantPermission(Permission $permission): void
    {
        if (!$this->hasPermission($permission->slug)) {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Detach permission from role
     */
    public function revokePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission->id);
    }

    /**
     * Scope: Get only active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get roles by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->whereHas('permissions', function ($q) use ($module) {
            $q->where('module', $module);
        });
    }
}
