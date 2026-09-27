<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Field;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;
use Livewire\Component;

class FieldSchedule extends Component
{
    public Field $field;
    public string $date;
    public array $bookedSlots = [];
    public ?string $errorMessage = null;
    public ?string $successMessage = null;

    public array $operatingHours = [
        '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
        '14:00', '15:00', '16:00', '17:00', '18:00',
        '19:00', '20:00', '21:00',
    ];

    public function mount(Field $field): void
    {
        $this->field = $field;
        $this->date = today()->toDateString();
        $this->loadSlots();
    }

    public function loadSlots(): void
    {
        $this->bookedSlots = Booking::where('field_id', $this->field->id)
            ->whereDate('date', $this->date)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(fn ($b) => substr($b->start_time, 0, 5))
            ->toArray();
    }

    public function updatedDate(): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;
        $this->loadSlots();
    }

    public function book(string $slot): void
    {
        $this->errorMessage = null;
        $this->successMessage = null;

        $end = date('H:i', strtotime($slot) + 3600); // default durasi 1 jam

        try {
            app(BookingService::class)->create(
                user: auth()->user(),
                field: $this->field,
                date: $this->date,
                start: $slot,
                end: $end,
            );

            $this->successMessage = "Berhasil booking jam {$slot}–{$end}.";
            $this->loadSlots();
        } catch (ValidationException $e) {
            $this->errorMessage = collect($e->errors())->flatten()->first();
        }
    }

    #[On('echo:fields.{field.id},BookingCreated')]
    public function onBookingCreated(): void
    {
        $this->loadSlots();
    }

    public function render()
    {
        return view('livewire.field-schedule');
    }
}
