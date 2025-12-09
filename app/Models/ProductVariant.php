<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'value',
        'price_modifier',
        'stock',
    ];

    protected $casts = [
        'price_modifier' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Get the product that owns this variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if variant is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get total price (product price + modifier).
     */
    public function getTotalPrice(): int
    {
        return $this->product->price + $this->price_modifier;
    }

    /**
     * Get variants by type for a product.
     */
    public static function getByProductAndType($productId, $type)
    {
        return self::where('product_id', $productId)
            ->where('type', $type)
            ->get();
    }
}
