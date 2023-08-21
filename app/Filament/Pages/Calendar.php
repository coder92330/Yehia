<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Page;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';

    protected static ?string $slug = 'calendar';

    protected static bool $shouldRegisterNavigation = false;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('navigation.labels.calendar');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.calendar');
    }

    public function mount()
    {
        abort_unless(auth()->user()->hasPermissionTo('Calendar'), 403, __('messages.unauthorized'));
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CalendarWidget::class,
        ];
    }

}
