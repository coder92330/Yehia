<?php

namespace App\Filament\Resources\City\CityResource\Pages;

use App\Filament\Resources\City\CityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CityResource::class;

    protected static string | array $middlewares = ['permission:Edit Cities'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
