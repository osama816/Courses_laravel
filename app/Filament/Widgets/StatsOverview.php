<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', \App\Models\User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            
            Stat::make('Total Courses', \App\Models\Course::count())
                ->description('Available courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),
            
            Stat::make('Total Bookings', \App\Models\Booking::count())
                ->description('All bookings')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('warning'),
            
            Stat::make('Total Revenue', '$' . number_format(\App\Models\Payment::where('status', 'paid')->sum('amount'), 2))
                ->description('Total earnings')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
