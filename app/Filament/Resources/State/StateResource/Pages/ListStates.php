<?php

namespace App\Filament\Resources\State\StateResource\Pages;

use App\Filament\Resources\State\StateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = StateResource::class;

    protected static string | array $middlewares = ['permission:List States'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
