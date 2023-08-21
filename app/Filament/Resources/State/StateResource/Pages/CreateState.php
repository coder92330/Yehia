<?php

namespace App\Filament\Resources\State\StateResource\Pages;

use App\Filament\Resources\State\StateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateState extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = StateResource::class;

    protected static string | array $middlewares = ['permission:Create States'];

    protected function getActions(): array
    {
        return [
             Actions\LocaleSwitcher::make(),
        ];
    }
}
