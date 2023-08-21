<?php

namespace App\TourGuide\Pages;

use App\Filament\Pages\Dashboard as PagesDashboard;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class Dashboard extends PagesDashboard
{
    use ContextualPage;

    protected function getWidgets(): array
    {
        return auth('tourguide')->user()->recommended()->exists()
            ? parent::getWidgets()
            : array_filter(parent::getWidgets(), fn($widget) => $widget !== \App\TourGuide\Widgets\RecommendedWidget::class);
    }
}
