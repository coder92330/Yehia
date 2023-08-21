<?php

namespace App\Filament\Resources\Service\ServiceResource\Pages;

use App\Filament\Resources\Service\ServiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ServiceResource::class;

    protected static string | array $middlewares = ['permission:Edit Services'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
