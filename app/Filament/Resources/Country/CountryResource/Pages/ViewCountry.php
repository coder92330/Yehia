<?php

namespace App\Filament\Resources\Country\CountryResource\Pages;

use App\Filament\Resources\Country\CountryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCountry extends ViewRecord
{
    use ViewRecord\Concerns\Translatable;

    protected static string $resource = CountryResource::class;

    protected static string | array $middlewares = ['permission:View Countries'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\EditAction::make(),
        ];
    }
}
