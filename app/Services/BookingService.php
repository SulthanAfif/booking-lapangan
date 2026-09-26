<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Jobs\CancelUnpaidBooking;
use App\Jobs\SendBookingConfirmation;
use App\Events\BookingCreated;

class BookingService
{
    public function create(User $user, Field $field, string $date, string $start, string $end): Booking
    {
        $startAt = Carbon::createFromFormat('Y-m-d H:i', "$date $start");
        $endAt   = Carbon::createFromFormat('Y-m-d H:i', "$date $end");

        if (! $field->is_active) {
            throw ValidationException::withMessages(['field_id' => 'Lapangan sedang tidak tersedia.']);
        }
        if ($endAt->lte($startAt)) {
            throw ValidationException::withMessages(['end_time' => 'Jam selesai harus setelah jam mulai.']);
        }
        if ($startAt->isPast()) {
            throw ValidationException::withMessages(['start_time' => 'Tidak bisa memesan waktu yang sudah lewat.']);
        }

        $startTime = $startAt->format('H:i:s');
        $endTime   = $endAt->format('H:i:s');
        $hours     = $startAt->diffInMinutes($endAt) / 60;

        return DB::transaction(function () use ($user, $field, $date, $startTime, $endTime, $hours) {
            // kunci baris lapangan supaya request paralel mengantre
            Field::whereKey($field->id)->lockForUpdate()->firstOrFail();

            $bentrok = Booking::where('field_id', $field->id)
                ->whereDate('date', $date)
                ->where('status', '!=', BookingStatus::Cancelled->value)
                ->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime)
                ->exists();

            if ($bentrok) {
                throw ValidationException::withMessages([
                    'start_time' => 'Jadwal sudah terisi, pilih jam lain.',
                ]);
            }

            $booking = Booking::create([
                'user_id'     => $user->id,
                'field_id'    => $field->id,
                'date'        => $date,
                'start_time'  => $startTime,
                'end_time'    => $endTime,
                'status'      => BookingStatus::Pending,
                'total_price' => (int) ceil($hours * $field->price_per_hour),
            ]);
            SendBookingConfirmation::dispatch($booking);
            CancelUnpaidBooking::dispatch($booking)->delay(now()->addMinutes(30));
            broadcast(new BookingCreated($booking))->toOthers();;

            return $booking;
        });
    }
}