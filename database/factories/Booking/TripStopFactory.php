<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\TripStop;
use App\Models\Booking\Trip;
use App\Models\Booking\Station;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TripStop>
 */
class TripStopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TripStop::class;

    public function definition(): array
    {
        return [
            'trip_id' => Trip::factory(), 
            'station_id' => Station::factory(),
            'stop_order' => $this->faker->numberBetween(1, 10), 
        ];
    }
}
