<?php

namespace App\Filament\Widgets;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Booking Hari Ini', Booking::whereDate('date', today())->count()),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . number_format(
                Booking::where('status', BookingStatus::Paid->value)
                    ->whereMonth('date', now()->month)
                    ->sum('total_price')
            )),

            Stat::make('Booking Pending', Booking::where('status', BookingStatus::Pending->value)->count())
                ->color('warning'),
        ];
    }
}