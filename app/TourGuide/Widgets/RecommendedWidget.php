<?php

namespace App\TourGuide\Widgets;

use Filament\Widgets\Widget;

class RecommendedWidget extends Widget
{
    protected static string $view = 'filament.widgets.recommended-widget';

    protected static ?int $sort = -4;

    protected int | string | array $columnSpan = 'full';
}
