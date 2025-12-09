<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\Middleware;

/**
 * BaseAdminController - Centralized admin functionality
 * Reduces code duplication and ensures consistent authorization/caching
 */
abstract class BaseAdminController extends Controller
{
    protected int $cacheTTL = 300;
    protected string $requiredRole = 'admin';

    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function __construct()
    {
        // Authorization check will be done via middleware
    }

    /**
     * Centralized authorization check
     */
    protected function authorize()
    {
        $user = Auth::user();
        if (!$user || ($user->role !== $this->requiredRole && $user->role !== 'pemilik')) {
            abort(403, 'Admin privileges required');
        }
        return true;
    }

    /**
     * Safely cache query results
     */
    protected function cacheQuery($key, $callback, $ttl = null)
    {
        return Cache::remember($key, $ttl ?? $this->cacheTTL, $callback);
    }

    /**
     * Clear specific cache keys
     */
    protected function clearCache(...$keys)
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Log admin action with context
     */
    protected function logAction($action, $data = [])
    {
        Log::info("Admin Action: {$action}", array_merge([
            'admin_id' => Auth::id(),
            'admin_email' => Auth::user()->email,
            'timestamp' => now(),
        ], $data));
    }

    /**
     * Validate and filter request inputs
     */
    protected function getValidatedFilters(Request $request, array $rules)
    {
        return $request->validate($rules);
    }

    /**
     * JSON response helper
     */
    protected function jsonSuccess($message, $data = [], $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * JSON error response helper
     */
    protected function jsonError($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    /**
     * Build paginated response
     */
    protected function paginated($query, $perPage = 15)
    {
        return $query->paginate($perPage)->withQueryString();
    }
}
