<?php

namespace App\Filament\Resources\Service\ServiceResource\Pages;

use App\Filament\Resources\Service\ServiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ServiceResource::class;

    protected static string | array $middlewares = ['permission:List Services'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
