<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class WorkLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rider_id',
        'vehicle_id',
        'clock_in_at',
        'clock_out_at',
        'total_hours',
        'clock_in_latitude',
        'clock_in_longitude',
        'clock_out_latitude',
        'clock_out_longitude',
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
            'clock_in_at' => 'datetime',
            'clock_out_at' => 'datetime',
            'total_hours' => 'decimal:2',
            'clock_in_latitude' => 'float',
            'clock_in_longitude' => 'float',
            'clock_out_latitude' => 'float',
            'clock_out_longitude' => 'float',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Get the rider (user) who created this work log.
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    /**
     * Get the vehicle associated with this work log.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // ──────────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────────

    /**
     * Determine if this work log is currently active (clocked in but not out).
     */
    public function isActive(): bool
    {
        return $this->clock_in_at !== null && $this->clock_out_at === null;
    }

    /**
     * Calculate the duration of this work session.
     *
     * Returns the difference in hours between clock-in and clock-out.
     * If the session is still active, calculates duration up to now.
     */
    public function duration(): float
    {
        if ($this->clock_in_at === null) {
            return 0.0;
        }

        $end = $this->clock_out_at ?? Carbon::now();

        return round($this->clock_in_at->diffInMinutes($end) / 60, 2);
    }
}
