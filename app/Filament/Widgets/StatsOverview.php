<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getCards(): array
    {
        return [
            Card::make(__('attributes.total_admins'), User::count())
                ->icon('heroicon-o-user-circle')
                ->description(__('attributes.view_all', ['field' => __('attributes.admins')]))
                ->descriptionIcon((app()->getLocale() === 'ar' ? 'heroicon-o-arrow-left' : 'heroicon-o-arrow-right'))
                ->color('primary')
                ->url(route('filament.resources.admins.index')),

            Card::make(__('attributes.total_companies'), Company::count())
                ->icon('heroicon-o-office-building')
                ->description(__('attributes.view_all', ['field' => __('attributes.companies')]))
                ->descriptionIcon((app()->getLocale() === 'ar' ? 'heroicon-o-arrow-left' : 'heroicon-o-arrow-right'))
                ->color('primary')
                ->url(route('filament.resources.companies.index')),

            Card::make(__('attributes.recommended_tourguides'), auth()->user()->favoriteTourguides()->count())
                ->icon('heroicon-o-star')
                ->description(__('attributes.view_all', ['field' => __('attributes.recommended_tourguides')]))
                ->descriptionIcon((app()->getLocale() === 'ar' ? 'heroicon-o-arrow-left' : 'heroicon-o-arrow-right'))
                ->color('primary')
                ->url(route('filament.resources.recommended-tourguides.index')),
        ];
    }
}
