<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'is_active',
        'applicable_to',
        'valid_from',
        'valid_until',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Check if discount is valid
     */
    public function isValid($subtotal = 0, $userId = null, $applicableTo = 'products')
    {
        // Check if active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This discount code is not active'];
        }

        // Check applicable type
        if ($this->applicable_to !== 'both' && $this->applicable_to !== $applicableTo) {
            return ['valid' => false, 'message' => 'This discount is not applicable to ' . $applicableTo];
        }

        // Check date validity
        $now = Carbon::now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return ['valid' => false, 'message' => 'This discount is not yet valid'];
        }
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return ['valid' => false, 'message' => 'This discount has expired'];
        }

        // Check minimum purchase
        if ($subtotal < $this->min_purchase) {
            return ['valid' => false, 'message' => 'Minimum purchase of Rp ' . number_format($this->min_purchase, 0, ',', '.') . ' required'];
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This discount code has reached its usage limit'];
        }

        // Check per user limit
        if ($userId && $this->per_user_limit) {
            $userUsage = \DB::table('discount_usage')
                ->where('user_id', $userId)
                ->where('discount_id', $this->id)
                ->first();
            
            if ($userUsage && $userUsage->usage_count >= $this->per_user_limit) {
                return ['valid' => false, 'message' => 'You have reached the usage limit for this discount'];
            }
        }

        return ['valid' => true, 'message' => 'Discount code is valid'];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($subtotal)
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            
            // Apply max discount if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        } else {
            // Fixed amount
            return min($this->value, $subtotal); // Don't exceed subtotal
        }
    }

    /**
     * Record usage
     */
    public function recordUsage($userId = null)
    {
        // Increment global usage count
        $this->increment('usage_count');

        // Record per-user usage if user is provided
        if ($userId) {
            \DB::table('discount_usage')->insertOrIgnore([
                'user_id' => $userId,
                'discount_id' => $this->id,
                'usage_count' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \DB::table('discount_usage')
                ->where('user_id', $userId)
                ->where('discount_id', $this->id)
                ->increment('usage_count');
        }
    }
}
