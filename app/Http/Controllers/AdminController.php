<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        try {
            // Only allow admin and pemilik roles
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                abort(403, 'Unauthorized');
            }

            $stats = [
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'total_users' => User::count(),
                'total_reservations' => Reservasi::count(),
                'revenue' => Order::sum('total'),
                'recent_orders' => Order::with('user')->latest()->limit(5)->get(),
                'popular_products' => Product::orderBy('sale_count', 'desc')->limit(5)->get(),
            ];

            return view('admin.dashboard', $stats);
        } catch (\Exception $e) {
            \Log::error('Error loading admin dashboard: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Failed to load admin dashboard');
        }
    }

    /**
     * Show products management page.
     */
    public function products()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        $query = Product::query();

        // Search by name
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        // Filter by category
        if (request('category')) {
            $query->where('category', request('category'));
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Sort
        $sort = request('sort', 'default');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form.
     */
    public function createProduct()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        return view('admin.products.create');
    }

    /**
     * Store a new product.
     */
    public function storeProduct(Request $request)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                abort(403, 'Unauthorized');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|integer|min:0',
                'category' => 'required|string|max:50',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_new'] = $request->has('is_new');

            Product::create($validated);

            return redirect()->route('admin.products')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Show edit product form.
     */
    public function editProduct(Product $product)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                abort(403, 'Unauthorized');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|integer|min:0',
                'category' => 'required|string|max:50',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path;
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_new'] = $request->has('is_new');

            $product->update($validated);

            return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Toggle product visibility (hide/show).
     */
    public function toggleProductStatus(Product $product)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        $product->status = $product->status === 'active' ? 'hidden' : 'active';
        $product->save();

        return back()->with('success', 'Product status updated!');
    }

    /**
     * Delete product.
     */
    public function deleteProduct(Product $product)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                abort(403, 'Unauthorized');
            }

            $product->delete();

            return back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete product. It may be referenced in orders.');
        }
    }

    /**
     * Show orders management page.
     */
    public function orders()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        $query = Order::with('user', 'items.product');

        // Search by order ID or customer name
        if (request('search')) {
            $search = request('search');
            $query->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('username', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by payment status
        if (request('payment_status')) {
            $query->where('payment_status', request('payment_status'));
        }

        // Sorting
        $sort = request('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'highest') {
            $query->orderBy('total', 'desc');
        } elseif ($sort === 'lowest') {
            $query->orderBy('total', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate(12);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show reservations management page.
     */
    public function reservations()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        $query = Reservasi::query();

        // Search by name or phone
        if (request('search')) {
            $search = request('search');
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
        }

        // Filter by area
        if (request('area')) {
            $query->where('area', request('area'));
        }

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Sorting
        $sort = request('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'earliest_date') {
            $query->orderBy('tanggal', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reservations = $query->paginate(12);
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Show users management page.
     */
    public function users()
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
            abort(403, 'Unauthorized');
        }

        $query = User::query();

        // Search by name or email
        if (request('search')) {
            $search = request('search');
            $query->where('username', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        // Filter by role
        if (request('role')) {
            $query->where('role', request('role'));
        }

        // Sorting
        $sort = request('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'name') {
            $query->orderBy('username', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->paginate(12);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan(User $user)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $shouldBan = request()->input('ban', true);
            $user->is_banned = $shouldBan;
            $user->save();

            $message = $shouldBan ? 'User banned successfully' : 'User unbanned successfully';
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            \Log::error('Error toggling user ban: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm a reservation.
     */
    public function confirmReservation($id)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $reservasi = Reservasi::find($id);
            if (!$reservasi) {
                return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
            }

            $reservasi->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'message' => 'Reservation confirmed successfully!',
                'reservation' => $reservasi
            ]);
        } catch (\Exception $e) {
            \Log::error('Error confirming reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation($id)
    {
        try {
            if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik') {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $reservasi = Reservasi::find($id);
            if (!$reservasi) {
                return response()->json(['success' => false, 'message' => 'Reservation not found'], 404);
            }

            $reservasi->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully!',
                'reservation' => $reservasi
            ]);
        } catch (\Exception $e) {
            \Log::error('Error cancelling reservation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
