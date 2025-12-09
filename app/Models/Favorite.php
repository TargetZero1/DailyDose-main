<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Get the user that owns this favorite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that is favorited.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if a product is favorited by a user.
     */
    public static function isFavorited($userId, $productId)
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
    }

    /**
     * Get all favorites for a user.
     */
    public static function getUserFavorites($userId)
    {
        return self::where('user_id', $userId)->with('product')->get();
    }
}
