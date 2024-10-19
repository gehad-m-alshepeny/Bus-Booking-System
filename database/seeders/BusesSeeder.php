<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking\Trip;
use App\Models\Booking\Bus;
use App\Models\Booking\Station;

class BusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trip = Trip::where('start_station_id', Station::where('name', 'Cairo')->first()->id)
                     ->where('end_station_id', Station::where('name', 'Aswan')->first()->id)->first();

        $bus = [
            ['trip_id' => $trip->id, 'seat_count' => 12]
        ];

        Bus::updateOrCreate(
                ['trip_id' =>  $trip->id], 
                ['trip_id' =>  $trip->id, 'seat_count' => 12] );
        
    }
}
