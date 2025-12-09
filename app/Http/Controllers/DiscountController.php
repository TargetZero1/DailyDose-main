<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Validate and apply discount code
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'type' => 'required|in:products,reservations',
        ]);

        $code = strtoupper(trim($request->code));
        $subtotal = $request->subtotal;
        $type = $request->type;
        $userId = Auth::id();

        // Find discount by code
        $discount = Discount::where('code', $code)->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid discount code'
            ], 404);
        }

        // Validate discount
        $validation = $discount->isValid($subtotal, $userId, $type);

        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        // Calculate discount amount
        $discountAmount = $discount->calculateDiscount($subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Discount applied successfully',
            'discount' => [
                'id' => $discount->id,
                'code' => $discount->code,
                'name' => $discount->name,
                'type' => $discount->type,
                'value' => $discount->value,
                'amount' => $discountAmount,
                'formatted_amount' => 'Rp ' . number_format($discountAmount, 0, ',', '.'),
            ]
        ]);
    }

    /**
     * Record discount usage (called after order/reservation is completed)
     */
    public function recordUsage(Request $request)
    {
        $request->validate([
            'discount_id' => 'required|exists:discounts,id',
        ]);

        $discount = Discount::findOrFail($request->discount_id);
        $userId = Auth::id();

        $discount->recordUsage($userId);

        return response()->json([
            'success' => true,
            'message' => 'Discount usage recorded'
        ]);
    }

    /**
     * Get active discounts (for display purposes)
     */
    public function getActive(Request $request)
    {
        $type = $request->get('type', 'both');
        
        $discounts = Discount::where('is_active', true)
            ->where(function($query) use ($type) {
                $query->where('applicable_to', $type)
                      ->orWhere('applicable_to', 'both');
            })
            ->where(function($query) {
                $query->whereNull('valid_from')
                      ->orWhere('valid_from', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('valid_until')
                      ->orWhere('valid_until', '>=', now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('usage_count < usage_limit');
            })
            ->get();

        return response()->json([
            'success' => true,
            'discounts' => $discounts
        ]);
    }
}
