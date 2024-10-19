<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking\Seat;
use App\Models\Booking\Bus;

class SeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = Bus::all();

        foreach ($buses as $bus) {
            // Create 12 seats for each bus
            for ($i = 1; $i <= 12; $i++) {
                Seat::updateOrCreate(
                    ['bus_id' => $bus->id, 'seat_number' => $i], 
                    ['bus_id' => $bus->id, 'seat_number' => $i]  
                );
            }
        }
    }
}
