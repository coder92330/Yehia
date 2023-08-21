<?php

namespace App\Filament\Resources\City\CityResource\Pages;

use App\Filament\Resources\City\CityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CityResource::class;

    protected static string | array $middlewares = ['permission:List Cities'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
