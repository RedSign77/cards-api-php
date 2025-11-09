<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhysicalCard extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_PENDING_AUTO = 'pending_auto';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'user_id',
        'title',
        'set',
        'language',
        'edition',
        'description',
        'image',
        'quantity',
        'condition',
        'price_per_unit',
        'currency',
        'tradeable',
        'status',
        'is_critical',
        'rejection_reason',
        'review_notes',
        'reviewed_at',
        'reviewed_by',
        'approved_by',
        'approved_at',
        'evaluation_flags',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_unit' => 'decimal:2',
        'tradeable' => 'boolean',
        'is_critical' => 'boolean',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'evaluation_flags' => 'array',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING_AUTO,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(CardStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function isPendingAuto(): bool
    {
        return $this->status === self::STATUS_PENDING_AUTO;
    }

    public function isUnderReview(): bool
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING_AUTO => 'Pending Auto Evaluation',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }
}
