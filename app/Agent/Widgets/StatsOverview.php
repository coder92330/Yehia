<?php

namespace App\Agent\Widgets;

use App\Models\Agent;
use App\Models\Event;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '1d';

    protected function getCards(): array
    {
        return [
            $this->getBookingCard(),
            $this->getConfirmedBookingsCard(),
            $this->getFavouriteTourguidesCard(),
        ];
    }

    private function getBookingCard()
    {
        $bookings = auth('agent')->user()->hasRole('admin')
            ? Event::whereRelation('company', 'companies.id', auth('agent')->user()->company_id)
            : Event::where('agent_id', auth('agent')->id());

        $chart = (clone $bookings)
            ->selectRaw("COUNT(*) total, DATE_FORMAT(created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make(__('attributes.total_bookings'), $bookings->count())
            ->icon('heroicon-o-ticket')
            ->description(__('attributes.view_all', ['field' => __('attributes.bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('agent.resources.bookings.index'));
    }

    private function getConfirmedBookingsCard()
    {
        $confirmedBookings = auth('agent')->user()->hasRole('admin')
            ? Order::whereRelation('event.company', 'companies.id', auth('agent')->user()->company_id)
            : Order::where(['orderable_type' => Agent::class, 'orderable_id' => auth('agent')->id()]);

        $chart = (clone $confirmedBookings)
            ->selectRaw("COUNT(*) total, DATE_FORMAT(created_at, '%y-%m') date")
            ->groupBy('date')
            ->pluck('total')
            ->toArray();

        return Card::make(__('attributes.total_confirmed_bookings'), $confirmedBookings->count())
            ->icon('heroicon-o-clipboard-check')
            ->description(__('attributes.view_all', ['field' => __('attributes.confirmed_bookings')]))
            ->descriptionIcon($this->getDescriptionIcon($chart))
            ->color($this->getChartColor($chart))
            ->chart($chart)
            ->url(route('agent.resources.confirmed-bookings.index'));
    }

    private function getFavouriteTourguidesCard()
    {
        $favouriteTourguides = auth('agent')->user()->favoriteTourguides()->count();

        return Card::make(__('attributes.favourite_tourguides'), $favouriteTourguides)
            ->icon('heroicon-o-heart')
            ->description(__('attributes.view_all', ['field' => __('attributes.favourite_tourguides')]))
            ->descriptionIcon((app()->getLocale() === 'ar' ? 'heroicon-o-arrow-left' : 'heroicon-o-arrow-right'))
            ->color('primary')
            ->url(route('agent.resources.favourites.index'));
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
