<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry',
        'license_type',
        'license_image',
        'assigned_vehicle_id',
        'assigned_route_id',
        'emergency_contact_name',
        'emergency_contact_phone',
        'guarantor_name',
        'guarantor_phone',
        'guarantor_address',
        'blood_group',
        'medical_conditions',
        'terms_accepted_at',
        'onboarding_completed_at',
        'onboarding_step',
        'id_document_path',
        'drivers_license_path',
        'passport_photo_path',
        'facial_verification_path',
        'assistant_name',
        'assistant_phone',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
            'terms_accepted_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Get the user that owns this rider profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle assigned to this rider.
     */
    public function assignedVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id');
    }

    /**
     * Get the route assigned to this rider.
     */
    public function assignedRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'assigned_route_id');
    }

    // ──────────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────────

    /**
     * Determine if the rider has completed onboarding.
     */
    public function isOnboardingComplete(): bool
    {
        return $this->onboarding_completed_at !== null;
    }
}
