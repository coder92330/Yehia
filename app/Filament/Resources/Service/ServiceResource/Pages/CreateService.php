<?php

namespace App\Filament\Resources\Service\ServiceResource\Pages;

use App\Filament\Resources\Service\ServiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ServiceResource::class;

    protected static string | array $middlewares = ['permission:Create Services'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
