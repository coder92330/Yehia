<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected function getWidgets(): array
    {
        return array_filter(parent::getWidgets(), function ($widget) {
            return !in_array($widget, [
                \App\Filament\Widgets\CalendarWidget::class,
                \App\TourGuide\Widgets\CalendarWidget::class,
                \App\Agent\Widgets\CalendarWidget::class
            ], true);
        });
    }
}
