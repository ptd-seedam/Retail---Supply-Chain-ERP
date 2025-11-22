<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\BaseAuthenticatable;

/**
 * @method bool hasRole(string $roleSlug)
 * @method bool hasAnyRole(array $roleSlugs)
 * @method bool hasAllRoles(array $roleSlugs)
 * @method bool hasPermission(string $permissionSlug)
 * @method bool hasAnyPermission(array $permissionSlugs)
 * @method bool hasAllPermissions(array $permissionSlugs)
 * @method array getAllPermissions()
 * @method array getRoleSlugs()
 * @method void assignRole(\App\Models\Role $role, int $userId)
 * @method void syncRoles(array $roleIds)
 */
class User extends BaseAuthenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'roles' => $this->roles()->pluck('slug')->toArray(),
            'permissions' => $this->getAllPermissions(),
        ];
    }

    /**
     * Get the roles associated with this user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        )
            ->withTimestamps()
            ->withPivot('assigned_at', 'assigned_by_id');
    }

    /**
     * Get the permissions associated with this user (through roles)
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'user_id',
            'permission_id'
        );
    }

    /**
     * Check if user has a specific role by slug
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()
            ->where('slug', $roleSlug)
            ->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()
            ->whereIn('slug', $roleSlugs)
            ->exists();
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roleSlugs): bool
    {
        $count = $this->roles()
            ->whereIn('slug', $roleSlugs)
            ->count();

        return $count === count($roleSlugs);
    }

    /**
     * Check if user has a specific permission by slug
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Check direct permissions
        if ($this->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Check permissions through roles
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug);
            })
            ->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($q) use ($permissionSlugs) {
                $q->whereIn('slug', $permissionSlugs);
            })
            ->exists();
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        foreach ($permissionSlugs as $slug) {
            if (!$this->hasPermission($slug)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions for this user (through roles)
     */
    public function getAllPermissions(): array
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('slug')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Get all role slugs for this user
     */
    public function getRoleSlugs(): array
    {
        return $this->roles()
            ->pluck('slug')
            ->toArray();
    }

    /**
     * Assign a role to user
     */
    public function assignRole(Role $role, ?int $assignedById = null): void
    {
        if (!$this->hasRole($role->slug)) {
            $this->roles()->attach($role->id, [
                'assigned_by_id' => $assignedById ?? Auth::id(),
            ]);
        }
    }

    /**
     * Remove a role from user
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Synchronize roles for user
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }
}
