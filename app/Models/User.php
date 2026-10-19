<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
        'is_admin',
        'role_id',
        'phone',
        'avatar',
        'status',
        'custom_permissions',
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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'active',
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
            'is_admin' => 'boolean',
            'custom_permissions' => 'array',
        ];
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the avatar URL or null.
     */
    public function avatarUrl(): ?string
    {
        if ($this->avatar) {
            return storage_asset($this->avatar);
        }

        return null;
    }

    /**
     * Check if user is a system administrator.
     */
    public function isSystemAdmin(): bool
    {
        if (($this->status ?? 'active') !== 'active') {
            return false;
        }

        // If explicit role is system-admin
        if ($this->role && $this->role->slug === 'system-admin') {
            return true;
        }

        // Fallback for primary/legacy admin user
        return (bool) $this->is_admin && empty($this->role_id);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if (($this->status ?? 'active') !== 'active') {
            return false;
        }

        if ($this->isSystemAdmin()) {
            return true;
        }

        // Check user custom permissions override (explicit grant or revoke)
        $custom = $this->custom_permissions;
        if (is_array($custom) && array_key_exists($permission, $custom)) {
            return (bool) $custom[$permission];
        }

        // Check role permissions
        if ($this->role) {
            return $this->role->hasPermission($permission);
        }

        // Legacy fallback if marked as admin but role not assigned
        return (bool) $this->is_admin;
    }

    /**
     * Check if user can access the admin dashboard.
     */
    public function canAccessAdmin(): bool
    {
        return ($this->status ?? 'active') === 'active' && ($this->is_admin || $this->role_id !== null);
    }
}
