<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all public routes are accessible.
     */
    public function test_public_routes_are_accessible(): void
    {
        $publicRoutes = [
            'home' => '/',
            'menu' => '/menu',
            'about' => '/about',
            'contact' => '/contact',
            'login' => '/login',
            'register' => '/register',
        ];

        foreach ($publicRoutes as $name => $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }
    }

    /**
     * Test that protected routes require authentication.
     */
    public function test_protected_routes_require_authentication(): void
    {
        $protectedRoutes = [
            '/profile',
            '/orders',
            '/cart',
            '/favorites',
        ];

        foreach ($protectedRoutes as $url) {
            $response = $this->get($url);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Test that authenticated users can access protected routes.
     */
    public function test_authenticated_users_can_access_protected_routes(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
    }

    /**
     * Test dashboard route works.
     */
    public function test_dashboard_route_works(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /**
     * Test reservation routes work.
     */
    public function test_reservation_routes_work(): void
    {
        $response = $this->get(route('reservasi.create'));
        $response->assertStatus(200);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('reservasi.list'));
        $response->assertStatus(200);
    }
}
