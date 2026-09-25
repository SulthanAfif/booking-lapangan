<?php

namespace Tests\Feature;

use App\Models\Field;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private function setUpData(): array
    {
        $user  = User::factory()->create();
        $field = Field::create(['name' => 'Futsal A', 'type' => 'futsal', 'price_per_hour' => 150000]);
        return [$user, $field, now()->addDay()->toDateString()];
    }

    public function test_booking_berhasil_dan_harga_dihitung(): void
    {
        [$user, $field, $date] = $this->setUpData();

        $booking = app(BookingService::class)->create($user, $field, $date, '19:00', '21:00');

        $this->assertSame(300000, $booking->total_price);
    }

    public function test_jadwal_bentrok_ditolak(): void
    {
        [$user, $field, $date] = $this->setUpData();
        $service = app(BookingService::class);
        $service->create($user, $field, $date, '19:00', '21:00');

        $this->expectException(ValidationException::class);
        $service->create($user, $field, $date, '20:00', '22:00');
    }

    public function test_jadwal_bersambung_diperbolehkan(): void
    {
        [$user, $field, $date] = $this->setUpData();
        $service = app(BookingService::class);
        $service->create($user, $field, $date, '19:00', '20:00');

        $booking = $service->create($user, $field, $date, '20:00', '21:00');

        $this->assertNotNull($booking->id);
    }
}