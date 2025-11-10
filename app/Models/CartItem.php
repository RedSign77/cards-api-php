<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'physical_card_id',
        'quantity',
        'reserved_until',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reserved_until' => 'datetime',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function physicalCard(): BelongsTo
    {
        return $this->belongsTo(PhysicalCard::class);
    }

    /**
     * Get the subtotal for this cart item
     */
    public function getSubtotal(): float
    {
        return $this->quantity * $this->physicalCard->price_per_unit;
    }

    /**
     * Extend the reservation for 1 hour from now
     */
    public function extendReservation(): void
    {
        $this->reserved_until = Carbon::now()->addHour();
        $this->save();
    }

    /**
     * Check if the reservation is still active
     */
    public function isReservationActive(): bool
    {
        return $this->reserved_until && $this->reserved_until->isFuture();
    }

    /**
     * Scope to get only active reservations
     */
    public function scopeActiveReservations(Builder $query): Builder
    {
        return $query->where('reserved_until', '>', Carbon::now());
    }

    /**
     * Scope to get expired reservations
     */
    public function scopeExpiredReservations(Builder $query): Builder
    {
        return $query->where('reserved_until', '<=', Carbon::now());
    }

    /**
     * Get the total reserved quantity for a physical card (excluding a specific cart)
     */
    public static function getReservedQuantity(int $physicalCardId, ?int $excludeCartId = null): int
    {
        return self::where('physical_card_id', $physicalCardId)
            ->activeReservations()
            ->when($excludeCartId, fn (Builder $query) => $query->where('cart_id', '!=', $excludeCartId))
            ->sum('quantity');
    }

    /**
     * Get the available quantity for a physical card (stock - reserved)
     */
    public static function getAvailableQuantity(int $physicalCardId, ?int $excludeCartId = null): int
    {
        $physicalCard = PhysicalCard::findOrFail($physicalCardId);
        $reservedQuantity = self::getReservedQuantity($physicalCardId, $excludeCartId);

        return max(0, $physicalCard->quantity - $reservedQuantity);
    }

    /**
     * Boot method to set reservation on creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cartItem) {
            if (!$cartItem->reserved_until) {
                $cartItem->reserved_until = Carbon::now()->addHour();
            }
        });
    }
}
