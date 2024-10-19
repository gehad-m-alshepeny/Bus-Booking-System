<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\AvailableSeatsRequest;
use App\Http\Requests\Booking\BookSeatRequest;
use App\Http\Resources\Booking\AvailableSeatResource;
use App\Http\Resources\Booking\BookingResource;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Exception;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function availableSeats(AvailableSeatsRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $availableSeats = $this->bookingService->getAvailableSeats($validatedData);

            return response()->json([
                'data' => AvailableSeatResource::collection($availableSeats)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch available seats',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function bookSeat(BookSeatRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['user_id'] =  $request->user()->id; 

            $booking = $this->bookingService->bookSeat($validatedData);

            return response()->json([
                'message' => 'Seat booked successfully',
                'data' => new BookingResource($booking)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to book seat',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

