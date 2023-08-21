<?php

namespace App\TourGuide\Resources\Event\EventResource\Pages;

use App\TourGuide\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Iotronlab\FilamentMultiGuard\Concerns\ContextualPage;

class ViewEvent extends ViewRecord
{
    use ContextualPage, ViewRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
