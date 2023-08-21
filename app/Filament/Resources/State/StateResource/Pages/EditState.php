<?php

namespace App\Filament\Resources\State\StateResource\Pages;

use App\Filament\Resources\State\StateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditState extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = StateResource::class;

    protected static string | array $middlewares = ['permission:Edit States'];

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
