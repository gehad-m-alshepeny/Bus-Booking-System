<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking\Trip;
use App\Models\Booking\Station;
use App\Models\Booking\TripStop;

class TripStopsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $trip = Trip::where('name', 'Cairo to Aswan')->first();

         $tripStops = [
             ['station_id' => Station::where('name', 'Cairo')->first()->id, 'stop_order' => 1],
             ['station_id' => Station::where('name', 'Giza')->first()->id, 'stop_order' => 2],
             ['station_id' => Station::where('name', 'AlFayyum')->first()->id, 'stop_order' => 3],
             ['station_id' => Station::where('name', 'Beni-Suef')->first()->id, 'stop_order' => 4],
             ['station_id' => Station::where('name', 'AlMinya')->first()->id, 'stop_order' => 5],
             ['station_id' => Station::where('name', 'Asyut')->first()->id, 'stop_order' => 6],
             ['station_id' => Station::where('name', 'Sohag')->first()->id, 'stop_order' => 7],
             ['station_id' => Station::where('name', 'Qena')->first()->id, 'stop_order' => 8],
             ['station_id' => Station::where('name', 'Luxor')->first()->id, 'stop_order' => 9],
             ['station_id' => Station::where('name', 'Aswan')->first()->id, 'stop_order' => 10],
         ];
 
         foreach ($tripStops as $stop) {
             TripStop::updateOrCreate(
                 ['trip_id' => $trip->id, 'station_id' => $stop['station_id']],
                 ['trip_id' => $trip->id, 'station_id' => $stop['station_id'], 'stop_order' => $stop['stop_order']] 
             );
         }
    }
}
