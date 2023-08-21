<?php

namespace App\Agent\Pages;

use App\Agent\Widgets\CalendarWidget;
use App\Models\Agent;
use App\Models\Order;
use Filament\Pages\Page;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Calendar extends Page
{
    use ContextualPage;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';

    protected static ?string $slug = 'calendar';

    protected static string | array $middlewares = 'permission:Calendar';

    protected static ?int $navigationSort = 2;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return __('navigation.labels.calendar');
    }

    protected static function getNavigationLabel(): string
    {
        return __('navigation.labels.calendar');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth('agent')->user()->hasPermissionTo('Calendar');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }
}
