<?php

namespace App\Filament\Resources\City\CityResource\Pages;

use App\Filament\Resources\City\CityResource;
use Filament\Pages\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CityResource::class;

    protected static string | array $middlewares = ['permission:Create Cities'];

    protected function getActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
