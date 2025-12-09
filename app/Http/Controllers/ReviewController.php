<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Simpan review baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Cek apakah user sudah review produk ini
        $existingReview = Review::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            // Update review yang sudah ada
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            return back()->with('success', 'Review berhasil diperbarui!');
        }

        // Buat review baru
        Review::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review berhasil ditambahkan!');
    }

    /**
     * Hapus review
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'Aksi tidak diizinkan');
        }

        $review->delete();
        return back()->with('success', 'Review berhasil dihapus!');
    }

    /**
     * Tandai review sebagai membantu
     */
    public function markHelpful(Review $review)
    {
        $review->increment('helpful_count');
        return back()->with('success', 'Terima kasih atas masukan Anda!');
    }

    /**
     * Ambil review untuk produk (AJAX)
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
