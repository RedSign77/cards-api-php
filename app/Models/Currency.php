<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_active',
        'is_base',
        'sort_order',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_active' => 'boolean',
        'is_base' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active currencies
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the base currency
     */
    public static function getBaseCurrency(): ?self
    {
        return self::where('is_base', true)->first();
    }

    /**
     * Convert amount from this currency to another currency
     */
    public function convertTo(float $amount, Currency $toCurrency): float
    {
        $baseCurrency = self::getBaseCurrency();

        if (!$baseCurrency) {
            return $amount; // No conversion if no base currency is set
        }

        // Convert to base currency first
        $amountInBase = $amount / $this->exchange_rate;

        // Convert from base to target currency
        return $amountInBase * $toCurrency->exchange_rate;
    }

    /**
     * Convert amount from base currency to this currency
     */
    public function convertFromBase(float $amount): float
    {
        return $amount * $this->exchange_rate;
    }

    /**
     * Convert amount to base currency
     */
    public function convertToBase(float $amount): float
    {
        return $amount / $this->exchange_rate;
    }

    /**
     * Get formatted amount with currency symbol
     */
    public function formatAmount(float $amount): string
    {
        return $this->symbol . ' ' . number_format($amount, 2);
    }

    /**
     * Boot method to ensure only one base currency
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($currency) {
            if ($currency->is_base) {
                // Set all other currencies to not base
                self::where('id', '!=', $currency->id)
                    ->where('is_base', true)
                    ->update(['is_base' => false]);
            }
        });
    }
}
