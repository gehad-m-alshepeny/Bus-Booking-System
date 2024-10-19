<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Trip;
use App\Models\Booking\Bus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Bus::class;

    public function definition(): array
    {
        return [
            'trip_id' => Trip::factory(),  // Associate a bus with a trip using the TripFactory
            'seat_count' => 12,            // Assuming each bus has 12 seats
        ];
    }
}
