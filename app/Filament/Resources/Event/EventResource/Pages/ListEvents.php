<?php

namespace App\Filament\Resources\Event\EventResource\Pages;

use App\Filament\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string | array $middlewares = ['permission:List Bookings'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
