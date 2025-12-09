<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Reservasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservasiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a reservation with valid data.
     */
    public function test_creates_reservation_with_valid_data(): void
    {
        $user = User::factory()->create();

        $data = [
            'nama' => 'John Doe',
            'no_hp' => '08123456789',
            'tanggal' => '2025-12-15',
            'waktu' => '18:00',
            'jumlah' => 4,
            'area' => 'VIP',
        ];

        $response = $this->actingAs($user)->post(route('reservasi.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservasi', [
            'nama' => 'John Doe',
            'no_hp' => '08123456789',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test reservation validation.
     */
    public function test_reservation_validation_fails_with_missing_data(): void
    {
        $user = User::factory()->create();

        $data = [
            'nama' => '', // Missing name
            'no_hp' => '08123456789',
        ];

        $response = $this->actingAs($user)->post(route('reservasi.store'), $data);

        $response->assertSessionHasErrors(['nama', 'tanggal', 'waktu', 'jumlah', 'area']);
    }

    /**
     * Test viewing reservation confirmation page.
     */
    public function test_can_view_reservation_confirmation(): void
    {
        $user = User::factory()->create();
        
        $reservasi = Reservasi::create([
            'nama' => 'Jane Doe',
            'no_hp' => '08987654321',
            'tanggal' => '2025-12-20',
            'waktu' => '19:00',
            'jumlah' => 2,
            'area' => 'Indoor',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('reservasi.confirmation', $reservasi->id));

        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
        $response->assertSee('08987654321');
    }
}
