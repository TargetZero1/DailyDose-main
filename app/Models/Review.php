<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'helpful_count',
    ];

    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
    ];

    /**
     * Get the product that owns this review.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that wrote this review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get average rating for a product.
     */
    public static function getAverageRating($productId)
    {
        return self::where('product_id', $productId)->avg('rating') ?? 0;
    }

    /**
     * Get review count for a product.
     */
    public static function getReviewCount($productId)
    {
        return self::where('product_id', $productId)->count();
    }
}
