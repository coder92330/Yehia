<?php

namespace App\Filament\Resources\Country\CountryResource\Pages;

use App\Filament\Resources\Country\CountryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CountryResource::class;

    protected static string | array $middlewares = ['permission:List Countries'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
