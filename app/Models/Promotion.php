<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'value',
        'product_id',
        'is_active',
        'start_date',
        'end_date',
        'min_quantity',
        'banner_image',
    ];

    protected $casts = [
        'value' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_quantity' => 'integer',
    ];

    /**
     * Get the product for this promotion (if product-specific).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if promotion is currently active.
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->is_active 
            && $now->greaterThanOrEqualTo($this->start_date)
            && $now->lessThanOrEqualTo($this->end_date);
    }

    /**
     * Get active promotions.
     */
    public static function getActive()
    {
        $now = Carbon::now();
        return self::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();
    }

    /**
     * Get active promotions for a specific product.
     */
    public static function getActiveForProduct($productId)
    {
        $now = Carbon::now();
        return self::where('product_id', $productId)
            ->where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
    }

    /**
     * Calculate discounted price.
     */
    public function getDiscountedPrice($originalPrice): int
    {
        if ($this->type === 'percentage') {
            return (int)($originalPrice * (100 - $this->value) / 100);
        } elseif ($this->type === 'fixed') {
            return max(0, $originalPrice - $this->value);
        }
        return $originalPrice;
    }

    /**
     * Get discount amount.
     */
    public function getDiscountAmount($originalPrice): int
    {
        if ($this->type === 'percentage') {
            return (int)($originalPrice * $this->value / 100);
        } elseif ($this->type === 'fixed') {
            return $this->value;
        }
        return 0;
    }
}
