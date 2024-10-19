<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableSeatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'seat_id' => $this->id,
            'seat_number' => $this->seat_number,
            'bus_id' => $this->bus->id,
            'trip_id' => $this->bus->trip_id,
        ];
    }
}
