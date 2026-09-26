<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Field;
use Livewire\Attributes\On;
use Livewire\Component;

class FieldSchedule extends Component
{
    public Field $field;
    public array $bookedSlots = [];

    public function mount(Field $field): void
    {
        $this->field = $field;
        $this->loadSlots();
    }

    public function loadSlots(): void
    {
        $this->bookedSlots = Booking::where('field_id', $this->field->id)
            ->whereDate('date', today())
            ->where('status', '!=', 'cancelled')
            ->pluck('start_time')
            ->toArray();
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