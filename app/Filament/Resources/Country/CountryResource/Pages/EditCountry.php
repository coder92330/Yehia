<?php

namespace App\Filament\Resources\Country\CountryResource\Pages;

use App\Filament\Resources\Country\CountryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCountry extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CountryResource::class;

    protected static string | array $middlewares = ['permission:Edit Countries'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
