<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Event;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class BookingOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            $this->getBookingCard(),
            $this->getConfirmedBookingsCard(),
        ];
    }

    private function getBookingCard()
    {
        $chart = Event::selectRaw("COUNT(*) total, DATE_FORMAT(created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make(__('attributes.total_bookings'), Event::count())
            ->icon('heroicon-o-ticket')
            ->description(__('attributes.view_all', ['field' => __('attributes.bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('filament.resources.bookings.index'));
    }

    private function getConfirmedBookingsCard()
    {
        $chart = Order::selectRaw("COUNT(*) total, DATE_FORMAT(created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make(__('attributes.total_confirmed_bookings'), Order::count())
            ->icon('heroicon-o-clipboard-check')
            ->description(__('attributes.view_all', ['field' => __('attributes.confirmed_bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('filament.resources.confirmed-bookings.index'));
    }

    private function getChartColor($chart)
    {
        return count($chart) >= 2
            ? (end($chart) > $chart[array_key_last($chart) - 1]
                ? 'success'
                : (end($chart) < $chart[array_key_last($chart) - 1] ? 'danger' : 'primary'))
            : 'primary';
    }

    private function getDescriptionIcon($chart)
    {
        return count($chart) >= 2
            ? (end($chart) > $chart[array_key_last($chart) - 1]
                ? 'heroicon-s-trending-up'
                : (end($chart) < $chart[array_key_last($chart) - 1] ? 'heroicon-s-trending-down' : 'heroicon-o-minus'))
            : (app()->getLocale() === 'ar' ? 'heroicon-o-arrow-left' : 'heroicon-o-arrow-right');
    }
}
