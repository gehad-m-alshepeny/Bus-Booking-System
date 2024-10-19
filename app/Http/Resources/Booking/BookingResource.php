<?php

namespace App\Http\Resources\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'seat_id' => $this->seat_id,
            'user_id' => $this->user_id,
            'trip_id' => $this->trip_id,
            'start_station' => $this->startStation->name, 
            'end_station' => $this->endStation->name,  
            'date' => $this->date->toDateString(),
        ];
    }
}
