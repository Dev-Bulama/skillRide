<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'status',
        'address',
        'city',
        'state',
        'date_of_birth',
        'gender',
        'next_of_kin_name',
        'next_of_kin_phone',
        'locale',
        'dark_mode',
        'is_verified',
        'verified_at',
        'last_login_at',
        'last_login_ip',
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
            'is_verified' => 'boolean',
            'facial_verified' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'dark_mode' => 'boolean',
            'last_login_at' => 'datetime',
            'date_of_birth' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Get the rider profile associated with the user.
     */
    public function riderProfile(): HasOne
    {
        return $this->hasOne(RiderProfile::class);
    }

    /**
     * Get the manager profile associated with the user.
     */
    public function managerProfile(): HasOne
    {
        return $this->hasOne(ManagerProfile::class);
    }

    /**
     * Get all vehicles owned by this user.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'owner_id');
    }

    /**
     * Get the vehicle assigned to this user as a rider.
     */
    public function assignedVehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class, 'assigned_rider_id');
    }

    /**
     * Get all vehicles managed by this user.
     */
    public function managedVehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'assigned_manager_id');
    }

    /**
     * Get all payments for this rider.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'rider_id');
    }

    /**
     * Get all work logs for this user.
     */
    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class, 'rider_id');
    }

    /**
     * Get all audit logs for this user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get GPS locations through the user's assigned vehicle.
     */
    public function gpsLocations(): HasManyThrough
    {
        return $this->hasManyThrough(
            GpsLocation::class,
            Vehicle::class,
            'assigned_rider_id', // Foreign key on vehicles table
            'vehicle_id',        // Foreign key on gps_locations table
            'id',                // Local key on users table
            'id'                 // Local key on vehicles table
        );
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope a query to only include admin users.
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope a query to only include manager users.
     */
    public function scopeManagers(Builder $query): Builder
    {
        return $query->where('role', 'manager');
    }

    /**
     * Scope a query to only include rider users.
     */
    public function scopeRiders(Builder $query): Builder
    {
        return $query->where('role', 'rider');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter users by a given role.
     */
    public function scopeByRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    // ──────────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────────

    /**
     * Determine if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if the user is a manager.
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Determine if the user is a rider.
     */
    public function isRider(): bool
    {
        return $this->role === 'rider';
    }

    /**
     * Determine if the user account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the full URL for the user's avatar.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        return Storage::url($this->avatar);
    }
}
