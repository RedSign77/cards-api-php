<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of all items in the cart
     */
    public function getTotalPrice(): float
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->physicalCard->price_per_unit;
        });
    }

    /**
     * Get the total number of items in the cart
     */
    public function getTotalItems(): int
    {
        return $this->items->sum('quantity');
    }
}
