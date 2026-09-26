<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use SerializesModels;

    public function __construct(public Booking $booking) {}

    public function build()
    {
        return $this->subject('Konfirmasi Booking')
            ->view('emails.booking-confirmation')
            ->with(['booking' => $this->booking]);
    }
}