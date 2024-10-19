<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Models\User;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 
        'seat_id', 
        'trip_id', 
        'start_station_id', 
        'end_station_id', 
        'date'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function startStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'start_station_id');
    }

    public function endStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'end_station_id');
    }

    // Accessors
    public function getDateAttribute($value): Carbon
    {
        return Carbon::parse($value);
    }

    // Scopes
    // In the Booking model
public function scopeForTripOnDate($query, $tripId, $date)
{
    return $query->where('trip_id', $tripId)->where('date', $date);
}

public function scopeConflictingWithBusStops($query, $startOrder, $endOrder)
{
    return $query->where(function ($q) use ($startOrder, $endOrder) {
        // Condition 1: input start or end station equals booking's start or end station 
        $q->whereHas('startStation.tripStops', function ($q) use ($startOrder) {
            $q->where('stop_order', '=', $startOrder);
        })
        ->orWhereHas('endStation.tripStops', function ($q) use ($endOrder) {
            $q->where('stop_order', '=', $endOrder);
        })

        // Condition 2: booking's start station comes after input start station and ends before input end station
        ->orWhereHas('startStation.tripStops', function ($q) use ($startOrder) {
            $q->where('stop_order', '<', $startOrder);
        })
        ->whereHas('endStation.tripStops', function ($q) use ($endOrder) {
            $q->where('stop_order', '>', $endOrder);
        })

        // Condition 3: input start station is between the booking's start and end stations
        ->orWhereHas('startStation.tripStops', function ($q) use ($startOrder, $endOrder) {
            $q->where('stop_order', '>', $startOrder)
               ->where('stop_order', '<', $endOrder);
        })

        // Condition 4: input end station is between the booking's start and end stations
        ->orWhereHas('endStation.tripStops', function ($q) use ($startOrder, $endOrder) {
            $q->where('stop_order', '>', $startOrder)
               ->where('stop_order', '<', $endOrder);
        });
    });
}


}
