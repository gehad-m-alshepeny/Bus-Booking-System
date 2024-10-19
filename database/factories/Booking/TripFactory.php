<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Trip;
use App\Models\Booking\Station;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Trip::class;

    public function definition(): array
    {
         // Fetch random stations
         $startStation = Station::inRandomOrder()->first();
         $endStation = Station::inRandomOrder()->where('id', '!=', $startStation->id)->first();
 
         return [
             'name' => $startStation->name . ' to ' . $endStation->name, 
             'start_station_id' => $startStation->id,
             'end_station_id' => $endStation->id,
         ];
    }
}
