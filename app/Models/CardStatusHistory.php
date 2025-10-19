<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardStatusHistory extends Model
{
    protected $fillable = [
        'physical_card_id',
        'old_status',
        'new_status',
        'action_type',
        'user_id',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function physicalCard(): BelongsTo
    {
        return $this->belongsTo(PhysicalCard::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new status history entry
     */
    public static function logStatusChange(
        PhysicalCard $card,
        string $newStatus,
        string $actionType,
        ?int $userId = null,
        ?string $notes = null,
        ?array $metadata = null
    ): self {
        return static::create([
            'physical_card_id' => $card->id,
            'old_status' => $card->getOriginal('status'),
            'new_status' => $newStatus,
            'action_type' => $actionType,
            'user_id' => $userId,
            'notes' => $notes,
            'metadata' => $metadata,
        ]);
    }
}
