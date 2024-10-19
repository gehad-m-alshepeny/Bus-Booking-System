<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Seat;
use App\Models\Booking\Bus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seat>
 */
class SeatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Seat::class;

    public function definition()
    {
        return [
            'bus_id' => Bus::factory(), // Create a bus that belongs to a trip
            'seat_number' => $this->faker->numberBetween(1, 12), // Assuming 12 seats per bus
        ];
    }
}
