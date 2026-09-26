<p>Halo {{ $booking->user->name }},</p>
<p>Booking kamu untuk {{ $booking->field->name }} pada {{ $booking->date->format('d M Y') }}
jam {{ $booking->start_time }}–{{ $booking->end_time }} sudah diterima.</p>
<p>Total: Rp {{ number_format($booking->total_price) }}</p>