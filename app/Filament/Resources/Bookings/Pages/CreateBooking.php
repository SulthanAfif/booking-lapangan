<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use App\Services\BookingService;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
    protected function handleRecordCreation(array $data): Booking
{
    return app(BookingService::class)->create(
        user: User::findOrFail($data['user_id']),
        field: Field::findOrFail($data['field_id']),
        date: $data['date'],
        start: $data['start_time'],
        end: $data['end_time'],
    );
}
}
