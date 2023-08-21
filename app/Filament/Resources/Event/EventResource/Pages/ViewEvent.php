<?php

namespace App\Filament\Resources\Event\EventResource\Pages;

use App\Filament\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string | array $middlewares = ['permission:View Bookings'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
