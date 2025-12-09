<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\Middleware;

#[Middleware('auth')]
class AdminOrderController extends Controller
{

    /**
     * Verifikasi akses admin
     */
    private function authorize()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan. Diperlukan hak admin.');
        }
    }

    /**
     * Dapatkan statistik pesanan dari cache teroptimasi
     */
    private function getOrderStats()
    {
        return Cache::remember('order_stats_v2', 300, function () {
            $stats = Order::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "processing" THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as payment_pending,
                SUM(CASE WHEN payment_status = "confirmed" THEN 1 ELSE 0 END) as payment_confirmed,
                SUM(CASE WHEN status = "completed" THEN total ELSE 0 END) as revenue_total
            ')->first();

            $revenueToday = Order::whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('total');

            return [
                'total' => $stats->total ?? 0,
                'pending' => $stats->pending ?? 0,
                'processing' => $stats->processing ?? 0,
                'completed' => $stats->completed ?? 0,
                'cancelled' => $stats->cancelled ?? 0,
                'payment_pending' => $stats->payment_pending ?? 0,
                'payment_confirmed' => $stats->payment_confirmed ?? 0,
                'revenue_total' => $stats->revenue_total ?? 0,
                'revenue_today' => $revenueToday ?? 0,
            ];
        });
    }

    /**
     * Terapkan filter ke query pesanan dengan sanitasi yang tepat
     */
    private function applyFilters($query, Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled,refunded',
            'payment_status' => 'nullable|in:pending,confirmed,failed,refunded',
            'search' => 'nullable|string|max:100',
            'sort' => 'nullable|in:oldest,newest',
        ]);

        return $query
            ->when($validated['status'] ?? null, fn($q, $status) => $q->where('orders.status', $status))
            ->when($validated['payment_status'] ?? null, fn($q, $payment) => $q->where('orders.payment_status', $payment))
            ->when($validated['search'] ?? null, fn($q, $search) => $q->where(function ($query) use ($search) {
                $query->where('orders.id', 'like', "%" . $search . "%")
                    ->orWhere('orders.customer_name', 'like', "%" . $search . "%")
                    ->orWhere('orders.customer_phone', 'like', "%" . $search . "%");
            }))
            ->when(($validated['sort'] ?? 'newest') === 'oldest', fn($q) => $q->oldest('orders.created_at'), fn($q) => $q->latest('orders.created_at'));
    }

    /**
     * List all orders with filters - Optimized
     */
    public function index(Request $request)
    {
        $this->authorize();

        $orders = $this->applyFilters(
            Order::with(['user:id,username,email', 'items.product:id,name,image'])
                ->select('orders.*'),
            $request
        )->paginate(15)->withQueryString();

        $stats = $this->getOrderStats();

        return view('admin.orders.index', compact('orders', 'stats'));
    }


    /**
     * Show order details - Optimized
     */
    public function show(Order $order)
    {
        $this->authorize();
        
        $order->load([
            'user:id,username,email,phone',
            'items.product:id,name,image,price'
        ]);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status with validation and logging
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize();

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
        ]);

        $oldStatus = $order->status;
        $order->update($validated);
        
        Cache::forget('order_stats_v2');
        Cache::flush(); // Clear analytics cache when order changes

        Log::info('Order status updated', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->username,
            'timestamp' => now(),
        ]);

        return back()->with('success', "Order #{$order->id} status updated to {$validated['status']}");
    }

    /**
     * Update payment status with validation and logging
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $this->authorize();

        $validated = $request->validate([
            'payment_status' => 'required|in:pending,confirmed,failed,refunded',
        ]);

        $oldPaymentStatus = $order->payment_status;
        $order->update($validated);
        
        Cache::forget('order_stats_v2');

        Log::info('Payment status updated', [
            'order_id' => $order->id,
            'old_payment_status' => $oldPaymentStatus,
            'new_payment_status' => $validated['payment_status'],
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->username,
            'timestamp' => now(),
        ]);

        return back()->with('success', "Order #{$order->id} payment status updated to {$validated['payment_status']}");
    }

    /**
     * Send WhatsApp notification - Improved with validation
     */
    public function sendWhatsappNotification(Request $request, Order $order)
    {
        $this->authorize();

        $validated = $request->validate([
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Sanitize and format phone number
        $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
        
        if (empty($phone)) {
            return back()->with('error', 'Invalid phone number for this order.');
        }
        
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . ltrim($phone, '0');
        }

        $message = strip_tags($validated['message']);
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        Log::info('WhatsApp notification sent', [
            'order_id' => $order->id,
            'phone' => $phone,
            'admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'WhatsApp link generated.')
            ->with('whatsapp_url', $whatsappUrl);
    }

    /**
     * Export orders to CSV - Optimized with streaming
     */
    public function export(Request $request)
    {
        $this->authorize();

        $validated = $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled,refunded',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Order::with(['items:id,order_id', 'user:id,username'])
            ->select('id', 'user_id', 'customer_name', 'customer_phone', 'total', 'status', 'payment_status', 'created_at')
            ->latest();

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['start_date']) && !empty($validated['end_date'])) {
            $query->whereBetween('created_at', [$validated['start_date'], $validated['end_date']]);
        }

        $orders = $query->get();
        
        $headers = [
            "Order ID", "Customer", "Phone", "Total (Rp)", 
            "Status", "Payment Status", "Items Count", "Date"
        ];
        $filename = "orders_export_" . date('Y-m-d_His') . ".csv";

        $callback = function() use ($orders, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer_name,
                    $order->customer_phone,
                    number_format($order->total, 0, ',', '.'),
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    $order->items->count(),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        Log::info('Orders exported', [
            'count' => $orders->count(),
            'admin_id' => Auth::id(),
        ]);

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Dashboard statistics - Deprecated, use index instead
     */
    public function dashboard()
    {
        return redirect()->route('admin.orders.index')
            ->with('info', 'Order dashboard has been merged into the main orders page.');
    }

    /**
     * Display analytics page
     */
    public function analytics(Request $request)
    {
        $this->authorize();

        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $validated['start_date'] ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $validated['end_date'] ?? now()->format('Y-m-d');

        // Ambil statistik pesanan
        $stats = $this->getOrderStats();

        // Ambil data tren pendapatan
        $revenueTrend = DB::table('orders')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count, SUM(total) as revenue')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ambil breakdown status pesanan
        $statusBreakdown = DB::table('orders')
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('status')
            ->get();

        // Ambil produk terbaik
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, COUNT(*) as sold_count, SUM(order_items.quantity) as total_quantity')
            ->whereBetween('order_items.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('sold_count')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'revenueTrend',
            'statusBreakdown',
            'topProducts',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export analytics data - Optimized with proper headers
     */
    public function exportAnalytics(Request $request)
    {
        $this->authorize();

        $validated = $request->validate([
            'format' => 'required|in:csv,pdf',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $format = $validated['format'];
        $startDate = $validated['start_date'] ?? now()->subDays(30)->format('Y-m-d');
        $endDate = $validated['end_date'] ?? now()->format('Y-m-d');

        if ($format === 'csv') {
            $orders = Order::with(['items.product:id,name', 'user:id,username'])
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->select('id', 'user_id', 'customer_name', 'customer_phone', 'total', 'status', 'payment_status', 'created_at')
                ->get();

            $headers = [
                "Order ID", "Customer", "Phone", "Total (Rp)", 
                "Status", "Payment", "Products", "Date"
            ];
            $filename = "analytics_" . date('Y-m-d_His') . ".csv";

            $callback = function() use ($orders, $headers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);

                foreach ($orders as $order) {
                    $products = $order->items->pluck('product.name')->implode(', ');
                    
                    fputcsv($file, [
                        $order->id,
                        $order->customer_name,
                        $order->customer_phone,
                        number_format($order->total, 0, ',', '.'),
                        ucfirst($order->status),
                        ucfirst($order->payment_status),
                        $products,
                        $order->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($file);
            };

            Log::info('Analytics exported', [
                'format' => 'CSV',
                'date_range' => "{$startDate} to {$endDate}",
                'admin_id' => Auth::id(),
            ]);

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        }

        // For PDF, redirect to CSV for now (can implement PDF library later)
        return $this->exportAnalytics($request->merge(['format' => 'csv']));
    }
}