<?php

namespace App\Filament\Resources\City\CityResource\Pages;

use App\Filament\Resources\City\CityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCity extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = CityResource::class;

    protected static string | array $middlewares = ['permission:View Cities'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
