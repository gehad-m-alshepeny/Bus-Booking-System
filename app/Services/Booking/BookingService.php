<?php

namespace App\Services\Booking;

use App\Models\Booking\Seat;
use App\Models\Booking\Booking;
use App\Models\Booking\TripStop;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class BookingService
{
    public function getAvailableSeats(array $data): Collection
    {
        try {
            $busStopOrders = $this->getStartEndBusStopOrders($data['trip_id'], $data['start_station_id'], $data['end_station_id']);
        
            $startBusStopOrder = $busStopOrders['startOrder'];
            $endBusStopOrder = $busStopOrders['endOrder'];
            
            $this->validateTripOrder($startBusStopOrder, $endBusStopOrder);
    
            return Seat::whereHas('bus', function ($query) use ($data) {
                        $query->where('trip_id', $data['trip_id']);
                    })
                    ->whereDoesntHave('bookings', function ($query) use ($data, $startBusStopOrder, $endBusStopOrder) {
                        $query->forTripOnDate($data['trip_id'], $data['date'])
                              ->conflictingWithBusStops($startBusStopOrder, $endBusStopOrder);
                    })
                    ->with('bus')
                    ->get();

        } catch (Exception $e) {
            Log::error('Error fetching available seats', [
                'message' => $e->getMessage(),
                'trip_id' => $data['trip_id'],
                'start_station_id' => $data['start_station_id'],
                'end_station_id' => $data['end_station_id'],
            ]);
            throw $e;
        }
    }
    

    public function bookSeat(array $data): Booking
    {
        try {

            return DB::transaction(function () use ($data) {
                if (!$this->isSeatAvailable(
                    $data['trip_id'],
                    $data['seat_id'],
                    $data['start_station_id'],
                    $data['end_station_id'],
                    $data['date']
                )) {
                    Log::warning('Seat already booked', [
                        'trip_id' => $data['trip_id'],
                        'seat_id' => $data['seat_id'],
                    ]);
                    throw new Exception("Seat is already booked.");
                }

                $booking = Booking::create($data);;

                return $booking;
            });
        } catch (Exception $e) {
            Log::error('Error booking seat', [
                'message' => $e->getMessage(),
                'trip_id' => $data['trip_id'],
                'seat_id' => $data['seat_id'],
            ]);
            throw $e;
        }
    }

    public function isSeatAvailable(int $tripId, int $seatId, int $startStationId, int $endStationId, string $date): bool
    {
        try {

            $orders = $this->getStartEndBusStopOrders($tripId, $startStationId, $endStationId);
            $startOrder = $orders['startOrder'];
            $endOrder = $orders['endOrder'];

            $this->validateTripOrder($startOrder, $endOrder);

           $seatAvailable = !Booking::where('seat_id', $seatId)
            ->forTripOnDate($tripId, $date)
            ->conflictingWithBusStops($startOrder, $endOrder)
            ->exists();

            if (!$seatAvailable) {
                Log::warning('Seat is unavailable', [
                    'seat_id' => $seatId,
                    'trip_id' => $tripId,
                ]);
            }

            return $seatAvailable;
        } catch (Exception $e) {
            Log::error('Error checking seat availability', [
                'message' => $e->getMessage(),
                'trip_id' => $tripId,
                'seat_id' => $seatId,
            ]);
            throw $e;
        }
    }

    private function getStartEndBusStopOrders(int $tripId, int $startStationId, int $endStationId): array
    {
        try {
            $orders = TripStop::where('trip_id', $tripId)
                ->whereIn('station_id', [$startStationId, $endStationId])
                ->pluck('stop_order', 'station_id')
                ->toArray();

            return [
                'startOrder' => $orders[$startStationId] ?? 0,
                'endOrder' => $orders[$endStationId] ?? 0,
            ];
        } catch (Exception $e) {
            Log::error('Error retrieving trip stop orders', [
                'message' => $e->getMessage(),
                'trip_id' => $tripId,
                'start_station_id' => $startStationId,
                'end_station_id' => $endStationId,
            ]);
            throw $e;
        }
    }

    private function validateTripOrder(int $startOrder, int $endOrder): void
    {
        if ($startOrder >= $endOrder) {
            Log::error('Invalid trip', ['start_order' => $startOrder, 'end_order' => $endOrder]);
            throw new Exception("Invalid trip stop: The start station cannot be equal or after the end station.");
        }
    }
}
