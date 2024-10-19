<?php

namespace Database\Factories\Booking;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking\Station;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Station::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->city, 
        ];
    }
}
