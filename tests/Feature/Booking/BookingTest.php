<?php

use App\Models\User;
use App\Models\Booking\Trip;
use App\Models\Booking\TripStop;
use App\Models\Booking\Seat;
use App\Models\Booking\Bus;
use App\Models\Booking\Station;
use App\Models\Booking\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

// Set up before each test case
beforeEach(function () {

    // Create a user for authentication
    $this->user = User::factory()->create();
    
    // Log in the user
    $this->actingAs($this->user);

    // Create stations
    $stations = [
        'Cairo' => Station::factory()->create(['name' => 'Cairo']),
        'Giza' => Station::factory()->create(['name' => 'Giza']),
        'AlFayyum' => Station::factory()->create(['name' => 'AlFayyum']),
        'AlMinya' => Station::factory()->create(['name' => 'AlMinya']),
        'Asyut' => Station::factory()->create(['name' => 'Asyut']),
    ];

    // Create a trip
    $this->trip = Trip::factory()->create([
        'name' => 'Cairo to Asyut',
        'start_station_id' => $stations['Cairo']->id,
        'end_station_id' => $stations['Asyut']->id,
    ]);

    // Create trip stops
    $tripStops = [
        ['station' => $stations['Cairo'], 'stop_order' => 1],
        ['station' => $stations['Giza'], 'stop_order' => 2],
        ['station' => $stations['AlFayyum'], 'stop_order' => 3],
        ['station' => $stations['AlMinya'], 'stop_order' => 4],
        ['station' => $stations['Asyut'], 'stop_order' => 5],
    ];

    foreach ($tripStops as $stop) {
        TripStop::factory()->create([
            'trip_id' => $this->trip->id,
            'station_id' => $stop['station']->id,
            'stop_order' => $stop['stop_order'],
        ]);
    }

    // Create a bus for the trip
    $this->bus = Bus::factory()->create(['trip_id' => $this->trip->id]);

    // Create seats for the bus
    for ($i = 1; $i <= 12; $i++) {
    Seat::factory()->create([
        'bus_id' => $this->bus->id,
        'seat_number' => $i, // Manually assign seat number
    ]);
    }
});

it('can get available seats', function () {

    $data = [
        'trip_id' => $this->trip->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    $queryString = http_build_query($data);

    $response = $this->getJson('/api/booking/available-seats?' . $queryString);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['seat_id', 'trip_id', 'bus_id'],
                 ]
             ]);
});

it('can book a seat', function () {

    $data = [
        'trip_id' => $this->trip->id,
        'seat_id' => Seat::where('seat_number', 2)->first()->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    $response = $this->postJson('/api/booking/book-seat', $data);

    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Seat booked successfully',
             ]);

});

it('prevents double booking of the same seat', function () {

    $data = [
        'trip_id' => $this->trip->id,
        'seat_id' => Seat::where('seat_number', 5)->first()->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    // First booking attempt
    $this->postJson('/api/booking/book-seat', $data)
         ->assertStatus(201);

    // Second booking should fail
    $response = $this->postJson('/api/booking/book-seat', $data);

    $response->assertStatus(400)
             ->assertJson([
                 'error' => 'Failed to book seat',
                 'message' => 'Seat is already booked.',
             ]);

});

it('validates booking request input', function () {
    $data = [
        'seat_id' => Seat::where('seat_number', 12)->first()->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    $response = $this->postJson('/api/booking/book-seat', $data);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['trip_id']);
});

it('removes booked seat from available seats after booking', function () {
    $bookingData = [
        'trip_id' => $this->trip->id,
        'seat_id' => Seat::where('seat_number', 3)->first()->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    // Book the seat
    $this->postJson('/api/booking/book-seat', $bookingData)
         ->assertStatus(201);

    // Now check available seats
    $availableSeatsData = [
        'trip_id' => $this->trip->id,
        'start_station_id' => $this->trip->start_station_id,
        'end_station_id' => $this->trip->end_station_id,
        'date' => now()->toDateString(),
    ];

    $queryString = http_build_query($availableSeatsData);

    // get the available seats after the booking
    $response = $this->getJson('/api/booking/available-seats?' . $queryString);

    // seat number 3 is no longer available
    $response->assertStatus(200)
             ->assertJsonMissing(['seat_id' => Seat::where('seat_number', 3)->first()->id])
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['seat_id', 'trip_id', 'bus_id'],
                 ]
             ]);
});

