<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservasi;
use Carbon\Carbon;

class ReservasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservations = [
            [
                'nama' => 'Vijaypal Singh Brara',
                'no_hp' => '08810975910',
                'tanggal' => Carbon::now()->addDays(1)->toDateString(),
                'waktu' => '09:00',
                'jumlah' => 5,
                'area' => 'Indoor',
                'status' => 'pending',
                'notes' => 'Birthday celebration, need quiet corner',
            ],
            [
                'nama' => 'Anjali Sharma',
                'no_hp' => '08823456789',
                'tanggal' => Carbon::now()->addDays(2)->toDateString(),
                'waktu' => '14:30',
                'jumlah' => 8,
                'area' => 'Outdoor',
                'status' => 'confirmed',
                'notes' => 'Corporate team lunch',
            ],
            [
                'nama' => 'Rahul Patel',
                'no_hp' => '08834567890',
                'tanggal' => Carbon::now()->addDays(3)->toDateString(),
                'waktu' => '18:00',
                'jumlah' => 4,
                'area' => 'VIP',
                'status' => 'pending',
                'notes' => 'Romantic dinner',
            ],
            [
                'nama' => 'Priya Kumar',
                'no_hp' => '08845678901',
                'tanggal' => Carbon::now()->addDays(4)->toDateString(),
                'waktu' => '11:00',
                'jumlah' => 12,
                'area' => 'Indoor',
                'status' => 'confirmed',
                'notes' => 'Family reunion',
            ],
            [
                'nama' => 'Arjun Desai',
                'no_hp' => '08856789012',
                'tanggal' => Carbon::now()->addDays(5)->toDateString(),
                'waktu' => '19:30',
                'jumlah' => 6,
                'area' => 'Outdoor',
                'status' => 'pending',
                'notes' => 'Friends gathering',
            ],
            [
                'nama' => 'Neha Singh',
                'no_hp' => '08867890123',
                'tanggal' => Carbon::now()->toDateString(),
                'waktu' => '12:00',
                'jumlah' => 3,
                'area' => 'Indoor',
                'status' => 'cancelled',
                'notes' => 'Changed plans',
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservasi::create($reservation);
        }
    }
}
