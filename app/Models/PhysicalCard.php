<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhysicalCard extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_unit' => 'decimal:2',
        'tradeable' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
