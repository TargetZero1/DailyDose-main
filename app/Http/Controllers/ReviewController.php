<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            return back()->with('success', 'Review updated successfully!');
        }

        // Create new review
        Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review added successfully!');
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action');
        }

        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Mark review as helpful.
     */
    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');
        return back()->with('success', 'Thank you for your feedback!');
    }

    /**
     * Get reviews for a product (AJAX).
     */
    public function getProductReviews(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => $product->getAverageRating(),
            'total_reviews' => $product->getReviewCount(),
        ]);
    }
}
