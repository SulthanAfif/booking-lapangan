<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Field;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return BookingResource::collection(
            $request->user()->bookings()->latest('date')->get()
        );
    }

    public function store(StoreBookingRequest $request, BookingService $service)
    {
        $booking = $service->create(
            user: $request->user(),
            field: Field::findOrFail($request->field_id),
            date: $request->date,
            start: $request->start_time,
            end: $request->end_time,
        );

        return new BookingResource($booking);
    }
}