<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class AiLog extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'system_prompt',
        'user_prompt',
        'response',
        'status',
        'error_message',
        'duration_ms',
        'cost_estimate',
        'metadata',
    ];

    protected $casts = [
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens' => 'integer',
        'duration_ms' => 'integer',
        'cost_estimate' => 'decimal:6',
        'metadata' => 'array',
    ];

    /**
     * Relationship: belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: successful requests
     */
    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: failed requests
     */
    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', 'error');
    }

    /**
     * Scope: rate-limited requests
     */
    public function scopeRateLimited(Builder $query): Builder
    {
        return $query->where('status', 'rate_limited');
    }

    /**
     * Scope: filter by provider
     */
    public function scopeByProvider(Builder $query, string $provider): Builder
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope: filter by model
     */
    public function scopeByModel(Builder $query, string $model): Builder
    {
        return $query->where('model', $model);
    }

    /**
     * Accessor: duration in seconds formatted
     */
    public function getDurationFormattedAttribute(): string
    {
        if ($this->duration_ms < 1000) {
            return $this->duration_ms . 'ms';
        }

        return round($this->duration_ms / 1000, 2) . 's';
    }

    /**
     * Accessor: status color for Filament badge
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'success' => 'success',
            'error' => 'danger',
            'rate_limited' => 'warning',
            default => 'gray',
        };
    }
}
