<?php

namespace App\TourGuide\Pages;

use App\TourGuide\Widgets\CalendarWidget;
use Filament\Pages\Page;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Calendar extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';

    protected static ?string $slug = 'calendar';

    protected ?string $heading = '';

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('navigation.labels.calendar');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.calendar');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
