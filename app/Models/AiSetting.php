<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class AiSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the value attribute, handling type-specific conversions
     */
    public function getValueAttribute($value): mixed
    {
        if (empty($value)) {
            return $value;
        }

        $type = $this->attributes['type'] ?? 'text';

        if ($type === 'encrypted') {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception) {
                return $value;
            }
        }

        if ($type === 'number') {
            return is_numeric($value) ? (float) $value : $value;
        }

        return $value;
    }

    /**
     * Set the value attribute, handling encryption
     */
    public function setValueAttribute($value): void
    {
        $type = $this->attributes['type'] ?? 'text';

        if ($type === 'encrypted' && !empty($value)) {
            $this->attributes['value'] = Crypt::encryptString($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('ai_settings');
        });

        static::deleted(function () {
            Cache::forget('ai_settings');
        });
    }

    /**
     * Get a setting value by key (reads raw, non-decrypted value from cache)
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $instance = static::where('key', $key)->first();

        if (!$instance) {
            return $default;
        }

        // Use the model accessor which handles decryption
        return $instance->value ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value): void
    {
        $existing = static::where('key', $key)->first();

        if ($existing) {
            // Update using the mutator
            $existing->value = $value;
            $existing->save();
        } else {
            static::create(['key' => $key, 'value' => $value]);
        }

        Cache::forget('ai_settings');
    }

    /**
     * Get all settings as key-value array (raw values, not decrypted)
     */
    public static function getAllGrouped(): array
    {
        return static::orderBy('group')
            ->orderBy('order')
            ->get()
            ->groupBy('group')
            ->toArray();
    }

    /**
     * Check if a setting exists
     */
    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }
}
