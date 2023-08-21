<?php

namespace App\Filament\Resources\Event\EventResource\Pages;

use App\Filament\Resources\Event\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = EventResource::class;

    protected static string | array $middlewares = ['permission:Edit Bookings'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
