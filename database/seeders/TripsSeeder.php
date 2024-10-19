<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking\Trip;
use App\Models\Booking\Station;

class TripsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trip = [
            'name' => 'Cairo to Aswan',
            'start_station_id' => Station::where('name', 'Cairo')->first()->id,
            'end_station_id' => Station::where('name', 'Aswan')->first()->id,
        ];

        Trip::updateOrCreate(
            ['name' => $trip['name']], 
            $trip 
        );
    }
}
