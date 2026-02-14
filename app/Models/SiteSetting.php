<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    // ──────────────────────────────────────────────
    // Static Helpers
    // ──────────────────────────────────────────────

    /**
     * Get a site setting value by key.
     *
     * @param  string  $key      The setting key to look up.
     * @param  mixed   $default  Default value if the setting does not exist.
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a site setting value by key.
     *
     * Creates the setting if it does not exist, updates it otherwise.
     *
     * @param  string  $key    The setting key.
     * @param  mixed   $value  The value to store.
     * @return static
     */
    public static function set(string $key, mixed $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
