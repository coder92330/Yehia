<?php

namespace App\Filament\Resources\Country\CountryResource\Pages;

use App\Filament\Resources\Country\CountryResource;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateCountry extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CountryResource::class;

    protected static string | array $middlewares = ['permission:Create Countries'];

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
