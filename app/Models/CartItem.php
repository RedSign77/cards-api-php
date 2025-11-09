<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'physical_card_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
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
}
