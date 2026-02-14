<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'registration_number',
        'vin',
        'make',
        'model',
        'year',
        'color',
        'type',
        'status',
        'fuel_type',
        'engine_number',
        'chassis_number',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_expiry',
        'road_worthiness_expiry',
        'purchased_at',
        'purchase_price',
        'current_mileage',
        'current_latitude',
        'current_longitude',
        'last_location_update',
        'owner_id',
        'assigned_rider_id',
        'assigned_manager_id',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_location_update' => 'datetime',
            'insurance_expiry' => 'date',
            'road_worthiness_expiry' => 'date',
            'purchased_at' => 'date',
            'purchase_price' => 'decimal:2',
            'current_latitude' => 'float',
            'current_longitude' => 'float',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Get the rider assigned to this vehicle.
     */
    public function assignedRider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_rider_id');
    }

    /**
     * Get the manager assigned to this vehicle.
     */
    public function assignedManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    /**
     * Get the owner of this vehicle.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all maintenance records for this vehicle.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    /**
     * Get all GPS location records for this vehicle.
     */
    public function gpsLocations(): HasMany
    {
        return $this->hasMany(GpsLocation::class);
    }

    /**
     * Get the route for this vehicle through the rider profile.
     */
    public function route(): HasOneThrough
    {
        return $this->hasOneThrough(
            Route::class,
            RiderProfile::class,
            'vehicle_id',        // Foreign key on rider_profiles table
            'id',                // Foreign key on routes table
            'id',                // Local key on vehicles table
            'assigned_route_id'  // Local key on rider_profiles table
        );
    }

    /**
     * Get all work logs for this vehicle.
     */
    public function workLogs(): HasMany
    {
        return $this->hasMany(WorkLog::class);
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include vehicles currently in maintenance.
     */
    public function scopeInMaintenance(Builder $query): Builder
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope a query to filter vehicles by type.
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}
