<?php

namespace App\TourGuide\Widgets;

use App\Models\Agent;
use App\Models\Event;
use App\Models\Favourite;
use App\Models\Order;
use App\Models\Tourguide;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';


    protected function getCards(): array
    {
        return [
            $this->getBookingCard(),
            $this->getConfirmedBookingsCard(),
        ];
    }

    private function getBookingCard()
    {
        $bookings = Event::whereHas('orders', function ($q) {
            $q->whereHas('tourguides', function ($q) {
                $q->where([
                    'status'       => 'approved',
                    'agent_status' => 'approved',
                    'tourguide_id' => auth('tourguide')->id(),
                ]);
            });
        });

        $chart = (clone $bookings)
            ->selectRaw("COUNT(events.id) total, DATE_FORMAT(events.created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make('Total Bookings', $bookings->count())
            ->icon('heroicon-o-ticket')
            ->description(__('attributes.view_all', ['field' => __('attributes.bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('tour-guide.resources.bookings.index'));
    }

    private function getConfirmedBookingsCard()
    {
        $confirmedBookings = auth('tourguide')->user()->orders()->where([
            'tourguide_id' => auth('tourguide')->id(),
            'agent_status' => 'approved',
            'status' => 'approved'
        ]);

        $chart = (clone $confirmedBookings)
            ->selectRaw("COUNT(orders.id) total, DATE_FORMAT(orders.created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make('Total Confirmed Bookings', $confirmedBookings->count())
            ->icon('heroicon-o-clipboard-check')
            ->description(__('attributes.view_all', ['field' => __('attributes.confirmed_bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('tour-guide.resources.confirmed-bookings.index'));
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
